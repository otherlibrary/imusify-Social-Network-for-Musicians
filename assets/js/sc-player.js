//variable to detect Player status 
// true Playing
// false No play
var playback = false;
var loop = false;
var track_link = '';
var active_addtrack = false;

var root = document.location.hostname;
var track_info_loaded = {};
var _this = this;
var _this_comment_form_post;
var _this_comment_form_url;
var _this_comment_holder;
var _force_to_play = false;
//andy Player loading
//(function($) {


  // Convert milliseconds into Hours (h), Minutes (m), and Seconds (s)
  var waveform;
  var wave_animate = '<span class="musicbar inline m-l-sm animate" style="width:20px;height:20px;" id="animate_span"><span class="bar1 a1 bg-primary lter"></span><span class="bar2 a2 bg-info lt"></span><span class="bar3 a3 bg-success"></span><span class="bar4 a4 bg-warning dk"></span><span class="bar5 a5 bg-danger dker"></span><span class="bar6 a3 bg-success"></span> <span class="bar7 a1 bg-primary lter"></span><span class="bar8 a4 bg-warning dk"></span><span class="bar9 a2 bg-info lt"></span><span class="bar10 a5 bg-danger dker"></span></span>';
  //andy remove class loading_circle
  //var loading_view='<div class="loading-container"><div class="loading_circle"></div></div>';
  var loading_view='<div class="loading-container"><div class=""></div></div>';

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
  // Sound Cloud Player
  //reference http://codepen.io/nicholaspetersen/pen/yyVYMY
  var audioEngine = function() {
    var html5AudioAvailable = function() {
      var state = false;
      try{
        var a = new Audio();//initialization for audio element
        state = a.canPlayType && (/maybe|probably/).test(a.canPlayType('audio/mpeg'));
        console.log('Browser can play MP3 or not ', state);
          // uncomment the following line, if you want to enable the html5 audio only on mobile devices
          // state = state && (/iPad|iphone|mobile|pre\//i).test(navigator.userAgent);
        }catch(e){
          // there's no audio support here sadly
          console.log('No HTML 5 Audio support for MP3');
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
          console.log('Pause');
          //reset force to play
          //_force_to_play = false;
        },
        onEnd: function() {
          $doc.trigger('scPlayer:onMediaEnd');
          console.log("Track is played fully");
        },
        onBuffer: function(percent) {
          $doc.trigger({type: 'scPlayer:onMediaBuffering',percent: percent});          
          
          //console.log('Track buffer ', percent);
          
        var position = audioEngine.getPosition();          
        if(navigator.userAgent.indexOf("Firefox") != -1 && position == 0) {            
            console.log('Force to play for Firefox');
            audioEngine.play();
            playback = true;
        }
          //if (percent == 100) console.log('Loaded 100%');
//          if(percent > 10 && !_force_to_play) {
//                console.log('Force to Play');
//                _force_to_play = true;//1 time to force
//                audioEngine.play();
//          }
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

     /* prepare the listeners
     player.addEventListener('play', callbacks.onPlay, false);*/
     player.addEventListener('play', callbacks.onPlay, false);
     
     
     //andy resize     
        $( window ).resize(function() {
            var time = player.currentTime;
            console.log('window resize', time);
            if (time > 0){
                //from play to pause
                $('.tr_item.active').trigger("click");
                //from pause to play to update waveform
                $('.tr_item.active').trigger("click");                
                console.log('Player pause and play');
                //var $player = $('.big_player');
//                $doc.trigger('scPlayer:onMediaPause');
//                $doc.trigger('scPlayer:onMediaPlay'); 
                 // onPause($player);
//                audioEngine.pause();
//                $('.big_player').delay(2000);
//                audioEngine.play();
                  //onPlay($player);                  
            }
            //audioEngine.pause();
            //audioEngine.play();
        });
        
        
        //control Repeat or Loop for track
        $(document).on('click',".repeat-icon", function(event) {
            if (!loop) {
                $(this).addClass("active");
                audioEngine.loop();
                loop = true;
            }
            else {
                $(this).removeClass("active");
                audioEngine.unloop();
                loop = false;
            }             
        })
        
        
        //document loaded Update pause-icon if Player is active
        //get first li of ul id home_left_menu    
       $(document).on('click',"#home_left_menu li:first, ul.heading li:first", function(event) {
            var time = player.currentTime;
            console.log('home loading 187 ', time);
            if (time > 0 && playback && track_link){                                
                //wait util ajax call completes
                $( document ).ajaxComplete(function() {
                    console.log('Track ',track_link);
                    var r = $("a[data-track='" + track_link +"']").removeClass('play-icon');  
                    $("a[data-track='" + track_link +"']").addClass('pause-icon'); 
                    //console.log('result ',r);
                });
                                      
            } else if (time > 0 && !playback){
                $( document ).ajaxComplete(function() {
                    console.log('Track ',track_link);
                    $("a[data-track='" + track_link +"']").removeClass('pause-icon'); 
                    var r = $("a[data-track='" + track_link +"']").addClass('play-icon');                      
                    //console.log('result ',r);
                });
            }
            
                //andy
                $( document ).ajaxComplete(function() {                    
                    console.log('Home Initialize Share');                                 
                    //Register Share link popup                     
                    setTimeout(function(){ 
                        my.sharePopup();
                    },500);
                                                     
                    //my.trackcover_cropic();
                    //Register for Upload page
                    //my.initUpload();
                });
  
        }); 
        
        
  
     /* handled in the onTimeUpdate for now untill all the browsers support 'ended' event
     player.addEventListener('ended', callbacks.onEnd, false);*/
     player.addEventListener('timeupdate', onTimeUpdate, false);
     player.addEventListener('progress', onProgress, false);
     return {
      load: function(track, apiKey) {
          console.log('Player HTML5Driver is loading ');
          console.log('Stream URL ', track.stream_url);
          //alert("a")
          player.pause();
          //player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
          player.src = track.stream_url;
          player.load();
          player.play();
        },
        
        play: function() {
          player.play();
        },
        pause: function() {
          player.pause();
        },
        loop: function() {
          player.loop = true;
        },
        unloop: function() {
          player.loop = false;
        },        
        stop: function(){
          if (player.currentTime) {
            player.currentTime = 0;
            player.pause();            
          }
        },
        seek: function(relative,track){//Scrubbing

         if(typeof(track)!="undefined" && track!=''){
          player.pause();
          
          //player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
          player.src = track.stream_url;
          player.load();

          player.currentTime = relative/1000;
        }
        else{
          player.currentTime = player.duration * relative;
        }

        player.play();
      },
      changeCurrentTime:function(track,currentTime){//Scrubbing
       if(typeof track != "undefined" && track != ""){
        player.pause();

        //player.src = track.stream_url + (/\?/.test(track.stream_url) ? '&' : '?') + 'consumer_key=' + apiKey;
        player.src = track.stream_url ;
        player.load();
      } 
      var v=currentTime/1000;

      player.currentTime = v;
      player.play();
    },
    //add new function for Object audioEngine andy
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
  imusify.addEventListener('onPlayerReady', function(flashId, data) {
    player = imusify.getPlayer(engineId);
    callbacks.onReady();
  }); 
  imusify.addEventListener('onMediaEnd', callbacks.onEnd);

  imusify.addEventListener('onMediaBuffering', function(flashId, data) {
    callbacks.onBuffer(data.percent);
  });


  imusify.addEventListener('onMediaPlay', callbacks.onPlay);

  

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
       //no use for below code It is for Flash Player
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


   $.getJSON(apiUrl, function(data) { 

    var found=false;
    if(typeof(link.id)!='undefined'){
      playerObj=findElementById($player.data('player'),link.id);
      if(typeof(playerObj)!='undefined'){
        found=true;
      }
    }

    if(found==false){
     playerObj = {node: $player, tracks: [],};
     playerObj.id=data.id+"_"+data.kind;
     playerObj.url=link.url;
     playerObj.type=data.kind;
   }
   found=false;
   index += 1;



   if(typeof track_info_loaded[data.permalink_url] == "undefined")
   {
    track_info_loaded[data.permalink_url] = data;
  }  





  if(data.tracks){

    playerObj.tracks = playerObj.tracks.concat(data.tracks);
  }else if(data.duration){

    playerObj.tracks.push(data);
  }else if(data.creator){

    links.push({track:data.uri + '/tracks',id:data.id+"_"+data.kind});
  }else if(data.username){


    if(/favorites/.test(link.url)){
      links.push({track:data.uri + '/favorites',id:data.id+"_"+data.kind});
    }else{

      links.push({track:data.uri + '/tracks',id:data.id+"_"+data.kind});
    }
  }else if($.isArray(data)){

    playerObj.tracks = playerObj.tracks.concat(data);
  }

  if(typeof($player.data('tracks'))==="undefined"){
   $player.data('tracks',[]);
 }


 if(found==false){

   $.each(playerObj.tracks,function(i,v){
    if(!findElementById($player.data('tracks'),v.id)){
      $player.data('tracks').push(v);
    }
  });

   if(typeof($player.data('player'))==="undefined"){
     $player.data('player',[]);

   }

   $player.data('player').push(playerObj)
 }
 if(links[index]){


  loadUrl($player,links[index]);
}else{

  $(document).trigger({type:'onTrackDataLoaded', playerObj: playerObj, url: apiUrl});

  if(typeof(callback)!="undefined" && typeof(callback)=="function"){
   eval("callback()");
 }
}
});
};
apiKey = key;


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
        //draw waveform
generateWaveform=function($player,track){
  var index=getIndexInQueue($player.data("queue"),track.permalink_url);
  var track_queue=$player.data("queue")[index];
  $(".sc-waveform-container[data-track='"+track.permalink_url+"']").each(function(){
    if($(this).hasClass('loaded')){
      //console.log('loaded', $(this));  
      return true;
    }
    var container = $(this)[0];
    //console.log('Waveform Container ', container);
    //console.log('Waveform Parent Container ', $(this));
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
        return "rgba(234,63,79, 0.8)";
      }else if((x*100) < track.buffer){
        return "rgba(211, 211, 211, 0.5)";
      }else{
        return "rgba(255,255,255,1)";
        /* return "rgba(0, 0, 0, 0.4)";*/
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
  
  if(typeof($player.data("queue")[index].waveform_loded)==='undefined'){
    $player.data("queue")[index].waveform_loded=false
    $player.data("queue")[index].buffer=0;
    $player.data("queue")[index].waveform=[];
  }
  if($player.data("queue")[index].waveform_loded==false){
    $.getJSON(track.waveform_url, {}, function(d){  
      console.log('Waveform URL ', track.waveform_url);  
      $player.data("queue")[index].waveform_loded=true;
      $player.data("queue")[index].wavedata=d;
      //console.log('wavedata ', d);
      var bar_width = 3;
      var bar_gap = 0.3;
      generateWaveform($player,track);
               console.log('Player current data: ',$player.data()) ;
               
               
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
    $('h2', this).html('<a data-role="trackdetail" title="' + track.title +'" href="' + track.permalink_url +'" date-href="' + track.permalink_url +'">' + track.mini_title + '</a>');
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
        //Play for Firefox
        //var isFirefox = typeof InstallTrigger !== 'undefined';
          // reset all other players playing status
          //$('div.sc-player.playing').removeClass('playing');
          playback = true;          
        }else {
            playback = false;
        }
        console.log('Status of Player ', status);
        $('.sc-player').toggleClass('playing', status)
        $(player).trigger((status ? 'onPlayerPlay' : 'onPlayerPause'));
      },
      onPlay = function(player,kind,url,track) {
        console.log('Player start ', player);
        console.log('Type of playback: ',kind);//type or kind: track or playlist
        //console.log(url);//url of track
        console.log("URL of track "+url);        
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
      console.log('Pause');       
      $player = $(player);
//      current_play_url = $('.sc-trackslist li.active', $player).find(".play-icon a").attr("data-href");
      current_play_url = $('.sc-trackslist li.active', $player).find(".play-btns a").attr("data-href");
      /*wave_animate*/
      var wave_playing_selector = jQuery(".waveform_cont[data-track='" +current_play_url+"']");
      wave_playing_selector.find(".musicbar").removeClass('animate');
      /*wave_animate ends*/
      updatePlayStatus(player, false);
      audioEngine.pause();

      /*add pause class here*/
      $(".pause-icon[data-track='"+current_play_url+"']").removeClass('pause-icon').addClass('play-icon');
       //andy add pause-icon
       if ($('#play_track_detail').hasClass("force-to-pause-icon")) {
             $('#play_track_detail').removeClass("play-icon");
             $('#play_track_detail').addClass("pause-icon");  
             $('#play_track_detail').removeClass("force-to-pause-icon");  
             console.log('click play_track_detail Force to have pause icon');
         }   


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
      onSeek = function(player, relative,t,track) {//seeking
         console.log('Seeking Relative t ', relative,t);
        if(typeof(t)!="undefined" && t!=''){
            
          audioEngine.changeCurrentTime(track,t);

        }
        else{

         audioEngine.seek(relative,track);
       }
       
       $(player).trigger('onPlayerSeek');       
       if(navigator.userAgent.indexOf("Firefox") != -1 && relative == 0) {            
            console.log('Force to play for Firefox');
            audioEngine.play();
        }
       
     },
     onSkip = function(player) {
      var $player = $(player);
        // continue playing through all players
        log('track finished get the next one');
        console.log('Track Finished Get the next one');
        $nextItem = $('.sc-trackslist li.active', $player).next('li');
        // try to find the next track in other player
        if(!$nextItem.length){ //if no more track to play
          $nextItem = $player.nextAll('div.big_player:first').find('.sc-trackslist li.active');
        }
        if(!$nextItem.length){
            $nextItem = $('.sc-queuelist .tr_item.active').next('article');
        }                
        $nextItem.click();                
        if(!$nextItem.length) App.ShowNotification("info","Info","No more track to be played");
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
      console.log("onPlayerReady: audio engine is ready");
      audioEngine.play();
        // set initial volume
        onVolume(soundVolume);
      })
      // when the loaded track started to play
      .on('scPlayer:onMediaPlay', function(event) {
        //music on Play already no need to force to play
        _force_to_play = true;
        clearInterval(positionPoll);
        
        if ($('#play_track_detail').hasClass("load_click")) {
             $('#play_track_detail').trigger("click");      
             $('#play_track_detail').removeClass("load_click")
             console.log('Stop Player for track detail page');
             clearInterval(positionPoll);
         }        
        
        //andy Position to play
        console.log("Started to play after loading track");
        playback = true;
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
         
         //andy remove comment
         if(updates.player.data("queue")[index] >= 0){
           $.each( updates.player.data("queue")[index].waveform,function(i,v){
            console.log("Update waveform");
            v.redraw();
          })
          }

          //updates.player.data("queue")[index].waveform.


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

		// init the player GUI, when the tracks data was loaded
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
          
          //andy Important 
          if($queuelist.find('a[data-href="' + track.permalink_url +'"]').length==0){ //new track Played
            $player.data('queue').push(track);            
            $('<li><div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 item-box"><div class="songs"><figure class="loading"><div class="img"></div><img src="'+track.track_avtar+'" alt="'+ track.mini_title+'" title="'+ track.title+'" class="img-responsive"></figure><div class="play-btns" data-href="'+track.permalink_url+'" data-track="'+track.permalink_url+'"><a class="play-icon" data-href="'+track.permalink_url+'" data-track="'+track.permalink_url+'" href="javascript:void(0);"></a></div></div><article><h3>'+ track.mini_title+'</h3><span>'+track.user.username+'</span></article></li>').data('sc-track', {id:track.id,kind:type,url:url,track:track.permalink_url}).toggleClass('active', active).appendTo($queuelist);
            //check current list has track or not to add active class 
            console.log('Track link: ', track.permalink_url);            
            if ( $(".sc-queuelist article[data-track='"+track.permalink_url+"']").length > 0){
                if (! active_addtrack){
                    $(".sc-queuelist article[data-track='"+track.permalink_url+"']").addClass("active");
                    $(".sc-queuelist article[data-track='"+track.permalink_url+"'] a").addClass("active");
                }                 
            } else $('<article class="tr_item" data-href="'+url+'" data-track="'+track.permalink_url+'" ><a href="javascript:void(0)" class="blue-light-bg"><span>' + track.mini_title + '</span> ' + track.user.username + '</a></article>').appendTo($queuelist_small);            
            active_addtrack = true;
            //$('<li><a href="' + track.permalink_url +'">' + track.title + '</a><span class="sc-track-duration">' + timecode(track.duration) + '</span></li>').data('sc-track', {id:track.id,kind:type,url:url,track:track.permalink_url}).toggleClass('active', active).appendTo($queuelist);
          }                                
        });
        removeDuplicateTrack();        
                        
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

//Forward and Backward buttons
/*click next j */
jQuery(document).on('click', ".nexttrack",function(e) {
  e.preventDefault();
  var $track = $(this),
  $player = $('.big_player');
  $nextItem = $('.sc-queuelist .tr_item.active').next('article');    
  if(!$nextItem.length){
      $nextItem = $('.sc-trackslist li.active', $player).next('li');            
  }
  $nextItem.click();  
  if(!$nextItem.length) App.ShowNotification("info","Info","No more track to be played");
  
}); 
/*click next ends j */

/*click prev j */
jQuery(document).on('click', ".prevtrack",function(e) {
  e.preventDefault();
  var $track = $(this),
  $player = $('.big_player');
  $prevItem = $('.sc-queuelist .tr_item.active').prev('article');    
  if(!$prevItem.length){
      $prevItem = $('.sc-trackslist li.active', $player).prev('li');
    /* $prevItem = $player.nextAll('div.big_player:first').find('.sc-trackslist li.active');*/    
  }
  $prevItem.click();  
  if(!$prevItem.length) App.ShowNotification("info","Info","No more track to be played")
}); 
/*click prev ends j */





$(document).on('click','.tr_item', function(event) {
 event.preventDefault();
 var clicked_class = event.target.className+" ";
 clicked_class_ar = clicked_class.split(/ +/);
 is_exist = $.inArray('noclickplay', clicked_class_ar);
 //removeDuplicateTrack();
 //remove all current active article and anchor for .sc-queuelist
 $(".sc-queuelist .tr_item").each(function(){
    $(this).removeClass('active');
    $(this).children('a').removeClass('active'); 
 });


 //if(e.target.className == "delete_conversation")
 if(event.target.className == "delete_conversation")
 {
    return false;
 }

if(is_exist < 0)
{
    //Andy Song clicked to play
  console.log("tr_item clicked Song clicked to play");
  active_addtrack = false;
  //hide waveform sample andy 
  $("#waveform_sample").hide();
  
  var $player = $('.big_player');
  var $list = $player.find('.sc-trackslist li');
  var url=$(this).attr("data-href"); 
  var track=$(this).attr("data-track");
  //add class active for it
  $(this).addClass('active');
  $(this).children('a').addClass('active');
  
  track_link = track;
  var title=$(this).text();
  
  if ( $(".sc-queuelist article[data-track='"+track+"']").length > 0 ){
    $(".sc-queuelist article[data-track='"+track+"']").addClass("active");
    $(".sc-queuelist article[data-track='"+track+"'] a").addClass("active");
    active_addtrack = true;
  }
  
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

/*Comment functionality all function*/


/*Get comments*/
function string_cut(s,m) {
  return (s.length > m) ? jQuery.trim(s).substring(0, m).split(" ").slice(0, -1).join(" ") + "..."  : s;
};

get_comments = function(url) {

  if (typeof(url) == "undefined" || url == '') {

    var $tracks = $(".track_list_item");
    if ($tracks.length > 0) {
      $tracks.each(function(i) {

        var v = $(this).attr("data-url");

        if(typeof $(this).attr("data-comment") != "undefined" && $(this).attr("data-comment") != "false"){

          var chk_ele = jQuery(".comments-holder").length;
          if( chk_ele != 0 ){

            $.when(
              jQuery(".comments-holder[data-url='" + v + "']").css("width", "100%")
              ).done(              
              setTimeout(function() {
                var $width = jQuery(".comments-holder[data-url='" + v + "']").width();
                var track = track_info_loaded[v];

                if(typeof track.comments != "undefined"){
                  if (track.comments.length > 0) {
                   $.each(track.comments, function(j, k) {
                    var comments_html;
                    if (track.time != '' && k.seconds != '') {
                      var avg = (track.time / k.seconds);
                      var left = ($width / avg);
                      var tooltip_align = 'align-left';                        
                      if (left > ($width / 2)) {
                        tooltip_align = 'align-right';
                      }
                      var trimmed_comment = string_cut(k.comment,150);
                      comments_html = '<span class="a-comment with-tooltip" style="left:' + left + 'px"><div class="aux-padder"></div><span class="dzstooltip skin-black ' + tooltip_align + '" style="width: 250px;"><span class="the-comment-author">@' + k.firstname + '</span> says:<br>' + trimmed_comment + '</span><div class="the-avatar" style="background-image: url(' + k.user_pic + ')" data-name="' + k.firstname + '" data-id="' + k.commentId + '"></div></span>';
                      jQuery(".comments-holder[data-url='" + v + "']").append(comments_html);
                    }                      
                  });
}
}





}, 1000)
);
}
}


});
}
} else {

  var v = url;
  jQuery(".comments-holder[data-url='" + v + "']").css("width", "100%");
  setTimeout(function(){
    var $width = jQuery(".comments-holder[data-url='" + v + "']").width();
    var track = track_info_loaded[url];
    if (track.comments.length > 0) {
      $.each(track.comments, function(j, k) {
        var comments_html;
        if (track.time != '' && k.seconds != '') {
          var avg = (track.time / k.seconds);
          var left = ($width / avg);
          var tooltip_align = 'align-left';

          if (left > ($width / 2)) {
            tooltip_align = 'align-right';
          }
          var trimmed_comment = string_cut(k.comment,150);
          comments_html = '<span class="a-comment with-tooltip" style="left:' + left + 'px"><div class="aux-padder"></div><span class="dzstooltip skin-black ' + tooltip_align + '" style="width: 250px;"><span class="the-comment-author">@' + k.from.username + '</span> says:<br>' + trimmed_comment + '</span><div class="the-avatar" style="background-image: url(' + k.from.avatar_url + ')" data-name="' + k.from.username + '" data-id="' + k.comment_id + '"></div></span>';

          jQuery(".comments-holder[data-url='" + v + "']").append(comments_html);
        }
      })
    }
  },500);
}
}
/*Get comments ends*/




var set_comments_form = function(url, p, n, t) {
  var this_track_data = track_info_loaded[url];
  if (typeof(t) == "undefined") {
    t = '';
  }
  var $commentform = $(".commentForm[data-url='" + url + "']");

  $commentform.find(".commentForm__inputWrapper").attr("disabled", true)
  if (App.config.loggedIn == true) {
    if (this_track_data.commentable == 'true') {
      $commentform.find("input[name=t]").val(t);
      $commentform.find("input[name=userId]").val(config.userIdJs);

      $commentform.find(".commentForm__avatar>img").attr("src", login_img);
      $commentform.addClass("large");
      $commentform.find(".commentForm__inputWrapper").attr("disabled", false);
      set_pad_text($commentform);
      $commentform.slideDown("fast");
      $commentform.find(".commentForm__input").focus();
      return true;
    }

  }

  return false;
}

submit_comment_form = function() {
  $("body").on("submit", ".comment_Form", function(e) {
    e.preventDefault();
    var $data = $(this).serialize();
    var url = $(this).attr("data-url");
    var t = $(".trackId").val();
    var $this = $(this);
    $comment_form = $(".comments-holder[data-url='" + url + "']");
    $commentform = $(".commentForm[data-url='" + url + "']");
    $commentform.slideUp();
    _this._this_comment_form_post = $this;
    _this._this_comment_form_url = url;
    _this._this_comment_holder = $comment_form;
    App.routingAjax(App.config.siteUrl+"newcomment",$data,"",function(response){    
      console.log(response);  
      if(response.status == "success")
      {
        App.ShowNotification("success","success",response.msg);    
        refresh_comments($this,url,$comment_form)      
      }
      else if(response.status == "error")            
      {
        App.ShowNotification("error","Error",response.msg);
      }
      App.initProgressComplete();
    },false,false);



  });
}


refresh_comments = function(){
  if (typeof(_this._this_comment_form_post) != "undefined" && typeof(_this._this_comment_form_url) != "undefined" && typeof(_this._this_comment_holder) != "undefined") {
    var $this = _this._this_comment_form_post;
    $this.find(".commentForm__input").val('');
    var url = _this._this_comment_form_url;
    var $comment_form = _this._this_comment_holder;
    submitValueHandler(site_url + url.substring(1), "action=get_comments", '', "refresh_track_comments");
    $comment_form.find('.cursour_wave').hide();
    _this.get_comments(url);
  }
}



set_pad_text = function($comment_form) {
  var w = $comment_form.find("a.commentForm__recipient").outerWidth(true);
  $(".commentForm__input").css("padding-left", w + "px");
}

var get_relative_mouse_position_element = function(e, _elem) {
 var xpos = e.pageX;
 var w = _elem.width();
 /*Calculate the relative position and make sure it doesn't exceed the buffer's current width.*/
 return Math.min(w, (xpos - _elem.parent().offset().left) / _elem.width());
}

var bind_events_commentbar = function(element) {
  _element = $(".the-bg");
  _element.addClass("binded");
  var current_elem1, current_elem2;
  _element.off('mousemove mouseleave click');
  login_img = 'http://192.168.1.103/imusify/assets/upload/users/64/64/00b6a9e31b323d96b78c7d9cbb66d44b1383601621.jpeg';
  // .the-bg
  $("body").on("click", ".the-bg", function(e) {
    e.preventDefault();
    if (App.config.loggedIn == true) {
      var url = $(this).parent().attr("data-url");
      var $this = $(this).parent();
      var this_track_data = track_info_loaded[url];
      if (this_track_data.commentable == 'true') {
        var relative = get_relative_mouse_position_element(e, $this);
        var pos = (this_track_data.duration/1000) * relative;
        set_comments_form(url, '', '', pos);
        var perc = Math.round(relative * 100);
        $this.find(".cursour_wave").css('left', perc + '%');
        $this.find(".cursour_wave").html("<img class='dragme' draggable='true' src='" + login_img + "'/>").fadeIn();
      }      
    } else {
      App.showNotification("info", "info",'Please login to post comments.');
    }
  });
};

/*Comment function*/



var scrub = function(node, xPos) {
  var $available = $(node).find('canvas');
  var $player = $('.big_player');
  var current_track=$player.data("track");

  var clicked_track=$(node).attr("data-track");

  var track=findElementByTrack($player.data("tracks"),clicked_track);
  console.log('Scrubbing Track Info ', track);
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
      /*J code*/
      $("#volumepercentage").html(getVolume(event.pageX));
      /*J code*/
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

/*
    $("#volume_slider").slider({
      orientation: "vertical",
      range: "min",
      min: 0,
      max: 100,
      value: 60,
      slide: function (event, ui) {
        $doc.trigger({type: 'scPlayer:onVolumeChange', volume: ui.value});
      }
    });
*/

    $("#volume_slider").ionRangeSlider({
      min: 0,
      max: 100,
      from: 50,
      hide_min_max: true,
      hide_from_to: true,
      onStart: function (data) {
      },
      onChange:function (data) {
        $doc.trigger({type: 'scPlayer:onVolumeChange', volume: data.from});
      },
      onFinish:function (data) {
      },
      onUpdate:function (data) {
      }
    });

  bind_events_commentbar();
  submit_comment_form();
  get_comments();
    //var $vol = $('#volume');
    //$vol.slider();
    
    if($.isFunction($.scPlayer.defaults.onDomReady)){
      $.scPlayer.defaults.onDomReady();
    }
  });

//})(jQuery);


