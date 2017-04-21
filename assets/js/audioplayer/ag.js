$.fn.audiogallery = function(o) {
        var defaults = {
            design_skin: 'skin-default'
            ,cueFirstMedia : 'on'
            ,autoplay: 'off'
            ,autoplayNext: 'on'
            ,design_menu_position: 'bottom'
            ,design_menu_state: 'open'
            ,design_menu_show_player_state_button: 'off'
            ,design_menu_width: 'default'
            ,design_menu_height: '200'
            ,design_menu_space: 'default'
            ,design_menuitem_width: 'default'
            ,design_menuitem_height: 'default'
            ,design_menuitem_space: 'default'
            ,disable_menu_navigation: 'off'
            ,disable_player_navigation: 'off'
            ,settings_ap: {}
            ,transition: 'direct' //fade or direct
            ,embedded: 'off'

        }
        o = $.extend(defaults, o);
        this.each(function() {
		//big 
            var cthis = $(this);
            var cchildren = cthis.children()
                ,cthisId = 'ag1'
                ;
			//song
			var sid=cthis.attr("data-id")
			var sthis=$(".s_"+sid);
			var schildren=sthis.children();
			//mini player
			var mthis=$(".minplayer");
			var mchildren=mthis.children();
          
			
            var currNr = -1 //===the current player that is playing
                ,lastCurrNr = 0
                ,tempNr = 0
                ;
            var busy = true;
            var i = 0;
            var ww
                , wh
                , tw
                , th
                ,n_maindim // the navmain main dimension for scrolling
                ,nc_maindim
                ,sw = 0//scrubbar width
                ,sh
                ,spos = 0 //== scrubbar prog pos
                ;
            var _sliderMain
                ,_sliderClipper
                ,_navMain
                ,_navClipper
                ,_cache
                ;
            var busy = false
                ,playing = false
                ,muted = false
                ,loaded=false
                ,first=true
                ;
            var time_total = 0
                ,time_curr=0
                ;
            var last_vol = 1
                ,last_vol_before_mute = 1
                ;
            var inter_check
                ,inter_checkReady
                ;
            var skin_minimal_canvasplay
                ,skin_minimal_canvaspause
                ;
            var is_flashplayer = false
                ;
            var data_source
                ;

            var aux_error = 20;//==erroring for the menu scroll

            var res_thumbh = false;

            var str_ie8 = '';

            var arr_menuitems = [];

            var str_alertBeforeRate = 'You need to comment or rate before downloading.';


            if(window.dzsap_settings && typeof( window.dzsap_settings.str_alertBeforeRate)!='undefined'){
                str_alertBeforeRate = window.dzsap_settings.str_alertBeforeRate;
            }

            init();
            function init(){




                if(o.design_menu_width=='default'){
                    o.design_menu_width = '100%';
                }
                if(o.design_menu_height=='default'){
                    o.design_menu_height = '200';
                }




                cthis.append('<div class="slider-main"><div class="slider-clipper"></div></div>');
                sthis.append('<div class="slider-main"><div class="slider-clipper"></div></div>');

                cthis.addClass('menu-position-'+ o.design_menu_position);
                sthis.addClass('menu-position-'+ o.design_menu_position);

                _sliderMain = cthis.find('.slider-main').eq(0);


                var auxlen = cthis.find('.items').children().length;

                // --- if there is a single audio player in the gallery - theres no point of a menu
                if(auxlen==0 || auxlen==1){
                    o.design_menu_position = 'none';
                    o.settings_ap.disable_player_navigation = 'on';
                }

                if(o.design_menu_position=='top'){
                    _sliderMain.before('<div class="nav-main"><div class="nav-clipper"></div></div>');
                }
                if(o.design_menu_position=='bottom'){
                    _sliderMain.after('<div class="nav-main"><div class="nav-clipper"></div></div>');
                }


                _sliderClipper = cthis.find('.slider-clipper').eq(0);
                _navMain = cthis.find('.nav-main').eq(0);
                _navClipper = cthis.find('.nav-clipper').eq(0);

                for(i=0;i<auxlen;i++){
                    arr_menuitems.push(cthis.find('.items').children().eq(0).find('.menu-description').html())
                    //cthis.find('.items').children().eq(0).find('.menu-description').remove();
                    _sliderClipper.append(cthis.find('.items').children().eq(0));
                }

                //console.info(arr_menuitems);

                for(i=0;i<arr_menuitems.length;i++){
                    _navClipper.append('<div class="menu-item">'+arr_menuitems[i]+'</div>')
                }

                if(o.disable_menu_navigation=='on'){
                    _navMain.hide();
                }

//                console.info(o.design_menu_height, o.design_menu_state);
                _navMain.css({
                    'height' : o.design_menu_height
                })

                if(is_ios() || is_android()){
                    _navMain.css({
                        'overflow':'auto'
                    })
                }

                if(o.design_menu_state=='closed'){

                    _navMain.css({
                        'height' : 0
                    })
                }





                if(cthis.css('opacity')==0){
                    cthis.animate({
                        'opacity' : 1
                    }, 1000);
                }

                $(window).bind('resize', handleResize);
                handleResize();
                setTimeout(handleResize, 1000);
                goto_item(tempNr);


                _navClipper.children().bind('click', click_menuitem);
                cthis.find('.download-after-rate').bind('click', click_downloadAfterRate);

                cthis.get(0).api_goto_next = goto_next;
                cthis.get(0).api_goto_prev = goto_prev;
                cthis.get(0).api_handle_end = handle_end;
                cthis.get(0).api_toggle_menu_state = toggle_menu_state;
                cthis.get(0).api_handleResize = handleResize;
                cthis.get(0).api_player_commentSubmitted = player_commentSubmitted;
                cthis.get(0).api_player_rateSubmitted = player_rateSubmitted;
			

                //console.info(cthis);
				sthis.get(0).api_goto_next = goto_next;
                sthis.get(0).api_goto_prev = goto_prev;
                sthis.get(0).api_handle_end = handle_end;
                sthis.get(0).api_toggle_menu_state = toggle_menu_state;
                sthis.get(0).api_handleResize = handleResize;
                sthis.get(0).api_player_commentSubmitted = player_commentSubmitted;
                sthis.get(0).api_player_rateSubmitted = player_rateSubmitted;				
				
				//mini
				mthis.get(0).api_goto_next = goto_next;
                mthis.get(0).api_goto_prev = goto_prev;
                mthis.get(0).api_handle_end = handle_end;
                
            }
            function click_downloadAfterRate(){
                var _t = $(this);


                if(_t.hasClass('active')==false){
                    alert(str_alertBeforeRate)
                    return false;
                }


            }
            function toggle_menu_state(){
                if(_navMain.height()==0){
                    _navMain.css({
                        'height' : o.design_menu_height
                    })
                }else{

                    _navMain.css({
                        'height' : 0
                    })
                }
                setTimeout(function(){
                    handleResize();
                }, 400); // -- animation delay
            }
            function handle_end(){
                goto_next();
            }

            function player_commentSubmitted(){
                _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');

            }
            function player_rateSubmitted(){
                _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');
            }

            function calculateDims(){
//                console.info('calculateDims');
                _sliderClipper.css('height', _sliderClipper.children().eq(currNr).height());
//                _navMain.show();
                n_maindim = _navMain.height();
                nc_maindim = _navClipper.outerHeight();

//                return;
//                console.info(nc_maindim, n_maindim)
                if(nc_maindim > n_maindim && n_maindim>0){
                    _navMain.unbind('mousemove', navMain_mousemove);
                    _navMain.bind('mousemove', navMain_mousemove);
                }else{
                    _navMain.unbind('mousemove', navMain_mousemove);
                }

                if(o.embedded=='on'){
//                    console.info(window.frameElement)
                    if(window.frameElement){
                        window.frameElement.height = cthis.height();
//                        console.info(window.frameElement.height, cthis.outerHeight())
                    }
                }
            }
            function navMain_mousemove(e){
                var _t = $(this);
                var mx = e.pageX - _t.offset().left;
                var my = e.pageY - _t.offset().top;

//                console.info(nc_maindim, n_maindim, nc_maindim <= n_maindim);
                if(nc_maindim <= n_maindim){
                    return;
                }

                n_maindim = _navMain.outerHeight();

                //console.log(mx);

                var vix = 0;
                var viy = 0;

                viy = (my / n_maindim) * -(nc_maindim - n_maindim+10 + aux_error*2) + aux_error;
                //console.log(viy);
                if(viy>0){
                    viy = 0;
                }
                if(viy < -(nc_maindim - n_maindim+10)){
                    viy = -(nc_maindim - n_maindim+10);
                }

                //console.log(viy, nc_maindim, n_maindim, (my / n_maindim))
                _navClipper.css({
                    'transform': 'translateY('+viy+'px)'
                });
            }
            function click_menuitem(e){
                var _t = $(this);
                var ind = _t.parent().children().index(_t);

                goto_item(ind);
            }

            function handleResize(){

                setTimeout(function(){
                    //console.info(_sliderClipper.children().eq(currNr), _sliderClipper.children().eq(currNr).height())
                    _sliderClipper.css('height', _sliderClipper.children().eq(currNr).height());
                },500);

                calculateDims();

            }

            function transition_end(){
                _sliderClipper.children().eq(lastCurrNr).hide();
                lastCurrNr = currNr;
                busy= false;
            }
            function transition_bg_end(){
                cthis.parent().children('.the-bg').eq(0).remove();
                busy= false;
            }
            function goto_prev(){
                tempNr = currNr;
                tempNr--;
                if(tempNr<0){
                    tempNr = _sliderClipper.children().length-1;
                }
                goto_item(tempNr);
            }
            function goto_next(){
                tempNr = currNr;
                tempNr++;
                if(tempNr>=_sliderClipper.children().length){
                    tempNr = 0;
                }
                goto_item(tempNr);
            }
            function goto_item(arg){

                if(busy==true){
                    return;
                }
                if(currNr==arg){
                    return;
                }

                _cache = _sliderClipper.children().eq(arg);

                if(currNr>-1){
                    if(typeof(_sliderClipper.children().eq(currNr).get(0))!='undefined'){
                        if(typeof(_sliderClipper.children().eq(currNr).get(0).fn_pause_media)!='undefined'){
                            _sliderClipper.children().eq(currNr).get(0).fn_pause_media();
                        }

                    }
                    if(o.transition=='fade'){
                        _sliderClipper.children().eq(currNr).css({
                            'position':'absolute'
                            ,'left' : 0
                            ,'top' : 0
                            ,'opacity' : 1
                        })
                        _sliderClipper.children().eq(currNr).animate({
                            'opacity' : 0
                        },{queue:false, complete: transition_end })
                    }
                    if(o.transition=='direct'){
                        transition_end();
                    }
                }


                //============ setting settings
                if(o.settings_ap.design_skin == 'sameasgallery'){
                    o.settings_ap.design_skin = o.design_skin;
                }

                //===if this is  the first audio
                if(currNr == -1 && o.autoplay=='on'){
                    o.settings_ap.autoplay = 'on';
                }

                //===if this is not the first audio
                if(currNr > -1 && o.autoplayNext=='on'){
                    o.settings_ap.autoplay = 'on';
                }
                o.settings_ap.disable_player_navigation = o.disable_player_navigation;
                o.settings_ap.parentgallery = cthis;

                o.settings_ap.design_menu_show_player_state_button = o.design_menu_show_player_state_button;
                o.settings_ap.cue = 'on';
                if(first==true){
                    if(o.cueFirstMedia=='off'){
                        o.settings_ap.cue = 'off';
                    }

                    first = false;
                }

                //============ setting settings END


                if(_cache.hasClass('audioplayer-tobe')){
                    _cache.audioplayer(o.settings_ap);
                }

                if(o.autoplayNext=='on'){
                    if(currNr>-1 && _cache.get(0) && _cache.get(0).api_play){
                        _cache.get(0).api_play();
                    }
                }



                if(o.transition=='fade'){
                    _cache.css({
                        'position':'absolute'
                        ,display:'block'
                        ,'left' : 0
                        ,'top' : 0
                        ,'opacity' : 0
                    })
                    _cache.animate({
                        'opacity' : 1
                    },{queue:false})
                }
                if(o.transition=='direct'){

                }


                if(_cache.attr("data-bgimage")!=undefined && cthis.parent().hasClass('ap-wrapper') && cthis.parent().children('.the-bg').length>0){
                    cthis.parent().children('.the-bg').eq(0).after('<div class="the-bg" style="background-image: url('+_cache.attr("data-bgimage")+');"></div>')
                    cthis.parent().children('.the-bg').eq(0).css({
                        'opacity':1
                    })


                    cthis.parent().children('.the-bg').eq(1).css({
                        'opacity':0
                    })
                    cthis.parent().children('.the-bg').eq(1).animate({
                        'opacity':1
                    },{queue:false, duration:1000, complete:transition_bg_end, step:function(){
                        busy=true;
                    } })
                    busy=true;
                }


                currNr = arg;
            }
        });
    }