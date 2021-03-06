var root = document.location.hostname;
(function($) {
  // Convert milliseconds into Hours (h), Minutes (m), and Seconds (s)
  var waveform;
  var wave_animate = '<span class="musicbar inline m-l-sm animate" style="width:20px;height:20px;" id="animate_span"><span class="bar1 a1 bg-primary lter"></span><span class="bar2 a2 bg-info lt"></span><span class="bar3 a3 bg-success"></span><span class="bar4 a4 bg-warning dk"></span><span class="bar5 a5 bg-danger dker"></span><span class="bar6 a3 bg-success"></span> <span class="bar7 a1 bg-primary lter"></span><span class="bar8 a4 bg-warning dk"></span><span class="bar9 a2 bg-info lt"></span><span class="bar10 a5 bg-danger dker"></span></span>';
  var loading_view='<div class="loading-container"><div class="loading_circle"></div></div>';

  var timecode = function(ms) {
    var hms = function(ms) {
      return {
        h: Math.floor(ms/(60*60*1000)),
        m: Math.floor((ms/60000) % 60),
        s: Math.floor((ms/1000) % 60)
      };
    }(ms),
        tc = []; // Timecode array to be joined with '.'

        if (hms.h > 0) {
          tc.push(hms.h);
        }

        tc.push((hms.m < 10 && hms.h > 0 ? "0" + hms.m : hms.m));
        tc.push((hms.s < 10  ? "0" + hms.s : hms.s));

        return tc.join('.');
      };
  // shuffle the array
  var shuffle = function(arr) {
    arr.sort(function() { return 1 - Math.floor(Math.random() * 3); } );
    return arr;
  };

  var debug = true,
  useSandBox = false,
  $doc = $(document),
  domain = root+'/imusify',
  secureDocument = (document.location.protocol === 'https:'),
  log = function(args) {
    try {
      if(debug && window.console && window.console.log){
        window.console.log.apply(window.console, arguments);
      }
    } catch (e) {

    }
  },    
  scApiUrl = function(url, apiKey) {
    var resolver = 'http://'+domain + '/data_api?url=',
    params = 'format=json&consumer_key=' + apiKey +'&callback=?';
    // force the secure url in the secure environment
    if( secureDocument ) {
      url = url.replace(/^http:/, 'https:');
    }

    // check if it's already a resolved api url
    if ( (/api\./).test(url) ) {
      return url + '?' + params;
    } else {
      return resolver + url + '&' + params;
    }
  };

  // TODO Expose the audio engine, so it can be unit-tested
  var audioEngine = function() {
    var html5AudioAvailable = function() {
      var state = false;
      try{
        var a = new Audio();
        state = a.canPlayType && (/maybe|probably/).test(a.canPlayType('audio/mpeg'));
          // uncomment the following line, if you want to enable the html5 audio only on mobile devices
          // state = state && (/iPad|iphone|mobile|pre\//i).test(navigator.userAgent);
        }catch(e){
          // there's no audio support here sadly
        }

        return state;
      }(),
      callbacks = {
        onReady: function() {
          $doc.trigger('scPlayer:onAudioReady');
        },
        onPlay: function() {
          $doc.trigger('scPlayer:onMediaPlay');
          /*alert("play");*/
        },
        onPause: function() {
          $doc.trigger('scPlayer:onMediaPause');
        },
        onEnd: function() {
          $doc.trigger('scPlayer:onMediaEnd');
          console.log("demo");
        },
        onBuffer: function(percent) {
          $doc.trigger({type: 'scPlayer:onMediaBuffering',percent: percent});
        }
      };

      var html5Driver = function() {
        var player = new Audio(),
        onTimeUpdate = function(event){
          var obj = event.target,
          buffer = ((obj.buffered.length && obj.buffered.end(0)) / obj.duration) * 100;
            // ipad has no progress events implemented yet
            callbacks.onBuffer(buffer);
            // anounce if it's finished for the clients without 'ended' events implementation
            if (obj.currentTime === obj.duration) { callbacks.onEnd(); }
          },

          onProgress = function(event) {
            var obj = event.target,
            buffer = ((obj.buffered.length && obj.buffered.end(0)) / obj.duration) * 100;
            callbacks.onBuffer(buffer);
          };

          $('<div class="sc-player-engine-container"></div>').appendTo(document.body).append(player);

      // prepare the listeners
     // player.addEventListener('play', callbacks.onPlay, false);
     player.addEventListener('play', callbacks.onPlay, false);
     
      // handled in the onTimeUpdate for now untill all the browsers support 'ended' event
      // player.addEventListener('ended', callbacks.onEnd, false);
      player.addEventListener('timeupdate', onTimeUpdate, false);
      player.addEventListener('progress', onProgress, false);


      return {
        load: function(track, apiKey) {
          //alert("a")
          player.pause();
          player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
          player.load();
          player.play();
        },
        
        play: function() {
          player.play();
        },
        pause: function() {
          player.pause();
        },
        stop: function(){
          if (player.currentTime) {
            player.currentTime = 0;
            player.pause();
          }
        },
        seek: function(relative,track){

         /* console.log(typeof(track));
          console.log(track);
          console.log(relative);*/

          if(typeof(track)!="undefined" && track!=''){
            player.pause();
            player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
            player.load();
            
            player.currentTime = relative/1000;
          }
          else{
            player.currentTime = player.duration * relative;
          }
          
          player.play();
        },
        changeCurrentTime:function(track,currentTime){
         if(typeof track != "undefined" && track != ""){
          player.pause();
          
          player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
          player.load();
        } 
        var v=currentTime/1000;

        player.currentTime = v;
        player.play();
      },
      getDuration: function() {
        return player.duration * 1000;
      },
      getPosition: function() {
        return player.currentTime * 1000;
      },
      setVolume: function(val) {
        player.volume = val / 100;
      }

    };

  };

  var flashDriver = function() {
    var engineId = 'scPlayerEngine',
    player,
    flashHtml = function(url) {
      var swf = (secureDocument ? 'https' : 'http') + '://player.' + domain +'/player.swf?url=' + url +'&amp;enable_api=true&amp;player_type=engine&amp;object_id=' + engineId;
      if ($.browser.msie) {

        return '<object height="100%" width="100%" id="' + engineId + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" data="' + swf + '">'+
        '<param name="movie" value="' + swf + '" />'+
        '<param name="allowscriptaccess" value="always" />'+
        '</object>';
      } else {

        return '<object height="100%" width="100%" id="' + engineId + '">'+
        '<embed allowscriptaccess="always" height="100%" width="100%" src="' + swf + '" type="application/x-shockwave-flash" name="' + engineId + '" />'+
        '</object>';
      }
    };


      // listen to audio engine events
      // when the loaded track is ready to play
      imusify.addEventListener('onPlayerReady', function(flashId, data) {
        player = imusify.getPlayer(engineId);
        callbacks.onReady();
      });

      // when the loaded track finished playing
      imusify.addEventListener('onMediaEnd', callbacks.onEnd);

      // when the loaded track is still buffering
      imusify.addEventListener('onMediaBuffering', function(flashId, data) {
        callbacks.onBuffer(data.percent);
      });

      // when the loaded track started to play
      imusify.addEventListener('onMediaPlay', callbacks.onPlay);

      // when the loaded track is was paused
      imusify.addEventListener('onMediaPause', callbacks.onPause);

      return {
        load: function(track) {
          var url = track.uri;
          if(player){
            player.api_load(url);
          }else{
            // create a container for the flash engine (IE needs this to operate properly)
            $('<div class="sc-player-engine-container"></div>').appendTo(document.body).html(flashHtml(url));
          }
        },
        play: function() {
          player && player.api_play();
        },
        pause: function() {
          player && player.api_pause();
        },
        stop: function(){
          player && player.api_stop();
        },
        seek: function(relative){
          player && player.api_seekTo((player.api_getTrackDuration() * relative));
        },
        getDuration: function() {
          return player && player.api_getTrackDuration && player.api_getTrackDuration() * 1000;
        },
        getPosition: function() {
          return player && player.api_getTrackPosition && player.api_getTrackPosition() * 1000;
        },
        setVolume: function(val) {
          if(player && player.api_setVolume){
            player.api_setVolume(val);
          }
        }

      };
    };

    return html5AudioAvailable? html5Driver() : flashDriver();

  }();

  var addLoading=function(){
    $(".sc-player").find(".sc-play").html(loading_view);
  }
  var removeLoading=function(){
    $(".sc-player").find(".sc-play").html('');
  }
  var findElementById = function(elements, id) {
    if(typeof(elements)==="undefined"){return false;}
    var arr=$.grep(elements, function(e) { return e.id === id; });
    if(arr.length==0){return false;}
    return arr[0];
    /*return $.grep(elements, function(e) { return e.id === id; })[0];*/
  }
  var findElementByUrl = function(elements, url) {
    if(typeof(elements)==="undefined"){return false;}
    var arr=$.grep(elements, function(e) { return e.url === url; });
    if(arr.length==0){ return false;}
    return arr[0];
    /*return $.grep(elements, function(e) { return e.url === url; })[0];*/
  }
  var findElementByTrack = function(elements, track) {
   if(typeof(elements)==="undefined"){ return false;}
   var arr=$.grep(elements, function(e) { return e.permalink_url === track; });
   if(arr.length==0){return false;}
   return arr[0];
 }

 var apiKey,
 didAutoPlay = false,
 players = [],
 tracks=[],
 updates = {},
 currentUrl,
 loaded=false,
 changed=false,
 clearQueue=function($player,$queuelist ){
  $queuelist.empty();
  $player.data('queue',[]);
},
getIndexInQueue=function(elements,track){
  if(elements==null || elements.length==0){return -1;}
  return elements.map(function (element) {return element.permalink_url;})
  .indexOf(track);
},
loadTracksData = function($player, links, key,callback) {
  var index = 0;
  var playerObj ;
  loadUrl = function($player,link,id) {
   var apiUrl = scApiUrl(link.track, apiKey);
           //alert(apiUrl);

           $.getJSON(apiUrl, function(data) { 
            //j
          // $.getJSON(link.track, function(data) {	
            var found=false;
            if(typeof(link.id)!='undefined'){
              playerObj=findElementById($player.data('player'),link.id);
              if(typeof(playerObj)!='undefined'){
                found=true;
              }
            }
             // alert(apiUrl+"  "+found)
             if(found==false){
               playerObj = {node: $player, tracks: [],};
               playerObj.id=data.id+"_"+data.kind;
               playerObj.url=link.url;
               playerObj.type=data.kind;
             }
             found=false;
             index += 1;


             if(data.tracks){
              //log('data.tracks', data.tracks);
              playerObj.tracks = playerObj.tracks.concat(data.tracks);
            }else if(data.duration){
             // log('data.duration', data.tracks);
              // a secret link fix, till the SC API returns permalink with secret on secret response
              //data.permalink_url = link.url;
              // if track, add to player
              playerObj.tracks.push(data);
            }else if(data.creator){
              // it's a group!
              links.push({track:data.uri + '/tracks',id:data.id+"_"+data.kind});
            }else if(data.username){

              // if user, get his tracks or favorites
              if(/favorites/.test(link.url)){
                links.push({track:data.uri + '/favorites',id:data.id+"_"+data.kind});
              }else{

                links.push({track:data.uri + '/tracks',id:data.id+"_"+data.kind});
              }
            }else if($.isArray(data)){
              // log('concat.tracks', data.tracks);
              playerObj.tracks = playerObj.tracks.concat(data);
            }

            if(typeof($player.data('tracks'))==="undefined"){
             $player.data('tracks',[]);
           }


           if(found==false){
             // console.log(playerObj.tracks)
             $.each(playerObj.tracks,function(i,v){
              if(!findElementById($player.data('tracks'),v.id)){
                $player.data('tracks').push(v);
              }
            });
              // players.push(playerObj);
              if(typeof($player.data('player'))==="undefined"){
               $player.data('player',[]);
               
             }
             
             $player.data('player').push(playerObj)
           }
           if(links[index]){

              // if there are more track to load, get them from the api
              loadUrl($player,links[index]);
            }else{
            //  alert("c")
              // if loading finishes, anounce it to the GUI
              /*  $(document).trigger({type:'onTrackDataLoaded', playerObj: playerObj, url: apiUrl});*/
              $(document).trigger({type:'onTrackDataLoaded', playerObj: playerObj, url: apiUrl});
              
              if(typeof(callback)!="undefined" && typeof(callback)=="function"){
               eval("callback()");
             }
           }
         });
};
apiKey = key;

        // load first tracks
        loadUrl($player,links[index]);
        
      },
      artworkImage = function(track, usePlaceholder) {
        if(usePlaceholder){
          return '<div class="sc-loading-artwork">Loading Artwork</div>';
        }else if (track.artwork_url) {
          return '<img src="' + track.artwork_url.replace('-large', '-t300x300') + '"/>';
        }else{
          return '<div class="sc-no-artwork">No Artwork</div>';
        }
      },
      generateWaveform=function($player,track){
        var index=getIndexInQueue($player.data("queue"),track.permalink_url);
        var track_queue=$player.data("queue")[index];
        $(".sc-waveform-container[data-track='"+track.permalink_url+"']").each(function(){
          if($(this).hasClass('loaded')){
            return true;
          }
          $(this).find("canvas").remove();
          $(this).addClass('loaded')
          var waveform_width = $(this).outerWidth();

          var waveform_height = 30;
          $player.data("queue")[index].waveform[$player.data("queue")[index].waveform.length] = new Waveform({
            container: $(this)[0],
            outerColor: "transparent",
            width: waveform_width,
            height: waveform_height,
            innerColor: function(x){     

             var duration = audioEngine.getDuration(),
             position = audioEngine.getPosition(),
             relative = (position / duration);


             if( x < position / duration){
                        //  console.log("1");
                        return "rgba(234,63,79, 0.8)";
                      }else if((x*100) < track.buffer){
                       //   console.log("2");
                       return "rgba(0, 0, 0, 0.8)";
                     }else{
                         // console.log("3");
                         return "rgba(0, 0, 0, 0.4)";
                       }
                     },
                     data: $player.data("queue")[index].wavedata
                   }); 
        })
           /*$(".sc-waveform-container.loaded[data-track!='"+track.permalink_url+"']").each(function(){

                var index=getIndexInQueue($player.data("queue"),$(this).attr("data-track"));
                alert(index)
                $.each( $player.data("queue")[index].waveform,function(i,v){
                    v.redraw();
                })
})*/
},
updateWaveform = function($player,url,kind,track){
  var index=getIndexInQueue($player.data("queue"),track.permalink_url);
  var track_queue=$player.data("queue")[index];
  /*new waveform rendering*/
  if(typeof($player.data("queue")[index].waveform_loded)==='undefined'){
    $player.data("queue")[index].waveform_loded=false
    $player.data("queue")[index].buffer=0;
    $player.data("queue")[index].waveform=[];
  }
  if($player.data("queue")[index].waveform_loded==false){
    $.getJSON(track.waveform_url, {}, function(d){       
      $player.data("queue")[index].waveform_loded=true;
      $player.data("queue")[index].wavedata=d;

      var bar_width = 3;
      var bar_gap = 0.3;
      generateWaveform($player,track);
               // console.log($player.data())  ;
               $player.trigger('onPlayerTrackSwitch.scPlayer', [track]);    
             });
  }
  else{
    generateWaveform($player,track);
    $player.trigger('onPlayerTrackSwitch.scPlayer', [track]);    
  }

},
updateTrackInfo = function($player,url,kind,track,wavegenerate) {
  /*console.log(url);*/
  /*if($player.data("kind")!=kind){

    $('.sc-waveform-container', $player).html('');
    $('.sc-waveform-container', $player).removeClass('loaded');
    $(".sc-waveform-container").html('');
    $(".sc-waveform-container").removeClass('loaded');
     
   }
   else if( $player.data("track")!=track.permalink_url){
    $('.sc-waveform-container', $player).html('');
    $('.sc-waveform-container', $player).removeClass('loaded');
  }*/
  $('.sc-player .sc-waveform-container').html('');
  $('.sc-player .sc-waveform-container').removeClass('loaded');

  $player.data("url",url);
  $player.data("kind",kind);
  $player.data("track",track.permalink_url);
  $('.sc-info').each(function(index) {
    $('h2', this).html('<a data-role="trackdetail" href="' + track.permalink_url +'" date-href="' + track.permalink_url +'">' + track.title + '</a>');
    $('span', this).html('<a data-role="profile" href="' + track.user.permalink_url +'">' + track.user.username + '</a>');
    $('img', this).attr("src",track.track_avtar);
    $(".sc-player").find(".transparent_img_player").css("background-image", "url("+track.track_avtar+")");  
    $('p', this).html(track.description || 'no Description');
  });

        // update the track duration in the progress bar
        $('.sc-duration').html(timecode(track.duration));
        $('.sc-position').html(timecode(0));
        // put the waveform into the progress bar
        $('.sc-player .sc-waveform-container').attr("data-track",track.permalink_url);
        if(typeof(wavegenerate)!='undefined' && wavegenerate==false){
         $player.trigger('onPlayerTrackSwitch.scPlayer', [track]);    
       }
       else{
        updateWaveform($player,url,kind,track);
      }

      /*new waveform rendering ends*/



    },
    play = function(track) {
      var url = track.permalink_url;
      if(currentUrl === url){
          // log('will play');

          audioEngine.play();
        }else{
          currentUrl = url;
          // log('will load', url);
          audioEngine.load(track, apiKey);

        }
      },
      getPlayerData = function(node,url) {
		  /* console.log(players)
		  console.log(url) */
		  return findElementByUrl(players,url);
		//return players[$(node).data('sc-player').id];
  },
  updatePlayStatus = function(player, status) {
    if(status){
          // reset all other players playing status
          //$('div.sc-player.playing').removeClass('playing');
        }
        $('.sc-player').toggleClass('playing', status)
        $(player).trigger((status ? 'onPlayerPlay' : 'onPlayerPause'));
      },
      onPlay = function(player,kind,url,track) {
        /*console.log(player);
        console.log(kind);
        console.log(url);
        console.log("Demo "+url);*/
        //alert("Play 2");
        addLoading();
        var song_new=false;
        if(player.data("track")!=track.permalink_url){
          song_new=true;
          //alert("JJ");
        }
        updateTrackInfo(player,url,kind,track,false);
        // cache the references to most updated DOM nodes in the progress bar
        updates = {
          player:player,
          position:  $('.sc-position'),
          url:url,
          kind:kind,
          track:track
          /*$buffer: $('.sc-buffer', player),
          $played: $('.sc-played', player),
          */

        };
        updatePlayStatus(player, true);
        var index=getIndexInQueue(player.data("queue"), player.data("track"));
        
        if(player.data("queue")[index].position<0){
          player.data("queue")[index].position=0;
        }
        var r=player.data("queue")[index].position;
        if(r==0){
          song_new=true;
          /*Increase counter of track*/
          App.IncreaseTrackCounter(track.id);
          /*Increase counter of track*/
          
        }
        if(song_new){
          onSeek(player,0,r,track);
        }else{
          onSeek(player,0,r);
        }
        /*}
        else{
             play(track);
           }*/

           /*wave_animate*/
           jQuery(".waveform_cont .musicbar").remove();
           jQuery(".waveform_cont").removeClass('playing');
           var wave_playing_selector = jQuery(".waveform_cont[data-track='" +url+"']");
           wave_playing_selector.addClass('playing');
           /*add pause class here*/
           $(".pause-icon").removeClass('pause-icon').addClass('play-icon');
           $(".play-icon[data-track='"+url+"']").removeClass('play-icon').addClass('pause-icon');

           $("#all-items .box").removeClass('active');
           $("#all-items .box[data-track='"+url+"']").addClass('active');
           /*add pause class here ends*/

           wave_playing_selector.append(wave_animate);
           /*wave_animate ends*/
           updateWaveform(player,url,kind,track);
         },
         onPause = function(player) {
       //alert("Pause")
      /* console.log(player);
      console.log(player.url);*/
      $player = $(player);
      current_play_url = $('.sc-trackslist li.active', $player).find(".play-icon a").attr("data-href");
      /*wave_animate*/
      var wave_playing_selector = jQuery(".waveform_cont[data-track='" +current_play_url+"']");
      wave_playing_selector.find(".musicbar").removeClass('animate');
      /*wave_animate ends*/
      updatePlayStatus(player, false);
      audioEngine.pause();

      /*add pause class here*/
      $(".pause-icon[data-track='"+current_play_url+"']").removeClass('pause-icon').addClass('play-icon');

    },
    onFinish = function() {
      var $player = $('.big_player'),
      $nextItem;
        // update the scrubber width
      //  updates.$played.css('width', '0%');
        // show the position in the track position counter
        $('.sc-position').innerHTML = timecode(0);
        // reset the player state
        updatePlayStatus($player, false);
        // stop the audio
        audioEngine.stop();

        /*wave_animate removing */
        var wave_playing_selector = jQuery(".waveform_cont .musicbar");
        wave_playing_selector.removeClass("playing");
        wave_playing_selector.remove();
        /*wave_animate removing ends*/
        $player.trigger('onPlayerTrackFinish');        
      },
      onSeek = function(player, relative,t,track) {
        if(typeof(t)!="undefined" && t!=''){

          audioEngine.changeCurrentTime(track,t);

        }
        else{

         audioEngine.seek(relative,track);
       }
       
       $(player).trigger('onPlayerSeek');
     },
     onSkip = function(player) {
      var $player = $(player);
        // continue playing through all players
        log('track finished get the next one');
        $nextItem = $('.sc-trackslist li.active', $player).next('li');
        // try to find the next track in other player
        if(!$nextItem.length){
          $nextItem = $player.nextAll('div.big_player:first').find('.sc-trackslist li.active');
        }
        $nextItem.click();
      },
      soundVolume = function() {
        var vol = 80,
        cooks = document.cookie.split(';'),
        volRx = new RegExp('scPlayer_volume=(\\d+)');
        for(var i in cooks){
          if(volRx.test(cooks[i])){
            vol = parseInt(cooks[i].match(volRx)[1], 10);
            break;
          }
        }
        return vol;
      }(),
      onVolume = function(volume) {
        var vol = Math.floor(volume);
        // save the volume in the cookie
        var date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        soundVolume = vol;
        document.cookie = ['scPlayer_volume=', vol, '; expires=', date.toUTCString(), '; path="/"'].join('');
        // update the volume in the engine
        audioEngine.setVolume(soundVolume);
      },
      positionPoll;

    // listen to audio engine events
    $doc
    .on('scPlayer:onAudioReady', function(event) {
      log('onPlayerReady: audio engine is ready');
      audioEngine.play();
        // set initial volume
        onVolume(soundVolume);
      })
      // when the loaded track started to play
      .on('scPlayer:onMediaPlay', function(event) {
        clearInterval(positionPoll);
        console.log("p");
        positionPoll = setInterval(function() {
          var duration = audioEngine.getDuration(),
          position = audioEngine.getPosition(),
          relative = (position / duration);

          // update the scrubber width
          
          // show the position in the track position counter
          $('.sc-position[data-track="'+ updates.url +'"]').html(timecode(position));
          $('.footer_sc_position .sc-position').html(timecode(position));
          

           //$(".play_playlist_extra[data-id='" + _this.id + "']").
          // announce the track position to the DOM
          var index=getIndexInQueue(updates.player.data("queue"), updates.player.data("track"));
          //J
          if(index >= 0)//J
           updates.player.data("queue")[index].position=position;
         updateWaveform(updates.player,updates.url,updates.kind,updates.track);
        /* if(updates.player.data("queue")[index] >= 0){
           $.each( updates.player.data("queue")[index].waveform,function(i,v){
            console.log("a");
            v.redraw();
          })
      }*/

         // updates.player.data("queue")[index].waveform.


         $doc.trigger({
          type: 'onMediaTimeUpdate.scPlayer',
          duration: duration,
          position: position,
          relative: relative
        });
       }, 500);
})
      // when the loaded track is was paused
      .on('scPlayer:onMediaPause', function(event) {
        clearInterval(positionPoll);
        positionPoll = null;
      })
      // change the volume
      .on('scPlayer:onVolumeChange', function(event) {
        onVolume(event.volume);
      })
      .on('scPlayer:onMediaEnd', function(event) {
        onFinish();
      })
      .on('scPlayer:onMediaBuffering', function(event) {
       // updates.$buffer.css('width', event.percent + '%');
       var index=getIndexInQueue(updates.player.data("queue"), updates.player.data("track"));
       //console.log(index); 

       
      // if(updates.player.data("queue")[index] >= 0)
      updates.player.data("queue")[index].buffer=event.percent;

       // console.log(updates.player.data("queue"))
        // updates.track.buffer=event.percent;
         //console.log(updates)
         //jainesh 
         $.each( updates.player.data("queue")[index].waveform,function(i,v){

          v.redraw();
        })
           //updates.player.data("queue")[index].waveform.redraw();
          //j code ends
        });



  // Generate custom skinnable HTML/CSS/JavaScript based imusify players from links to imusify resources
  $.scPlayer = function(options, node) {
    var opts = $.extend({}, $.scPlayer.defaults, options),
    $source = node && $(node),
    queue=opts.queue,
    $player = $('.big_player'),

    $artworks = $('.sc-artwork-list'),
    $info = $('.sc-info'),
    $controls = $('.sc-controls'),
    $queuelist = $('.sc-trackslist');//queue
    $queuelist_small = $('.sc-queuelist');//queue
    // adding controls to the player
    addLoading();

    // load and parse the track data from imusify API

    if(queue.length > 0)
      loadTracksData($player, queue, opts.apiKey);

		// init the player GUI, when the tracks data was laoded
    $(document).on('onTrackDataLoaded', function(event) {

      //log('onTrackDataLoaded.scPlayer', event.playerObj, playerId, event.target);
      //var tracks = event.playerObj.data("tracks");
      var player=$player.data('tracks');
      var tracks = $player.data('tracks');;
      var type = event.playerObj.type;
      var url;
      if(type!="track"){
        url = event.playerObj.url;
      }
      var callback = (typeof(event.callback)!="undefined")?event.callback:'';
      if (opts.randomize) {
        tracks = shuffle(tracks);
      }
      if(type!='track'){
        clearQueue($player,$queuelist);
      }
      else if(typeof($player.data("kind"))!="undefined" && $player.data("kind")!='track'){
        clearQueue($player,$queuelist);
      }
      else if($player.data('kind')!='track' && type=="track"){
        clearQueue($player,$queuelist);
      }
		//var q_track=$player.data('tracks');
        // create the playlist
        $.each(tracks, function(index, track) {
          var active = index === 0;
          if(type=="track"){
            url=track.permalink_url;
          }
          if($queuelist.find('a[data-href="' + track.permalink_url +'"]').length==0){
            $player.data('queue').push(track);
            $('<li><div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 item-box"><div class="songs"><figure class="loading"><div class="img"></div><img src="'+track.track_avtar+'" alt="'+ track.title+'" title="'+ track.title+'" class="img-responsive"></figure><div class="play-icon"><a data-href="'+track.permalink_url+'" href="javascript:void(0);"></a></div></div><article><h3>'+ track.title+'</h3><span>'+track.user.username+'</span></article></li>').data('sc-track', {id:track.id,kind:type,url:url,track:track.permalink_url}).toggleClass('active', active).appendTo($queuelist);
            $('<article class="tr_item" data-href="'+url+'" data-track="'+track.permalink_url+'" ><a href="javascript:void(0)" class="blue-light-bg"><span>' + track.title + '</span> ' + track.user.username + '</a></article>').appendTo($queuelist_small);
            //$('<li><a href="' + track.permalink_url +'">' + track.title + '</a><span class="sc-track-duration">' + timecode(track.duration) + '</span></li>').data('sc-track', {id:track.id,kind:type,url:url,track:track.permalink_url}).toggleClass('active', active).appendTo($queuelist);
          }
        });

        // update the element before rendering it in the DOM
        $player.each(function() {
          if($.isFunction(opts.beforeRender)){
            opts.beforeRender.call(this, tracks);
          }
        });
        $("#sc-trackslist-gallery").gallery(); 
        // set the first track's duration
        $('.sc-duration').innerHTML = timecode(tracks[0].duration);
        $('.sc-position').innerHTML = timecode(0);
        
        if(loaded==false){
         loaded=true;
         updateTrackInfo($player,url,type,tracks[0]);
       }

        // if continous play enabled always skip to the next track after one finishes
        if (opts.continuePlayback) {
          $player.on('onPlayerTrackFinish', function(event) {
            onSkip($player);
          });
        }
        
        // announce the succesful initialization
        removeLoading();
        $player.trigger('onPlayerInit');

        // if auto play is enabled and it's the first player, start playing
        if(opts.autoPlay && !didAutoPlay){
          onPlay($player);
          didAutoPlay = true;
        }
        if(typeof(callback)!="undefined" && typeof(callback)=="function"){
         eval("callback()");
       }
     });
    // replace the DOM source (if there's one)
    $source.each(function(index) {
      $(this).replaceWith($player);
    });

    return $player;
  };

  // stop all players, might be useful, before replacing the player dynamically
  $.scPlayer.stopAll = function() {
    $('.big_player.playing a.sc-pause').click();
  };

  // destroy all the players and audio engine, usefull when reloading part of the page and audio has to stop
  $.scPlayer.destroy = function() {
    $('.big_player, .sc-player-engine-container').remove();
  };

  // plugin wrapper
  $.fn.scPlayer = function(options) {
    // reset the auto play
    didAutoPlay = false;
    // create the players
    this.each(function() {
      $.scPlayer(options, this);
    });
    return this;
  };

  // default plugin options
  $.scPlayer.defaults = $.fn.scPlayer.defaults = {
    customClass: null,
    // do something with the dom object before you render it, add nodes, get more data from the services etc.
    beforeRender  :   function(tracksData) {
      var $player = $(this);
    },
    // initialization, when dom is ready
    onDomReady  : function() {
      //$('a.sc-player, div.sc-player').scPlayer();
    },
    autoPlay: false,
    continuePlayback: true,
    randomize: false,
    loadArtworks: 5,
    // the default Api key should be replaced by your own one
    // get it here http://imusify.com/you/apps/new
    apiKey: 'htuiRd1JP11Ww0X72T1C3g'
  };


  // the GUI event bindings
  //--------------------------------------------------------

  // toggling play/pause
  $(document).on('click','a.sc-play, a.sc-pause', function(event) {
   event.preventDefault();
   //var $list = $(this).closest('.sc-player').find('.sc-trackslist');
   var $list = $('.sc-player').find('.sc-trackslist');
   addLoading();
    // simulate the click in the tracklist
    $list.find('li.active').click();
    return false;
  });

  
  // selecting tracks in the playlist
  $(document).on('click','.sc-trackslist li', function(event) {
   event.preventDefault();
  /* console.log($(this).data('sc-track').url);
   console.log($(this).data('sc-track').track);
   console.log($(this).data('sc-track').kind);*/

   var $track = $(this),
   $player = $('.big_player'),
   url = $track.data('sc-track').url,
   track = $track.data('sc-track').track;
   kind = $track.data('sc-track').kind;
    //console.log($('.big_player').data());
     //console.log($player.data("queue"))
     var track_data=findElementByTrack($player.data("queue"),track);
     if(!track_data){return false;}
     /* console.log(track_data.buffer);
     console.log(track_data.position);*/
     

     var play = $player.is(':not(.playing)') || $track.is(':not(.active)');
     //alert(play)
    //alert(changed)
    if (play || changed==true) {
        // $player.addClass("loading");
        addLoading();
        if(changed==true){
          changed=false;
          onPause($player);

        }
        
      //console.log(track_data);

      $(".sc-player").find(".sc-waveform-container").removeClass('loaded').html('')
      onPlay($player,kind,url,track_data);
    }
    else{
      onPause($player);
    }

    $track.parent('.sc-trackslist').children("li").removeClass('active');
    $track.addClass('active')
    removeLoading();
    $('.artworks li', $player).each(function(index) {
      $(this).toggleClass('active', index === trackId);
    });
    return false;
  }); 

/*click next j */
jQuery(document).on('click', ".nexttrack",function(e) {
  e.preventDefault();
  var $track = $(this),
  $player = $('.big_player');
  $nextItem = $('.sc-trackslist li.active', $player).next('li');
  if(!$nextItem.length){
    /*$nextItem = $player.nextAll('div.big_player:first').find('.sc-trackslist li.active');*/

  }
  $nextItem.click();
}); 
/*click next ends j */

/*click prev j */
jQuery(document).on('click', ".prevtrack",function(e) {
  e.preventDefault();
  var $track = $(this),
  $player = $('.big_player');
  $prevItem = $('.sc-trackslist li.active', $player).prev('li');
  if(!$prevItem.length){
    /* $prevItem = $player.nextAll('div.big_player:first').find('.sc-trackslist li.active');*/

  }
  $prevItem.click();
}); 
/*click prev ends j */



$(document).on('click','.tr_item', function(event) {
 event.preventDefault();
 //alert("tr_ite play");
//alert($(this).attr("class"));
var clicked_class = event.target.className+" ";
clicked_class_ar = clicked_class.split(/ +/);
is_exist = $.inArray('noclickplay', clicked_class_ar);

if(is_exist < 0)
{
  console.log("tr_item clicked");
  var $player = $('.big_player');
  var $list = $player.find('.sc-trackslist li');
 //var url=$(this).find("a").attr("href");
 var url=$(this).attr("data-href"); 
 //var track=$(this).find("a").attr("data-track");
 var track=$(this).attr("data-track");
 //var title=$(this).find("a").text();
 var title=$(this).text();

 addLoading();
 var exists=false;
 var current_type;
 var exists_player=findElementByUrl($player.data("player"),url);
 if(exists_player){
  exists=true;
}
var track_loaded=findElementByTrack($player.data("tracks"),track);
if(!track_loaded && exists==true){
  current_type=exists_player.type;
  track=exists_player.tracks[0].permalink_url;
  changed=true;
}
else if(track_loaded && exists==false){

  if($player.data('kind')==current_type){
    current_type='track';
    exists=true;
  }
}

   // alert($player.data('kind'))
   // alert(current_type)
   if(typeof(current_type)!='undefined' && current_type!='track' && $player.data('kind')=='track'){

    clearQueue($player,$list);
    changed=true;

  }
  else if(typeof(current_type)!='undefined' && $player.data('kind')!='track' && current_type=="track"){

    clearQueue($player,$list);
    changed=true;
  }
   // alert(exists)
   if(exists==false){

     var t,l;
     t=[{"url":url,"track":url,"title":title}];
   /*alert(url);
   alert(title);*/
   loadTracksData($player, t, apiKey,function(){
    var index=getIndexInQueue($player.data("queue"),track);
    if(index<0){index=0;}
    var $list = $player.find('.sc-trackslist li');
    changed=true;
    $list.eq(index).click();  
  })
 }
 else{

  var index=getIndexInQueue($player.data("queue"),track);
      // alert(index)
      if(index<0){
      //  alert("d")
      var playerObj=findElementByUrl($player.data("player"),url);
      $(document).trigger('onTrackDataLoaded', { playerObj: playerObj, url: url,callback:function(){
          //alert("call")
          index=getIndexInQueue($player.data("queue"),track);
          changed=true;
          if(index<0){index=0;}
            //  alert("ii"+index)
            var $list = $player.find('.sc-trackslist li');
            $list.eq(index).click(); 

          }
        });

    }
    else{
      if(index<0){index=0;}
         //alert("i"+index)
         $list.eq(index).click(); 
       }
     }
     return false;
   }else{      
   }
 });




var scrub = function(node, xPos) {
  var $available = $(node).find('canvas');
  var $player = $('.big_player');
  var current_track=$player.data("track");

  var clicked_track=$(node).attr("data-track");

  var track=findElementByTrack($player.data("tracks"),clicked_track);
  console.log(track);
  if(!track){
    if($(node).parents(".tr_item").length == 0)
     $(node).parents(".track_item").find(".tr_item").click();
   else 
    $(node).parents(".tr_item").click();
  return true;
}
var index=getIndexInQueue($player.data("queue"), clicked_track);
var play=false;
if(index>=0){
  var buffer=$player.data("queue")[index].buffer;
  if(buffer==0){
    play=true
  }
  else{
    var buffer_width=($available.width()*buffer)/100;
    var relative=0;
      //relative = Math.min(buffer_width, (xPos  - $available.offset().left)) / $available.width();
      if(typeof($available.offset())!="undefined"){
        relative =(xPos  - $available.offset().left) / $available.width();
      }

      relative=relative;
      if($player.data("queue")[index].position<=0){
        play=true;
      }
      $player.data("queue")[index].position=relative*track.duration;
      if(current_track!=clicked_track){
        play=true;
      }
    }
  }
  else{
    play=true;
  }
  if(play==true){

    $(".sc-player").removeClass('playing');
    $play=$(".sc-trackslist li a[data-href='"+clicked_track+"']").get(0);

    if(typeof($play)=='undefined'){
     $play=$(".tr_item[data-track='"+clicked_track+"']").get(0);
   }

   if(typeof($play)=='undefined'){
     return true;
   }

   $play.click();
 }
 else{
  if(!$player.hasClass('playing')){
    updatePlayStatus($player)
  }
  onSeek($player,relative);
}


};

var onTouchMove = function(ev) {
  if (ev.targetTouches.length === 1) {
    scrub(ev.target, ev.targetTouches && ev.targetTouches.length && ev.targetTouches[0].clientX);
    ev.preventDefault();
  }
};


  // seeking in the loaded track buffer
  $(document)
  .on('click','.sc-waveform-container', function(event) {

    scrub(this, event.pageX);
    return false;
  })
  .on('touchstart','.sc-waveform-container', function(event) {
    this.addEventListener('touchmove', onTouchMove, false);
    event.originalEvent.preventDefault();
  })
  .on('touchend','.sc-waveform-container', function(event) {
    this.removeEventListener('touchmove', onTouchMove, false);
    event.originalEvent.preventDefault();
  });

  // changing volume in the player
  var startVolumeTracking = function(node, startEvent) {
    var $node = $(node),
    originX = $node.offset().left,
    originWidth = $node.width(),
    getVolume = function(x) {
      return Math.floor(((x - originX)/originWidth)*100);
    },
    update = function(event) {
      $doc.trigger({type: 'scPlayer:onVolumeChange', volume: getVolume(event.pageX)});
    };
    $node.on('mousemove.sc-player', update);
    update(startEvent);
  };

  var stopVolumeTracking = function(node, event) {
    $(node).off('mousemove.sc-player');
  };

  $(document)
  .on('mousedown','.sc-volume-slider', function(event) {
    startVolumeTracking(this, event);
  })
  .on('mouseup','.sc-volume-slider', function(event) {
    stopVolumeTracking(this, event);
  });

  $doc.on('scPlayer:onVolumeChange', function(event) {
    $('span.sc-volume-status').css({width: event.volume + '%'});
  });
  // -------------------------------------------------------------------

  // the default Auto-Initialization
  $(function() {
    if($.isFunction($.scPlayer.defaults.onDomReady)){
      $.scPlayer.defaults.onDomReady();
    }
  });

})(jQuery);
