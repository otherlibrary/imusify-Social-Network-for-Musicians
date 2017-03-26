    var ajax_popupdata = {};
    var sc_trackslist_current= '';
    var add_save_never_sell = false;
    var init_upload = false;
    var url_location_static = window.location.href;
    var hide_number_license = 1;
    var init_trackcover = false;    
    var croppicHeaderOptions;
    var croppicHeaderOptionstrack = [];
    var interval = 30000;
    var links=[];
    var queue=[];
    var temp={};    
    var root = document.location.hostname;
    var genre,subgenre,d_genre,d_mood,s_price,s_duration,loaded,response_genre,response_sec_genre,response_soundlike,response_album_list,mood_list,response_instruments_list,licence_types_list,sell_type_list,np_type_list,el_type_list,track_upload_type_list,lower_type_list,higher_type_list;
    var search_params={};
    var request_array = [];
    var r_flag;     
    var upload_queue=[];
    var main_q=[];
    var saved_q=[];
    var completed_q=[];
    var cancelled_q=[];
    var completed_f=[];
    var track_i_files={};
    var jqXHR=[];
    var ref_q=[];
    var loaded = false;
    var pixels,exist_html = "";
    var pl_r_no,pl_pic_upd =false;
    var share_url,share_desc,share_title;
    var cur_song_space = 0;
    var buy_items = [];
    var order_random_id;
    var $toast;
    //fb_share = "https://www.facebook.com/sharer/sharer.php?u=";  
    fb_share = "https://www.facebook.com/plugins/share_button.php?layout=button_count&mobile_iframe=false&width=68&height=20&appId&href=";
    twit_share = "https://twitter.com/intent/tweet?url=";
    google_share = "https://plus.google.com/share?url=";
    jQuery.browser = {};
    notificationTimestamp = 0;
    notification_unread_count = 0;
    var window_height = $(window).height();
    var window_outer_height = $(window).outerHeight(true);
    var window_width = $(window).width();
    var window_outer_width = $(window).outerWidth(true);        
        
        

    /*Hint share tips*/
    var upload_similar_steps = [
    {
        'click .slide-btn' : 'Click the button to view similar track'
    }  
    ];    
    /*Hint share tips*/

    $.fn.TextAreaExpander = function(minHeight, maxHeight) {
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
        else{
            var hCheck = !(jQuery.browser.msie || $.browser.opera);    
        }       
        
        function ResizeTextarea(e) {
            e = e.target || e;
            
            var vlen = e.value.length,
            ewidth = e.offsetWidth;
            if (vlen != e.valLength || ewidth != e.boxWidth) {
                if (hCheck && (vlen < e.valLength || ewidth != e.boxWidth)) e.style.height = "0px";
                var h = Math.max(e.expandMin, Math.min(e.scrollHeight, e.expandMax));
                e.style.overflow = (e.scrollHeight > h ? "auto" : "hidden");
                e.style.height = h + "px";
                e.valLength = vlen;
                e.boxWidth = ewidth;
            }
            return true;
        };
        this.each(function() {
            if (this.nodeName.toLowerCase() != "textarea") 
                return;
            
            var p = this.className.match(/expand(\d+)\-*(\d+)*/i);
            this.expandMin = minHeight || (p ? parseInt('0' + p[1], 10) : 0);
            this.expandMax = maxHeight || (p ? parseInt('0' + p[2], 10) : 99999);
            ResizeTextarea(this);
            
            if (!this.Initialized) {
                this.Initialized = true;
                $(this).css("padding-top", 0).css("padding-bottom", 0);
                $(this).bind("keyup", ResizeTextarea).bind("focus", ResizeTextarea);
            }
        });
        return this;
    };
    
    $.fn.linkPreview = function (options) {
        var defaults = {
            placeholder: "What is on your mind?",
            imageQuantity: -1
            
        };
        var opts = jQuery.extend(defaults, options);
        function trim(str) {
            if(typeof str != "undefined" )
                return str.replace(/^\s+|\s+$/g, "");
            else
                return str;
        }
        var selector = $(this).selector;
        var $elm = $(selector);
        selector = selector.substr(1);
        $tmp2 = {};
        $tmp2["selector"] =  selector; 
        $tmp2["opts"] =  opts;

       /* if(opts.crawl_feed_row != "")
        {
            $.template("u_tmpl_container",opts.crawl_feed_row);
            $.tmpl('u_tmpl_container',$tmp2).appendTo($elm); 
            
        }*/
        

        $.when(my.renderTemplate("crawl_feed_row")).done(function(){
            //console.log("1");
            //console.log('Crawl feed row: ',my.config.loaded_template);
            $.template("u_tmpl_container",my.config.loaded_template['crawl_feed_row']);
            $.tmpl('u_tmpl_container',$tmp2).appendTo($elm);  
        });
        var urlRegex = /(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i;
        var block = false;
        var blockTitle = false;
        var blockDescription = false;
        var contentWidth = 335;
        var content = "";
        var leftSideContent = "";
        var photoNumber = 0;
        var firstPosted = false;
        var firstPosting = false;
        var nT = false;
        var imageId = "";
        var imageIdArray = "";
        var pTP = "";
        var pDP = "";
        var allowPosting = false;
        var isCrawling = false;
        var defaultTitle = "Enter a title";
        var defaultDescription = "Enter a description";
        var textText = "";
        $('#text_' + selector).focus(function () {
            if (trim($('#text_' + selector).val()) === textText) {
                $(this).val('');
                $(this).css({
                    'color': 'black'
                });
            }
        }).blur(function () {
            if (trim($('#text_' + selector).val()) === "") {
                $(this).val(textText);
                $(this).css({
                    'color': 'grey'
                });
            }
        });
        function resetPreview() {
            $('#previewPreviousImg_' + selector).removeClass('buttonLeftActive');
            $('#previewPreviousImg_' + selector).addClass('buttonLeftDeactive');
            $('#previewNextImg_' + selector).removeClass('buttonRightActive');
            $('#previewNextImg_' + selector).addClass('buttonRightDeactive');
            $('#previewButtons_' + selector).show();
            contentWidth = 335;
            photoNumber = 0;
            $('#previewContent_' + selector).css({
                'width': '335px'
            });
            $('#noThumb_' + selector).show();
            $('#nT_' + selector).show();
            $('#noThumb_' + selector).removeAttr('checked');
            images = "";
        }
        function addfeedata(key,value){
            var feed=$elm.data("feeddata");
            if(feed==null){
                feed={};
            }
            feed[key]=value;
            $elm.data("feeddata",feed);
        }
        function renderPreviewBlock(answer){
            $('#previewLoading_' + selector).html("");
            $('#preview_' + selector).show();
            $('#previewTitle_' + selector).html("<span id='previewSpanTitle_" + selector + "' class='previewSpanTitle' >" + answer.title + "</span><input type='text' value='" + answer.title + "' id='previewInputTitle_" + selector + "' class='previewInputTitle inputPreview' style='display: none;'/>");
            $('#text_' + selector).css({
                "border": "1px solid #b3b3b3",
                "border-bottom": "1px dashed #b3b3b3"
            });
            $('#previewUrl_' + selector).html(answer.url);
            $('#previewDescription_' + selector).html("<span id='previewSpanDescription_" + selector + "' class='previewSpanDescription' >" + answer.description + "</span><textarea id='previewInputDescription_" + selector + "' class='previewInputDescription' style='display: none;' class='inputPreview' >" + answer.description + "</textarea>");
            var title = "<a href='" + answer.pageUrl + "' target='_blank'>" + $('#previewTitle_' + selector).html() + "</a>";
            var url = "<a href='http://" + answer.canonicalUrl + "' target='_blank'>" + answer.canonicalUrl + "</a>";
            var fancyUrl = answer.canonicalUrl;
            var hrefUrl = answer.url;
            var description = $('#previewDescription_' + selector).html();
            var video = answer.video;
            var videoIframe = answer.videoIframe;
            var feedType = answer.feedtype;
            addfeedata("title",title);
            addfeedata("url",url);
            addfeedata("fancyUrl",fancyUrl);
            addfeedata("hrefUrl",hrefUrl);
            addfeedata("description",description);
            addfeedata("video",video);
            addfeedata("videoIframe",videoIframe);
            addfeedata("feedType",feedType);
        }
        function renderFeedImagePreview(answer,flagclass){
            try {
                images = (answer.images).split("|");
                addfeedata("images",images);                
            } catch (err) {
                $('#previewImages_' + selector).hide();
                $('#previewButtons_' + selector).hide();
            }
            images.length = parseInt(images.length);
            if(images.length > 1){
                $('#previewImages_' + selector).show();
                $('#previewButtons_' + selector).show();
            }
            var appendImage = "";
            for (i = 0; i < images.length; i++) {
                if (i === 0)
                    appendImage += "<img class='"+ flagclass +"' id='imagePreview_" + selector + "_" + i + "' src='" + images[i] + "' style='width: 130px; height: auto' ></img>";
                else
                    appendImage += "<img class='"+ flagclass +"' id='imagePreview_" + selector + "_" + i + "' src='" + images[i] + "' style='width: 130px; height: auto; display: none' ></img>";
            }
            $('#previewImage_' + selector).html("<a href='" + answer.pageUrl + "' target='_blank'>" + appendImage + "</a><div id='whiteImage' style='width: 130px; color: transparent; display:none;'>...</div>");
            $('#photoNumbers_' + selector).html("1 of " + images.length);
            if (images.length > 1) {
                renderFeedImages(selector,answer,images);
            } else if (images.length <= 1) {
                renderFeedImage(selector,answer,images);
            }
        }
        function renderFeedImage(answer,images){
            $('#closePreview_' + selector).css({
                "margin-right": "-206px"
            });
            $('#previewTitle_' + selector).css({
                "width": "495px"
            });
            $('#previewDescription_' + selector).css({
                "width": "495px"
            });
            $('#previewInputDescription_' + selector).css({
                "width": "495px"
            });
            contentWidth = 495;
            $('#previewButtons_' + selector).hide();
            $('#noThumb_' + selector).hide();
            $('#nT_' + selector).hide();
        }
        function renderFeedImages(answer,images){
            $('#previewNextImg_' + selector).removeClass('buttonRightDeactive');
            $('#previewNextImg_' + selector).addClass('buttonRightActive');
            if (firstPosted === false) {
                firstPosted = true;
                $('#previewPreviousImg_' + selector).unbind('click').click(
                    function(e){
                        e.stopPropagation();
                        if(images.length > 1)
                        {
                            photoNumber = parseInt($('#photoNumber_' + selector).val());
                            $('#imagePreview_' + selector + '_' + photoNumber).css({
                                'display': 'none'
                            });
                            photoNumber -= 1;
                            if (photoNumber === -1)
                                photoNumber = 0;
                            $('#previewNextImg_' + selector).removeClass('buttonRightDeactive');
                            $('#previewNextImg_' + selector).addClass('buttonRightActive');
                            if (photoNumber === 0) {
                                photoNumber = 0;
                                $('#previewPreviousImg_' + selector).removeClass('buttonLeftActive');
                                $('#previewPreviousImg_' + selector).addClass('buttonLeftDeactive');
                            }
                            $('#imagePreview_' + selector + '_' + photoNumber).css({
                                'display': 'block'
                            });
                            $('#photoNumber_' + selector).val(photoNumber);
                            $('#photoNumbers_' + selector).html(parseInt(photoNumber + 1) + " of " + images.length);
                        }
                    });
    $('#previewNextImg_'+selector).unbind('click').click(function(e){
        e.stopPropagation();
        if (images.length > 1) {
            photoNumber = parseInt($('#photoNumber_' + selector).val());
            $('#imagePreview_' + selector + '_' + photoNumber).css({'display': 'none'});
            photoNumber += 1;
            if (photoNumber === images.length)
                photoNumber = images.length - 1;
            $('#previewPreviousImg_' + selector).removeClass('buttonLeftDeactive');
            $('#previewPreviousImg_' + selector).addClass('buttonLeftActive');
            if (photoNumber === images.length - 1) {
                photoNumber = images.length - 1;
                $('#previewNextImg_' + selector).removeClass('buttonRightActive');
                $('#previewNextImg_' + selector).addClass('buttonRightDeactive');
            }
            $('#imagePreview_' + selector + '_' + photoNumber).css({'display': 'block' });
            $('#photoNumber_' + selector).val(photoNumber);
            $('#photoNumbers_' + selector).html(parseInt(photoNumber + 1) + " of " + images.length);
        }
    });
}
}
function renderFeedEvents(answer){
    $('#nT_' + selector).unbind('click').click(function (e) {
        e.stopPropagation();
        noThumbAction($('#noThumb_' + selector), 'parent');
    });
    $('#noThumb_' + selector).unbind('click').click(function (e) {
        e.stopPropagation();
        noThumbAction($(this), 'input');
    });
    $("body").on('click','#previewSpanTitle_' + selector,function (e) {
        e.stopPropagation();
        if (blockTitle === false) {
            blockTitle = true;
            $('#previewSpanTitle_' + selector).hide();
            $('#previewInputTitle_' + selector).show();
            $('#previewInputTitle_' + selector).val($('#previewInputTitle_' + selector).val());
            $('#previewInputTitle_' + selector).focus().select();
        }
    });
    $("body").on('blur','#previewInputTitle_' + selector,function () {
        blockTitle = false;
        $('#previewSpanTitle_' + selector).html($('#previewInputTitle_' + selector).val());
        $('#previewSpanTitle_' + selector).show();
        $('#previewInputTitle_' + selector).hide();
    });
    $('body').on('keypress','#previewInputTitle_' + selector,function (e) {
        if (e.which === 13) {
            blockTitle = false;
            $('#previewSpanTitle_' + selector).html($('#previewInputTitle_' + selector).val());
            $('#previewSpanTitle_' + selector).show();
            $('#previewInputTitle_' + selector).hide();
        }
    });
    $('body').off('click','#previewSpanDescription_' + selector);
    $('body').on('click','#previewSpanDescription_' + selector,function (e) {
        e.stopPropagation();
        if (blockDescription === false) {
            blockDescription = true;
            $('#previewSpanDescription_' + selector).hide();
            $('#previewInputDescription_' + selector).show();
            $('#previewInputDescription_' + selector).val($('#previewInputDescription_' + selector).val());
            $('#previewInputDescription_' + selector).focus().select();
        }
    });
    $("body").on('blur','#previewInputDescription_' + selector,function () {
        blockDescription = false;
        $('#previewSpanDescription_' + selector).html($('#previewInputDescription_' + selector).val());
        $('#previewSpanDescription_' + selector).show();
        $('#previewInputDescription_' + selector).hide();
    });
    $("body").on('keypress','#previewInputDescription_' + selector,function (e) {
        if (e.which === 13) {
            blockDescription = false;
            $('#previewSpanDescription_' + selector).html($('#previewInputDescription_' + selector).val());
            $('#previewSpanDescription_' + selector).show();
            $('#previewInputDescription_' + selector).hide();
        }
    });
    $('#previewSpanTitle_' + selector).mouseover(function () {
        $('#previewSpanTitle_' + selector).css({
            "background-color": "#ff9"
        });
    });
    $('#previewSpanTitle_' + selector).mouseout(function () {
        $('#previewSpanTitle_' + selector).css({
            "background-color": "transparent"
        });
    });
    $('#previewSpanDescription_' + selector).mouseover(function () {
        $('#previewSpanDescription_' + selector).css({
            "background-color": "#ff9"
        });
    });
    $('#previewSpanDescription_' + selector).mouseout(function () {
        $('#previewSpanDescription_' + selector).css({
            "background-color": "transparent"
        });
    });
    $("body").off('click','#closePreview_' + selector);
    $("body").on('click','#closePreview_' + selector,function (e) {
        e.stopPropagation();
        block = false;
        addfeedata("hrefUrl",'');
        addfeedata("fancyUrl",'');
        addfeedata("video",'');
        addfeedata("images",'');
        $('#preview_' + selector).fadeOut("fast", function () {
            $('#text_' + selector).css({
                "border": "1px solid #b3b3b3",
                "border-bottom": "1px solid #e6e6e6"
            });
            $('#previewImage_' + selector).html("");
            $('#previewTitle_' + selector).html("");
            $('#previewUrl_' + selector).html("");
            $('#previewDescription_' + selector).html("");
        });
    });
}
function renderfeedTemplate(answer,flagclass){
    resetPreview();
    if(typeof flagclass != "undefined")
        flagclass = "feed_image";
    else
        flagclass = "";
    if(answer.feedtype=="text"){
        addfeedata("title",answer.title)
        addfeedata("feedType",answer.feedtype)
    }
    else{
        renderPreviewBlock(answer);
        renderFeedImagePreview(answer,flagclass);
        renderFeedEvents(answer);
    }
}
function noThumbAction(inputCheckbox, src) {
    var value = src === 'parent' ? !inputCheckbox.prop("checked") : inputCheckbox.prop("checked");
    inputCheckbox.prop('checked', value);
    $('#imagePreview_' + selector + '_' + photoNumber).css({
        'display': !value ? 'block' : 'none'
    });
    $('#whiteImage_' + selector).css({
        'display': !value ? 'none' : 'block'
    });
    $('#previewContent_' + selector).css({
        'width': !value ? '335px' : '500px'
    });
    if (value === true) {
        $('#previewButtons_' + selector).hide();
    } else {
        $('#previewButtons_' + selector).show();
    }
}
function iframenize(obj) {
    var oldId = obj.prop("id");
    var currentId = oldId.substring(3);
    pTP = "pTP" + currentId;
    pDP = "pDP" + currentId;
    oldId = "#" + oldId;
    currentId = "#" + currentId;
    $(oldId).css({
        'display': 'none'
    });
    $(currentId).css({
        'display': 'block'
    });
    $('#' + pTP).css({
        'width': '495px'
    });
    $('#' + pDP).css({
        'width': '495px'
    });
}
var first_time_crawl = false;
var crawlText = function ($imgupload,e,input,extensions) {
    allowPosting = true;
    block = false;
    text = " " + $('#text_' + selector).val();
    if(typeof $imgupload != "undefined" && $imgupload == true)
    {
        if (input.files && input.files[0])
        {
            t = input.files[0].name;                                     
            if(App.check_file(t,extensions)==true)
            {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var answer = {};
                    answer.title = "Title";
                    answer.description = "Description";
                    answer.images = e.target.result;
                    answer.feedtype = "internal_image";
                    renderfeedTemplate(answer,"true");
                }
                reader.readAsDataURL(input.files[0]);
            }
            else
            {
                App.ShowNotification("info","Info","Please upload a valid  image format.");
                return false;
            }
        }
    }
    if(trim(text) !== ""){
        video = "no";
        videoPlay = "";
        if (block === false && urlRegex.test(text))
        {
            block = true;
            $('#preview_' + selector).hide();
            $('#previewButtons_' + selector).hide();
            $('#previewLoading_' + selector).html("<img src='"+App.config.siteUrl+"assets/images/loader_posts.gif' />");
            $('#photoNumber_' + selector).val(0);
            console.log('Crawl link ', firstPosting);
            if(firstPosting) {
//                if (first_time_crawl) setTimeout(function(){
//                    first_time_crawl = false;
//                    firstPosting = false;
//                    $('#postPreviewButton_feed_post').click();                    
//                }, 300);
                
                first_time_crawl = true;
                //load post                
            }
            allowPosting = false;
            isCrawling = true;
            $.post(App.config.siteUrl+'api/linkcrawler',{text: text,crawlurl:text,imagequantity: opts.imageQuantity},function(answer){
                if(answer.url === null){answer.url = "";}
                if(answer.pageUrl === null){answer.pageUrl = "";}
                if(answer.title === null || answer.title === ""){answer.title = defaultTitle;}
                if(answer.description === null || answer.description === ""){answer.description = defaultDescription;}
                if(answer.canonicalUrl === null){answer.canonicalUrl = "";}
                if(answer.images === null){answer.images = "";}
                if(answer.video === null){answer.video = "";}
                if(answer.videoIframe === null){answer.videoIframe = "";}
                if(answer.feedtype != null){feedType = answer.feedtype;}
                renderfeedTemplate(answer);
                if(firstPosting === false) {firstPosting = true;}
                allowPosting = true;
                isCrawling = false;},"json");
        }   
        else{
            var answer = {};
            answer.title = "Title";
            answer.feedtype = "text";
            renderfeedTemplate(answer);
        }
    }
};
$('body').on('paste','#text_' + selector,function(){
    setTimeout(function () {
        crawlText();
    }, 100);            
});
$('body').on('keyup','#text_' + selector,function(e){
    if ((e.which === 13 || e.which === 32 || e.which === 17)) {
        crawlText();
    }       
});
$('body').on('blur','#text_' + selector,function(e){
    crawlText();       
});
$("body").on('click','.leave-reply',function (e) {
    //show reply form for Post a reply
    var id = $(this).attr("data-id");
    var feedlogId = $(this).attr("data-feedlogId");
    var profileImg = $(this).attr("data-profileimg");
    var username = $(this).attr("data-username");
    var parentId = $(this).attr("data-parentid");
    if(typeof parentId != "undefined" && parentId > 0)
        $elem = $("#feed_comment_"+id);
    else 
        $elem = $("#feed_com_cont_"+id);
    if($elem.find("#comment_new_row").length == 0)
    {
        $tmpc = {};
        $tmpc["profileImage"] = profileImg;
        $tmpc["username"] = username;
        $tmpc["id"] = id;
        $tmpc["feedlogId"] = feedlogId;
        $tmpc["parentId"] = parentId;
        
        $.template("u_tmpl_container",App.config.loaded_template['feed_comment_row']);
        if(typeof parentId != "undefined" && parentId > 0)
        {
            $.tmpl('u_tmpl_container',$tmpc).appendTo("#feed_comment_"+id);  
            $("#feed_comment_"+id).find("#comment").focus();
        } 
        else{
            $.tmpl('u_tmpl_container',$tmpc).appendTo("#feed_com_cont_"+id);  
            $("#feed_com_cont_"+id).find("#comment").focus();
        } 

    }           
});   
    jQuery("body").on('click', "#following_image_upload",function(e) { 
        $("#fileupload_feed").click();    
    });
    jQuery("body").on('change', "#fileupload_feed",function(e) { 
        crawlText(true,e,this,'jpg,png,jpeg,bmp');            
    });
    $("body").on('click','.videoPostPlay',function (e) {
        e.stopPropagation();
        $elem = $(this).parent().find("iframe");
        if(!$elem.hasClass("displayed"))
        {
            $elem.addClass("displayed");
            $elem.css("display","block");
        }else{
            $elem.removeClass("displayed");
            $elem.css("display","none");
        }                       
    });
    $("body").off('click','#postPreviewButton_'+ selector);
    //$("body").on('click','#postPreviewButton_' + selector,function (e) {            
    $("body").on('click','#postPreviewButton_feed_post',function (e) {       
        var selector = 'feed_post';
        console.log('Clicked Add Post on Folowing page', selector);
        e.stopPropagation();  
        var $img ='';          
        imageId = "";
        pTP = "";
        pDP = "";
        text = " " + $('#text_' + selector).val();
        title = $('#previewTitle_' + selector).html();
        description = $('#previewDescription_' + selector).html();
        if($('#imagePreview_' + selector + '_' + photoNumber).hasClass("feed_image"))
        {
            var tt = $('#imagePreview_' + selector + '_' + photoNumber).prop("src").split(',');
            var uu1 = tt[0];
            var uu2 = tt[1];      
            imgType = uu1;
            imgUrl = uu2;   
            $img = "";                         
        }
        else
        {
            $img = $('#imagePreview_' + selector + '_' + photoNumber).prop("src");
            imgType = "";
            imgUrl = "";                           
        } 
        var image= $('#noThumb_' + selector).prop("checked") ? '' :$img ;
        addfeedata("title",title)
        addfeedata("text",text)
        addfeedata("image",image)
        addfeedata("imgUrl",imgUrl)
        addfeedata("imgType",imgType)
        addfeedata("description",description)
        var data=$elm.data("feeddata");
        $.post(App.config.siteUrl+'api/feed_save', {
            data:data
        }, function (response) {
            var id = !isNaN(response) ? response : Math.floor((Math.random() * 1000000000) + 1);
            if(response.feed_detail.audiofeed == true)
            {
                $template_name = "external_link";
            }
            else if(response.feed_detail.textfeed == true)
            {
                $template_name = "textfeed";
            }
            else if(response.feed_detail.videofeed == true)
            {
                $template_name = "external_link";
                console.log('Video post ');                                
            }
            else if(response.feed_detail.imagefeed == true)
            {
                $template_name = "external_link"; 
            }
            else if(response.feed_detail.linkfeed == true)
            {
                $template_name = "external_link"; 
            }
            
            setTimeout(function(){
                //load all available videos and audios
                //hide all Play buttons
                if($(".videoPostPlay:visible").length > 0 ){
                    $(".videoPostPlay:visible").click();
                    $(".videoPostPlay:visible").hide();
                }                        
            }, 500);
            
            $.template("u_tmpl_container",App.config.loaded_template[$template_name]);
            $('#preview_' + selector).fadeOut("fast", function () {
                if (response.url != null && response.url.indexOf("vine.co") != -1) {
                    setTimeout(function () {
                        $('#' + imageId).hide();
                    }, 50);
                }
                $('#text_' + selector).css({
                    "border": "1px solid #b3b3b3",
                    "border-bottom": "1px solid #e6e6e6"
                });
                $('#text_' + selector).val("");
                $('#previewImage_' + selector).html("");
                $('#previewTitle_' + selector).html("");
                $('#previewUrl_' + selector).html("");
                $('#previewDescription_' + selector).html("");
                $(".imgIframe").unbind('click').click(function (e) {
                    e.stopPropagation();
                    iframenize($(this));
                });
                $("body").on('click', '#videoPostPlay' + imageId,function (e) {
                    e.stopPropagation();
                    iframenize($(this).parent().find(".imgIframe"));                                        
                    //Register listener for Video playback
//                    setTimeout(function(){
//                        //load all available videos and audios
//                        //hide all Play buttons
//                        if($(".videoPostPlay").length > 0 ){
//                            $(".videoPostPlay").click();
//                            $(".videoPostPlay").hide();
//                        }                        
//                    }, 500);
                });
            });

    if($(".no_rec_found").size() > 0)
    {
        $(".no_rec_found").remove();
    }

    $.tmpl('u_tmpl_container',response.feed_detail).prependTo('#users_feed');   

});
    text = "";     
});

    function replaceAll(find, replace, str) {
        return str.replace(new RegExp(find, 'g'), replace);
    }
};
$.fn.isLoaded = function(message) {
    var $this = $(this);
    if($this.height() > 0 && $this.width() > 0){
        return true;
    }
    return false;
};

$.fn.SmoothAnchors = function() {
    function scrollBodyTo(destination, hash) {
        var scrollmem = $(document).scrollTop();
        window.location.hash = hash;
        $(document).scrollTop(scrollmem);
        $("html,body").animate({
            scrollTop: destination
        }, 1200);
    }
    if (typeof $().on == "function") {
        $(document).on('click', 'a[href^="#"]', function() {
            var href = $(this).attr("href");
            if ($(href).length == 0) {
                var nameSelector = "[name=" + href.replace("#", "") + "]";
                if (href == "#") {
                    scrollBodyTo(0, href);
                }
                else if ($(nameSelector).length != 0) {
                    scrollBodyTo($(nameSelector).offset().top, href);
                }
                else {
                    window.location = href;
                }
            }
            else {
                scrollBodyTo($(href).offset().top, href);
            }
            return false;
        });
    }
    else {
        $('a[href^="#"]').click(function() {
            var href = $(this).attr("href");
            if ($(href).length == 0) {
                var nameSelector = "[name=" + href.replace("#", "") + "]";
                if (href == "#") {
                    scrollBodyTo(0, href);
                }
                else if ($(nameSelector).length != 0) {
                    scrollBodyTo($(nameSelector).offset().top, href);
                }
                else {
                    window.location = href;
                }
            }
            else {
                scrollBodyTo($(href).offset().top, href);
            }
            return false;
        });
    }
};
$.fn.progressInitialize = function() {
    return this.each(function() {
        var button = $(this),
        progress = 0;
        var options = $.extend({
            type: 'background-horizontal',
            loading: 'Loading..',
            finished: 'Done!'
        }, button.data());
        button.attr({
            'data-loading': options.loading,
            'data-finished': options.finished
        });
        var bar = $('<span class="tz-bar ' + options.type + '">').appendTo(button);
        button.on('progress', function(e, val, absolute, finish) {
            if (!button.hasClass('in-progress')) {
                bar.show();
                progress = 0;
                button.removeClass('finished').addClass('in-progress')
            }
            if (absolute) {
                progress = val;
            } else {
                progress += val;
            }
            if (progress >= 100) {
                progress = 100;
            }
            if (finish) {
                button.removeClass('in-progress').addClass('finished');
                bar.delay(500).fadeOut(function() {
                    button.trigger('progress-finish');
                    setProgress(0);
                });
            }
            setProgress(progress);
        });
        function setProgress(percentage) {
            bar.filter('.background-horizontal,.background-bar').width(percentage + '%');
            bar.filter('.background-vertical').height(percentage + '%');
        }
    });
};
$.fn.progressFinish = function() {
    return this.first().progressSet(100);
};
$.fn.progressReset = function() {
    return this.first().removeClass("finished");
};
$.fn.progressTimed = function(seconds, cb) {
    var button = this.first(),
    bar = button.find('.tz-bar');
    if (button.is('.in-progress')) {
        return this;
    }
    bar.css('transition', seconds + 's linear');
    button.progressSet(99);
    window.setTimeout(function() {
        bar.css('transition', '');
        button.progressFinish();
        setTimeout(function(){
            button.progressReset();
        },100)
        if ($.isFunction(cb)) {
            cb();
        }
    }, seconds * 1000);
};
$.fn.progressSet = function(val) {
    val = val || 100;
    var finish = false;
    if (val >= 100) {
        finish = true;
    }
    return this.first().trigger('progress', [val, true, finish]);
};
function test($this){
    console.log($this);
}   
var App={
    config:{
        siteName:"",
        loggedIn:false,
        notification:null,
        changedloggedin:false,
        siteUrl:'http://'+root+'/imusify/',
        AssetUrl:'http://'+root+'/imusify/assets/',
        ViewUrl:'http://'+root+'/imusify/assets/views/',         
        history_redirect_url:document.URL,
        current_tm:"home",
        templates:  {
            "sign_in":"sign_in/sign_in",
            "Reset":"sign_in/reset",
            "sign_up":"sign_up/sign_up",
            "sign_up_mail":"sign_up/sign_up_mail",
            "sign_up_middlepage":"sign_up/sign_up_middlepage",      
            "home":"main",
            "headlines":"headlines",
            "news_row":"news_row",
            "left_panel":"left_panel",
            "right_panel":"right_panel",
            "main":"main",
            "setup":"setup/role",
            "profile":"profile/profile",
            "edit_profile":"profile/edit_profile",
            "edit_cover_img" : "profile/edit_cover_image",
            "artist_profile":"profile/artist_profile",              
            "profile_playset":"profile/playset_row",
            "profile_recent_listen":"profile/recent_listen_row",
            "profile_artist_popular_songs":"profile/artist_popular_row",
            "profile_follow_row" : "profile/follow-row",
            "profile_following_row" : "profile/following-row",
            "profile_music_header" : "profile/profile_music_header",
            "profile_tab_render" : "profile/profile_tab_render",

            "upload":"upload/upload",
            "uploadtabrender": "upload/tab_render",
            "albumListrow": "upload/album_list",
            "upload_song_row":"upload/upload_song_row",
            "uploaded_song_row":"upload/uploaded_song_row",
            "album_track_list_row":"upload/album_track_list_row",   
            "album":"upload/album",   
            "albumedit":"upload/album_edit",  
            "trackedit":"upload/trackedit",  
            "following":"following/following",
            "following_tab_render":"following/tab_render",
            "member-list":"following/member-list",
            "follow-member":"following/follow-member",
            "article":"following/article",
            "external_link":"following/external_link",
            "textfeed":"following/textfeed",
            "follow_comment_row":"following/comment_row",
            "feed_comment_row":"following/new_comment_row",
            "new_feed_row":"following/new_feed_row",
            "feed_follow_suggestion_row":"following/follow-member",
            "crawl_feed_row":"following/crawl_feed_row",
            "playlistContent":"playlist/playlist",
            "playlist_ls":"playlist/playlist_list",
            "browse_recommended" : "browse/browse_recommended",
            "browse_music_header" : "browse/browse_music_header",
            "browse_pop_new_songs" : "general/browse_rec_music_row",
            "browse_popartist" : "browse/browse_rec_pop_artist_row",
            "explore" : "explore/explore",
            "addplaylist_popup" : "general/playlist_row",
            "msg_message" : "message/message",
            "msg_member_list" : "message/member_list",
            "msg_member_row" : "message/member_row",
            "msg_chat_box" : "message/chat_box",
            "loadmore" : "general/loadmore",
            "box3":"general/box_3",
            "comment_row":"general/comment_row",
            "tab_render" : "general/tab_render",
            "notification_row" : "notification_row",
            "songs_row_four_list" : "general/songs_row_four_list",
            "songs_row_four_pllist" : "general/songs_row_four_pllist",
            "playlist_info":"playlist/playlist_info",
            "songs_header_four" : "general/songs_header_four",
            "user_suggestion_list" : "message/user_suggestion_list",
            "messages_list" : "message/mesages_list",
            "new_message_main" : "message/new_message",
            "message_row" : "message_row",
            "browse_pop_artist" : "browse/popular_user_row",
            "home_music_row" : "home/music_row",
            "home_tab_render" : "home/tab_render",
            "home_article_row" : "home/article_row",            
            "trackdetail" : "trackdetail/trackdetail",
            "similar_music_row" : "trackdetail/similar_music_row",
            "search_results" : "search/search_results",
            "search_res_artist_row" : "search/artist_row",
            "search_res_pl_row" : "search/playlist_row",
            "search_res_track_row" : "search/track_row",
            "exploretags" : "explore/tags",
            "content" : "content/content",
            "newcomment":"general/new_comment",
            "liked":"liked/liked",  
            "liked_song_row":"liked/liked_uploaded_song_row",               
            "liked_track_list_row":"liked/liked_track_list_row",
            "invite":"invite/invite",
            "membership":"membership/membership",
            "article_detail":"article/article",
            "article_detail_row":"article/similar_artical_row",
            "about":"about/about",
            "team_row":"about/team_row",
            "industry_professional_row":"about/industry_professional_row",            
            "account_main":"account/main",                
            "account_stripe_connect":"account/stripe_connect",
            "stripe_connected":"account/stripe_connected",
            "account_change_password":"account/change_password",
            "buynow":"trackdetail/buynow",
            "buynow_licence_row":"trackdetail/buynow_licence_row",
            "buy_list_row" : "trackdetail/buynow_licence_row",
            "biography" : "trackdetail/biography",
            "notificationAllRow":"notifications/notification_row",
            "notificationall":"notifications/notifications"
        },
        stack:[],
        loaded_template:{}  
    },
    req:{},
    data:{},
    _data:null,
    init:function(config){
        my = this;
        //console.log(my);
        $.extend(my.config,config);
        $("html").SmoothAnchors();
        my.addScroll();
        window.addEventListener('popstate', function(event) {
            var e=event.state;
            if(e != null)
                my.routingAjax(e.u,e.d,e.ca,e.sc,true);
        });
        my.preloadTemplate();
        /*  my.initGenerateWaveform();*/
        my.initScrollToTop();           
        my.initPlayerQueue();
        my.initPlaysetMinHeight();

        my.initUploadDetails();
        my.initSearchCondition();           
        my.initPopup();
        my.initSignUp();
        my.initLogin();         
        my.initResetPassword();         
        my.initFollowing();
        my.initEditProfile();
        my.initHome();
        my.initContent();
        my.initTrackDetail();
        my.initProfile();
        my.initMessage();
        my.initUpload();
        my.initBrowse();
        my.initPlaylist();
        my.initExplore();
        my.initSubmitRoles();
        /*my.initMasonary();  */
        my.initSimplePopup();
        my.initSocialLinkShare();
        my.initLikedSongs();
        my.initInviteFriends();
        my.initMembership();
        my.initGiftcouponapply();
        my.initFollowingfeed();
        my.initArticledetail();
        my.initAbout();
        my.initGeneral();
        my.initAccount();
        my.initStipeconnect();
        /*my.initNotificationRead();*/
        //andy turn off to development
        //my.initNotificationComet();
        
        my.initMessageCompose();
        my.initNotificationAll();
    },
    preloadTemplate:function(){
       $.each(my.config.templates, function(key, val){  
        my.renderTemplate(key);
    });
   },

   initMessageCompose:function(){
    /*Setting sidebar height*/
    var window_height = $(window).height();
    var footer_height = $("#trans-bg").height();
    var footer_player_height = $(".footer_player").outerHeight();
    var $inbox_height = $("#header_cont").outerHeight(true);
    var apply_height = window_height - footer_player_height -  $inbox_height;
    $("#member_list_cont").height(apply_height);
    $("#my-type-box").width($("#conversation_cont").width());
    $("#my-type-box").css('bottom',footer_player_height); 
    $("#member_list_c").width($(".members-list").outerWidth(true));

    var right_box = jQuery(".right_box").height();
    var my_type_box = jQuery(".my-type-box").outerHeight();
    jQuery(".msg-box").css('padding-bottom',my_type_box);


},

initNotificationRead: function() {
    if (notification_unread_count > 0) {
        $("#notification_counter").html('&nbsp;');
        url_not = my.config.siteUrl+"api/notification/read";
        my.routingAjax(url_not,"","",function(response)
        {
            if(response.status == "success")
            {   
             $(".notification_counter").addClass('displaynone').html(response.notification_unread_count);
         }
     },false,false);
    }
},
initNotificationAll:function(){
    $("body").on("click","a[data-role=all_notification]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");
                $.template("#notificationAllRow",my.config.loaded_template['notificationAllRow']);
                $.template("#contentPanel",my.config.loaded_template['notificationall']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.initPlugin();
            }
        },true,false,"",true);
    });
},

initNotificationComet: function() {
    var url = my.config.siteUrl+"api/notification/list";
    var arr1 = {};
    arr1["timestamp"] = notificationTimestamp; 
    /*'timestamp': _this.config.notificationTimestamp*/
    my.routingAjax(url,arr1,"",function(data)
    {
        notificationTimestamp = data['timestamp'];
        /*if (data.notification_total_count > 0) {
            $("#notification_counter").html(data.notification_total_count + ' Total');
        }*/
        if(jQuery.isEmptyObject(data.notifications) == false && data.notifications.length > 0)
        {
            if(data.notification_unread_count > 0)
            {
                $(".notification_counter").removeClass('displaynone').html(data.notification_unread_count)
            }
            $.each( data.notifications, function( key, value ) {                               
                var exist = $("#notification_"+value.notification_id).size();
                if(exist > 0)
                {

                }
                else{

                    $.template("#notrow",my.config.loaded_template['notification_row']);
                    $.tmpl('#notrow',value).prependTo("#notification_rows");
                }                                                            
            }); 
            notification_unread_count = data.notification_unread_count;
        }   
        else{
        }
        my.initNotificationComet();

    },false,false);

},

popupHandler:function(e,shareurl) {
    Width = 500;
    Height = 500;           
    e = (e ? e : window.event);
    var t = (e.target ? e.target : e.srcElement);
    var
    px = Math.floor(((screen.availWidth || 1024) - 500) / 2),
    py = Math.floor(((screen.availHeight || 700) - 500) / 2);
    var popup = window.open(t.href, "social", "width="+Width+",height="+Height+",left="+px+",top="+py+",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
    if (popup) {
        popup.focus();
        if (e.preventDefault) e.preventDefault();
        e.returnValue = false;
    }
    return !!popup;
},
validate_email:function(tag){
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var valid = emailReg.test(tag);
    if(!valid) {
        return false;
    } else {
        $("#display_invited_cont").removeClass("displaynone").show();
        return true;
    }
},
initSocialLinkShare:function(){
    var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
    var Config = {
        Link: "a.sharelink",
        Width: 500,
        Height: 500
    };
    var slink = document.querySelectorAll(Config.Link);
    for (var a = 0; a < slink.length; a++) {
        slink[a].onclick = my.popupHandler;
    }
    $('a[data-sharemodal]').click(function(e) {
        e.preventDefault();
        share_url = $(this).attr("data-shareurl");
        share_desc = $(this).attr("data-sharedesc");
        console.log('Share icon Init');
        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        var modalBox = $(this).attr('data-sharemodal');
        var encoded_share_url = encodeURI(share_url);
        //$("#sharepopup .fb-share-button").attr("data-href", share_url);        
        //$("#sharepopup .facebook").attr("href",fb_share+encoded_share_url);
        $("#sharepopup .facebook").attr("src",fb_share+encoded_share_url);
        //$("#sharepopup .fb-xfbml-parse-ignore").attr("href",fb_share+encoded_share_url);
                
        
        $("#sharepopup .twitter").attr("href",twit_share+share_url);
        
        $("#sharepopup .google").attr("data-href",share_url);
        $('#'+modalBox).fadeIn($(this).data());
        
        var temp = share_url.split('/');
        var track_name = temp[temp.length - 1];
        track_name = track_name.replace(/_/g, ' ');
        track_name = 'Shared track '+ track_name;
        console.log('Track name', track_name);
        //add Share feed for this user Following page
            $.ajax({
              url:App.config.siteUrl+'api/feed_save',              
              type:'POST',
              cache: false,              
              data: {
                 text: track_name,
                 feedType: 'text',
              },                          
              dataType:'json',
              success: function(data) {                
              }});
        
        
        
    });  
},
//new function for my app Initialize Share Popup
sharePopup:function(){
    $('a.share-icon').click(function(e) {        
        $("body").append('<div class="modal-overlay js-modal-close" style="opacity: 0.7;"></div>');
        share_url = $(this).attr("data-shareurl");
        share_desc = $(this).attr("data-sharedesc");
        var modalBox = $(this).attr('data-sharemodal');
        console.log('Share icon clicked');
        var encoded_share_url = encodeURI(share_url);        
        $("#sharepopup .facebook").attr("src",fb_share+encoded_share_url);       
        $("#sharepopup .twitter").attr("href",twit_share+share_url);        
        $("#sharepopup .google").attr("data-href",share_url);
        $(".modal-overlay").fadeTo(500, 0.7);
        $('#sharepopup').fadeIn($(this).data());      
        
        var temp = share_url.split('/');
        var track_name = temp[temp.length - 1];
        track_name = track_name.replace(/_/g, ' ');
        track_name = 'Shared track '+ track_name;
        console.log('Track name', track_name);
        //add Share feed for this user Following page
            $.ajax({
              url:App.config.siteUrl+'api/feed_save',              
              type:'POST',
              cache: false,              
              data: {
                 text: track_name,
                 feedType: 'text',
              },                          
              dataType:'json',
              success: function(data) {                
              }});
                
    });
},        
initRandomString:function(){
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var string_length = 8;
    var randomstring = '';
    for (var i=0; i<string_length; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
    }
    return randomstring;
},
scrollElem:function($target,source){
        /*console.log($target);
        console.log(source);*/
        $('.right_box').animate({
            scrollTop: $(source).offset().top
        }, 1000);
        /*$target.mCustomScrollbar("scrollTo", source);*/
        

    },
    leftPanelWidthset:function(){
        var width = $(window).width();          
        //console.log('Width 1167 ', width);
        var height = $(window).height();          
        var left_panel_width = $(".left-panel").outerWidth(true);
        var w = width-left_panel_width + 5;
        
        //console.log('Width left panel ', w);
        $(".content-box").width(w);
        $("#big-player").css({"width":w,"left":left_panel_width});
        $("#top-header .top-header-part").css({"width":w-17});
        $footer_height = $("footer div.footer_player ").height()+5;
        $footer_outer_height = $("footer div.footer_player ").outerHeight(true);
        //andy
        $(".right_box").css("padding-bottom",$footer_outer_height);
        $(".right_box").height(height-$footer_outer_height - 20);
        /*  $(".browse-songs").css("padding-bottom",$footer_outer_height);*/
    },
    initCancelUpload:function(){
        if(confirm("Are you sure you want to cancel upload?"))
        {               
            jQuery(".upload_cont").remove();
            Cancel_callback(flag,true);                                 
        }
        else
        {
            return false;
        }
    },
    initRemoveItem:function(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                break;
            }
        }
    },
    initCancelCallback:function(flag,remove_flag){
        if(jQuery.inArray(flag,upload_queue)>=0)
        {       
            if(remove_flag==true)
            {
                removeItem(upload_queue,flag);
            }
        }                   
        cancelled_q.push(flag);
        removeItem(main_q,flag);
    },
    initSaveTrack:function(r,type){
        console.log("save function ");
        if(typeof type != "undefined" && type == "edit")
        {
            var temps = "edit_save_track";
        }else{
            var temps = "save_track";
            if (type != 'no_change_never_sell') {
                console.log('Change value for Never Sell');
                if ($(".never_sell").val() == 1 || $(".never_sell").val() == '1'){
                    config.never_sell = 'y';                
                }  else config.never_sell = 'n';
            } 
            //else {
                //change value for never_sell to 2
                //$(".never_sell").val("2");
            //}
        }
        console.log(new FormData($("#track_form"+r)[0]));

        /*my.addProgressBarButton(function(){*/
            var postarray = new FormData($("#track_form"+r)[0]);
            //console.log('POST track save: ', JSON.stringify(postarray));
            postarray['progressButton']=$(this);
            var link_ajax = my.config.siteUrl+temps; 
            if (type == 'no_change_never_sell') link_ajax = link_ajax+'?nonevermusic=true';
            my.routingAjax(link_ajax,postarray,"",function(response){
                console.log("success");
                my.initRemoveItem(ref_q,r);
                if(typeof type != "undefined" && type == "edit")
                {
                    $("#track_form"+response.data.id).fadeOut();
                    $("#music_row_"+response.data.id).fadeOut();    
                    $(".close_popup").click();
                }else{              
                    $("#u_container_"+r).remove();
                    if(response.data.album_song == "yes")
                    {

                        var temp_selector = $("#album_row_"+response.data.album_id).find(".mediaItems .heading");
                        $.template("songslistRow",my.config.loaded_template['album_track_list_row']);   
                        $.template("u_tmpl_container_e",my.config.loaded_template['album_track_list_row']);
                        $.tmpl("u_tmpl_container_e",response.data).insertAfter(temp_selector);  
                        my.scrollElem($("#main").find(".mcs_container"),".album_row_"+response.data.album_id);                      

                    }else{                  

                        $.template("songslistRow",my.config.loaded_template['album_track_list_row']);   
                        $.template("u_tmpl_container_e",my.config.loaded_template['uploaded_song_row']);                                                    
                        $.tmpl("u_tmpl_container_e",response.data).prependTo("#my_songs_row");                              
                        my.scrollElem($("#main").find(".mcs_container"),".music_row_"+response.data.id);                        

                    }               
                }               
                my.ShowNotification("success","Success",response.msg);
               // $(".never_sell").val(0);
                localstorage_avail_space = localStorage.getItem("localstorage_avail_space");

                if(parseInt(localstorage_avail_space) != parseInt("-1")){
                 localstorage_avail_new_space = localstorage_avail_space - cur_song_space;
                 localStorage.setItem("localstorage_avail_space", localstorage_avail_new_space);
             }
             cur_song_space = 0;
             my.initPlugin();        
         },false,false,true);

    /*});*/
},
initCheckboxApplies:function(){
    $('.button-checkbox').each(function () {
        var $widget = $(this),
        $button = $widget.find('button'),
        $checkbox = $widget.find('input:checkbox'),
        color = $button.data('color'),
        settings = {
            on: {
                icon: 'fa fa-check'
            },
            off: {
                icon: 'fa fa-unchecked'
            }
        };
        $button.on('click', function (e) {
            if(!$(e.target).hasClass('edit_price_operation')){
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                my.initCheckboxUpdateDisplay($button,$checkbox,settings,color);
            }
            else{

            }
        });
        $checkbox.on('change', function (e) {
            if(!$(e.target).hasClass('edit_price_operation')){
                my.initCheckboxUpdateDisplay($button,$checkbox,settings,color);
            }
            else{

            }

        });
        my.initCheckboxInit($button,$checkbox,settings,color);
    });
},
initCheckboxUpdateDisplay:function($button,$checkbox,settings,color){
    var isChecked = $checkbox.is(':checked');
    $button.data('state', (isChecked) ? "on" : "off");
    $button.find('.state-icon')
    .removeClass()
    //if (typeof settings[$button.data('state')] !== 'undefined')
    .addClass('state-icon ' + settings[$button.data('state')].icon);
    //.addClass('state-icon fa fa-check');
    
    if (isChecked) {
        $button
        .removeClass('btn-default')
        .addClass('btn-' + color + ' active');
        var temp2 = $button.data('id');
        if (temp2 > 2 && temp2 < 8) {
           $button.find('a').editable({
                source: [
                       {id: '1000', text: 'Music Production and Audio Project / Lease (Up to 1000 copies)', selected: 'selected'},
                       {id: '10000', text: 'Music Production and Audio Project / Lease (1001 to 10000 copies)'},
                       {id: '50000', text: 'Music Production and Audio Project / Lease (10001 to 50000 copies)'},
                       {id: '100000', text: 'Music Production and Audio Project / Lease (Over 50000 copies)'}
                    ],
                 success: function(response, newValue) {
                     hid_id = $(this).attr('data-val');
                     var licenseId = 4;
                     if (newValue == 1000 || newValue == '1000'){
                        licenseId = 4;
                     } else if (newValue == 10000 || newValue == '10000') {
                        licenseId = 5;
                     } else if (newValue == 50000 || newValue == '50000') {
                         licenseId = 6;                
                     } else if (newValue == 100000 || newValue == '100000') {
                         licenseId = 7;                
                     }
                     //hid_id 4 no change
                     console.log('ID of selection new Value edit: ', newValue, licenseId);            
                     $("#license_number_"+hid_id).val(licenseId);

                     $(this).parent().parent().addClass('active');            
                     $(".edit_license_number").parent().parent().find("i").removeClass('fa-unchecked').addClass('fa-check');
                     //$(this).parent().parent().prepend('<i class="state-icon fa fa-check"></i> ');
                 }
                 }); 
        }
    }
    else {
        $button
        .removeClass('btn-' + color + ' active')
        .addClass('btn-default');
        //andy hide any not active checkbox
        var temp = $button.data('val');
        var temp2 = $button.data('id');        
        if (/edit/.test(url_location_static)){
            //4 times to hide Only for Edit
            if (temp2 > 2 && temp2 < 8 && hide_number_license < 5) {
                hide_number_license++;
                $button.hide();
                console.log('ID of button not active: ', temp2);
            }                    
        }
        
    }
},
initCheckboxInit:function($button,$checkbox,settings,color){
    my.initCheckboxUpdateDisplay($button,$checkbox,settings);
    if ($button.find('.state-icon').length == 0) {
        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
    }
},
initEditableCheckbox:function(){
    $('.sell_type_edit_val').editable({
        success: function(response, newValue) {
            hid_id = $(this).attr('data-val');
            $("#music_vals_"+hid_id).val(newValue);                         
        }
    });
    $('.licence_type_edit_val').editable({
        success: function(response, newValue) {
            //set new value ID for license option             
            hid_id = $(this).attr('data-val');  
            $("#music_vals_"+hid_id).val(newValue);
        }
    }); 
   $('.edit_license_number').editable({
       source: [
              {id: '1000', text: 'Music Production and Audio Project / Lease (Up to 1000 copies)', selected: 'selected'},
              {id: '10000', text: 'Music Production and Audio Project / Lease (1001 to 10000 copies)'},
              {id: '50000', text: 'Music Production and Audio Project / Lease (10001 to 50000 copies)'},
              {id: '100000', text: 'Music Production and Audio Project / Lease (Over 50000 copies)'}
           ],
        success: function(response, newValue) {
            hid_id = $(this).attr('data-val');
            var licenseId = 4;
            if (newValue == 1000 || newValue == '1000'){
               licenseId = 4;
            } else if (newValue == 10000 || newValue == '10000') {
               licenseId = 5;
            } else if (newValue == 50000 || newValue == '50000') {
                licenseId = 6;                
            } else if (newValue == 100000 || newValue == '100000') {
                licenseId = 7;                
            }
            //hid_id 4 no change
            console.log('ID of selection new Value: ', newValue, licenseId);            
            $("#license_number_"+hid_id).val(licenseId);
                        
            $(this).parent().parent().addClass('active');            
            $(".edit_license_number").parent().parent().find("i").removeClass('fa-unchecked').addClass('fa-check');
            //$(this).parent().parent().prepend('<i class="state-icon fa fa-check"></i> ');
        }
    }); 
    $('.np_type_edit_val').editable({
        success: function(response, newValue) {
            $("#np_price").val(newValue);
        }
    }); 
},
initSwitchApply:function(){
    var vocal_switch_state = $('.vocal_switch').bootstrapSwitch('state');

    if(vocal_switch_state)
    {
       my.UploadAgreeCheckbox();
       if(vocal_switch_state == true)
        $(".vocal_disp").removeClass("displaynone").fadeIn();
    else
        $(".vocal_disp").addClass("displaynone").fadeOut();
}

$('.vocal_switch').on('switchChange.bootstrapSwitch', function (event, state) 
{
    my.UploadAgreeCheckbox();
    if(state == true)
        $(".vocal_disp").removeClass("displaynone").fadeIn();
    else
        $(".vocal_disp").addClass("displaynone").fadeOut();
});

var sale_avail_switch_state = $('.sale_avail_switch').bootstrapSwitch('state');

if(sale_avail_switch_state)
{
    my.UploadAgreeCheckbox();
    if(sale_avail_switch_state == true)
    {   
        $(".music_available_sale_disp").removeClass("displaynone").fadeIn();
    }
    else
    {   
        $(".music_available_sale_disp").addClass("displaynone").fadeOut();
    }
}

$('.sale_avail_switch').on('switchChange.bootstrapSwitch', function (event, state) {
    my.UploadAgreeCheckbox();
    if(state == true)
    {   
        $(".music_available_sale_disp").removeClass("displaynone").fadeIn();
    }
    else
    {   
        $(".music_available_sale_disp").addClass("displaynone").fadeOut();
    }
});
var licence_avail_switch_state = $('.licence_avail_switch').bootstrapSwitch('state');

if(licence_avail_switch_state)
{
    my.UploadAgreeCheckbox();
    if(licence_avail_switch_state == true)
        $(".lic_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".lic_avail_disp").addClass("displaynone").fadeOut();
}

$('.licence_avail_switch').on('switchChange.bootstrapSwitch', function (event, state) {
    my.UploadAgreeCheckbox();
    if(state == true)
        $(".lic_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".lic_avail_disp").addClass("displaynone").fadeOut();
});

//exclusive license
var exclusive_avail_switch_state =  $('.exclusive_license_switch').bootstrapSwitch('state');

if(exclusive_avail_switch_state)
{
    my.UploadAgreeCheckbox();
    if(exclusive_avail_switch_state == true)
        $(".el_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".el_avail_disp").addClass("displaynone").fadeOut();
}

$('.exclusive_license_switch').on('switchChange.bootstrapSwitch', function (event, state) {
    my.UploadAgreeCheckbox();
    if(state == true)
        $(".el_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".el_avail_disp").addClass("displaynone").fadeOut();
}); 


var nonprofit_avail_switch_state =  $('.nonprofit_avail_switch').bootstrapSwitch('state');

if(nonprofit_avail_switch_state)
{
    my.UploadAgreeCheckbox();
    if(nonprofit_avail_switch_state == true)
        $(".np_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".np_avail_disp").addClass("displaynone").fadeOut();
}

$('.nonprofit_avail_switch').on('switchChange.bootstrapSwitch', function (event, state) {
    my.UploadAgreeCheckbox();
    if(state == true)
        $(".np_avail_disp").removeClass("displaynone").fadeIn();
    else
        $(".np_avail_disp").addClass("displaynone").fadeOut();
}); 

var never_sell_music_state = $('.never_sell').bootstrapSwitch('state');

if(never_sell_music_state)
{
    my.UploadAgreeCheckbox();
    if(never_sell_music_state == true)
      {
          $(".never_sell").val(1);  
          config.never_sell = 'y';
      }
    else {
        $(".never_sell").val(2);
        config.never_sell = 'n';
    }
      
}

$('.never_sell').on('switchChange.bootstrapSwitch', function (event, state) {
    my.UploadAgreeCheckbox();
    if(state == true) {
      $(".never_sell").val(1);    
      config.never_sell = 'y';
    } else {
        $(".never_sell").val(2);
        config.never_sell = 'n';
    }
      
});

},
UploadAgreeCheckbox:function(){
   nonprofit_avail_switch_state = $('.nonprofit_avail_switch').bootstrapSwitch('state');
   exclusive_avail_switch_state = $('.exclusive_license_switch').bootstrapSwitch('state');
   licence_avail_switch_state = $('.licence_avail_switch').bootstrapSwitch('state');
   sale_avail_switch_state = $('.sale_avail_switch').bootstrapSwitch('state');
   if(nonprofit_avail_switch_state == true || licence_avail_switch_state == true || sale_avail_switch_state == true ||
       exclusive_avail_switch_state == true)
   {
    $("#certify_checkbox").removeClass('displaynone');
}
else
{
    $("#certify_checkbox").addClass('displaynone');
}
},
initalbummodalSwitchApply:function(){
    $('.sale_avail_modal_switch').bootstrapSwitch('state');
    $('.sale_avail_modal_switch').on('switchChange.bootstrapSwitch', function (event, state) {

    });

    $('.album_partial_modal_switch').bootstrapSwitch('state');
    $('.album_partial_modal_switch').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state == true)
        {
            $(".sale_avail_switch_modal_disp").removeClass("displaynone").fadeIn();
            $(".album_sale_def_modal_msg").addClass("displaynone").fadeIn();

        }    
        else{
            $(".album_sale_def_modal_msg").removeClass("displaynone").fadeIn();
            $(".sale_avail_switch_modal_disp").addClass("displaynone").fadeOut();
        }
    });   
},
initalbumSwitchApply:function(){
 $('.sale_avail_album_switch').bootstrapSwitch('state');
 $('.sale_avail_album_switch').on('switchChange.bootstrapSwitch', function (event, state) {

 });

 $('.album_partial_switch').bootstrapSwitch('state');
 $('.album_partial_switch').on('switchChange.bootstrapSwitch', function (event, state) {
    if(state == true)
    {
        $(".sale_avail_switch_disp").removeClass("displaynone").fadeIn();
        $(".album_sale_def_msg").addClass("displaynone").fadeIn();

    }    
    else{
        $(".album_sale_def_msg").removeClass("displaynone").fadeIn();
        $(".sale_avail_switch_disp").addClass("displaynone").fadeOut();
    }
});
},
initFormatFileSize:function(bytes){
    if (typeof bytes !== 'number') {
        return '';
    }
    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }
    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }
    return (bytes / 1000).toFixed(2) + ' KB';
},
initGenerateWaveform:function($eleme,hovercolor,forcefully_gen)
{
    /*return true;*/
    var element_passed = true;
    if(typeof $eleme == "undefined" || $eleme == "")
    {
        $eleme = $(".waveform_img_div");
        element_passed = false;
    }
    if(element_passed == false)
    {
        if($eleme.size() > 0)
        {
            $eleme.each(function(){
                var $this = $(this);    
                my.initWaveformProcess($this,hovercolor,forcefully_gen);
            });
        }
    }else{
        var $this = $(this);    
        my.initWaveformProcess($eleme,hovercolor,forcefully_gen);
    }
},
initWaveformProcess:function($this,hovercolor,forcefully_gen){
    if(!$this.hasClass('waveformadded') || typeof forcefully_gen != 'undefined')
    {
        var dataurl = $this.attr("data-waveurl");
        var height = $this.attr("data-height");
        var width = $this.width();
        var container = $this;

        $.ajaxSetup({
         async: true
     });

        /*Pace.ignore(function(){*/
            $.getJSON(dataurl,{
            }, function(d){
                var sound;
                var waveform_width = width;
                var waveform_height = height;
                var bar_width = 3;
                var bar_gap = 0.3;
                container.html("");                 
                var waveform = new Waveform({
                    container: container.get(0),
                    outerColor: "transparent",
                    width: waveform_width,
                    height: waveform_height,
                    interpolate: false,
                    innerColor: function(x){
                        if(typeof hovercolor != "undefined" || hovercolor == "")
                        {
                            return "rgba(255,255,255,1)";
                        }
                        else
                        {
                            return "rgba(255,255,255,1)";
                            /* return "rgba(102,102,102, 0.4)";*/
                        }                   
                    },
                    data: d
                });
                temp = container.get(0);
                if($this.hasClass("waveform_cont"))
                {
                    var waveform = new Waveform({
                        container: container.get(0),
                        outerColor: "transparent",
                        width: waveform_width,
                        height: waveform_height,
                        innerColor: function(x){
                            return "rgba(255,255,255,1)";
                        },
                        data: d
                    });
                }
                $this.addClass("waveformadded");
            });
    /*});*/
    $.ajaxSetup({
        async: false
    });

}   
},
initScrollToTop:function(){
    var offset = 300;
    var duration = 500;
    if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {  
        $(window).bind("touchend touchcancel touchleave", function(e){
            if ($(this).scrollTop() > offset) {
                $('.scroll-to-top').fadeIn(duration);
            } else {
                $('.scroll-to-top').fadeOut(duration);
            }
        });
    } else {  
        $(document).scroll(function() {
            if ($(this).scrollTop() > offset) {
                $('.scroll-to-top').fadeIn(duration);
            } else {
                $('.scroll-to-top').fadeOut(duration);
            }
        });
    }
    $('.scroll-to-top').click(function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    });
},
initPlayerQueue:function(){
    var queue=[];
    var site_url = my.config.siteUrl;;
    var temp2={};
    temp2.url=site_url+"DavidWalters/xy";
    temp2.track=site_url+"DavidWalters/xy";
    temp2.title="XY";
    queue.push(temp2);
    /*var temp3={};
    temp3.url=site_url+"a/BlankSpace";
    temp3.track=site_url+"a/BlankSpace";
    temp3.title="Animals";
    queue.push(temp3);
    var temp5={};
    temp5.url=site_url+"a/SexyBeaches";
    temp5.track=site_url+"a/SexyBeaches";
    temp5.title="SexyBeaches";
    queue.push(temp5);*/
    /*{
        queue:queue
    }*/
    $('.big_player').scPlayer({
        queue:''
    });
},
ShowNotification:function(shortCutFunction,title,msg,$showDuration ,$hideDuration,$timeOut,$extendedTimeOut,$showEasing,$hideEasing,$showMethod,$hideMethod){
    if (typeof shortCutFunction === "undefined" || shortCutFunction === null) { 
        shortCutFunction  = "success"
    }           
    if (typeof $showDuration === "undefined" || $showDuration === null) { 
        $showDuration  = 3000;
    }           
    if (typeof $hideDuration === "undefined" || $hideDuration === null) { 
        $hideDuration  = 2500;
    }           
    if (typeof $timeOut === "undefined" || $timeOut === null) { 
        $timeOut  = 2500;
    }       
    if (typeof $extendedTimeOut === "undefined" || $extendedTimeOut === null) { 
        $extendedTimeOut  = 2500;
    }
    if (typeof $showEasing === "undefined" || $showEasing === null) { 
        $showEasing  = "swing";
    }
    if (typeof $hideEasing === "undefined" || $hideEasing === null) { 
        $hideEasing  = "linear";
    }
    if (typeof $showMethod === "undefined" || $showMethod === null) { 
        $showMethod = "fadeIn";
    }
    if (typeof $hideMethod === "undefined" || $hideMethod === null) { 
        $hideMethod  = "fadeOut";
    }    



    toastr.options = {
        closeButton: true,
        debug: $('#debugInfo').prop('checked'),
        positionClass: 'toast-top-right',
        onclick: null
    };
    toastr.options.showDuration = $showDuration;
    toastr.options.hideDuration = $hideDuration;
    toastr.options.timeOut = $timeOut;
    toastr.options.extendedTimeOut = $extendedTimeOut;
    toastr.options.showEasing = $showEasing;
    toastr.options.hideEasing = $hideEasing;
    toastr.options.showMethod = $showMethod;
    toastr.options.hideMethod = $hideMethod;
    if (!msg) {
        msg = "";
    }
    if(typeof $toast != "undefined")
    {
     $toast.remove();
 }
 $toast = toastr[shortCutFunction](msg, title); 
 $toastlast = $toast;
 if ($toast.find('#okBtn').length) {
    $toast.delegate('#okBtn', 'click', function () {
        $toast.remove();
    });
}
if ($toast.find('#surpriseBtn').length) {
    $toast.delegate('#surpriseBtn', 'click', function () {
    });
}
},
check_file:function(value,allowedExtensions){
    file = value.toLowerCase();
    extension = file.substring(file.lastIndexOf(".") + 1);
    allowedExtensions=allowedExtensions+",";            
    allowedExtensions_ar=allowedExtensions.split(",");
    allowedExtensions_ar.filter(function(e){return e;});                
    if ($.inArray(extension, allowedExtensions_ar) == -1) {
        return false;
    } 
    else {
        return true;
    }
},
initImagePreview:function(event,input,extensions,returndata){
},
initPlaysetMinHeight:function(deduct_height_e,append_height_e){
    if(my.config.loggedIn == true && document.URL == my.config.siteUrl+"playlist")
    {
        new_height = $(window).height() - $(deduct_height_e).outerHeight() - $("#trans-bg").height();
        $(append_height_e).css({
            "min-height" : new_height,
            "background-color" : "#151617"
        });
    }
},
initLookup:function(inputString){
    if(inputString.length == 0) {
        $('#suggestions').fadeOut(); 
    } else {
        var arr = {};
        arr["query"] = inputString;         
        if(inputString != "")
        {
            my.routingAjax(my.config.siteUrl+"msg_alloweduserlist",arr,"",function(response){                   
                if(response.success == "success")
                {
                    $('#suggestions').html("");
                    if(response.data && jQuery.isEmptyObject(response.data) == false)
                    {

                        $.template("u_tmpl_container_e",my.config.loaded_template['user_suggestion_list']);
                        $.tmpl("u_tmpl_container_e",response.data).prependTo("#suggestions");                               

                        $('#suggestions').fadeIn(); 
                    }
                    else{                               
                        $('#suggestions').html("No users.please try again with new keyword.");
                        $('#suggestions').fadeIn();
                    }
                    
                }
                else if(response.error == "error")                  
                {
                    ShowNotification("error","Error",response.msg);
                }
            },false,false);         
    return false;       
}
}
},
initRemoveValue:function(list, value){
    list = list.split(',');
    list.splice(list.indexOf(value), 1);
    return list.join(',');
},
IncreaseTrackCounter:function(trackId){
    var arr = {};
    arr["trackId"] = trackId;
    arr["userId"] = my.config.userIdJs;
    if(arr["trackId"] > 0)
    {
        my.routingAjax(App.config.siteUrl+"increase_track_counter",arr,"",function(response){              

            $(".play_counter_disp_js[data-track='"+response.perLink+"']").html(response.count);

        },false,false);  
    }
},
initUploadDetails:function(){
    //if(my.config.loggedIn == true && (document.URL == my.config.siteUrl+"upload" || /edit/g.test(document.URL)))
    if(my.config.loggedIn == true && document.URL == my.config.siteUrl+"upload" )
    {
        my.routingAjax(my.config.siteUrl+"upload_details","","",function(response){
            response_genre = response.genre;
            response_sec_genre = response.sec_genre;
            response_soundlike = response.sound_like_list;
            response_album_list = response.album_list;
            mood_list = response.mood_list; 
            response_instruments_list = response.instuments_list; 
            sell_type_list = response.sell_type_list;
            licence_types_list = response.licence_type_list;
            np_type_list = response.np_type_list;
            el_type_list = response.el_type_list;
            track_upload_type_list = response.track_upload_type_list;  
            lower_type_list = response.lower_type_list; 
            higher_type_list = response.higher_type_list; 
            console.log('Fetch upload form selection');
            
        },false,false,true);    
    }           
},
initSearchCondition:function(){
    genre_explore_list = $("#select_tags_cont").val();
    if(genre_explore_list != "")
    {
        search_params["genre_explore_list"] = genre_explore_list;
    }
    else if(genre_explore_list == "" || genre_explore_list == NULL){
        search_params["genre_explore_list"] = "";
    }
    subgenre_explore_list = $("#select_subtags_cont").val();
    if(subgenre_explore_list != "")
    {
        search_params["subgenre_explore_list"] = subgenre_explore_list;
    }
    else if(subgenre_explore_list == "" || subgenre_explore_list == NULL){
        search_params["subgenre_explore_list"] = "";
    }
    genre_explore_id = $("#genre_explore option:selected").val();
    if(genre_explore_id > 0)
    {
        search_params["d_genre_explore"] = genre_explore_id;
    }
    mood_explore_id = $("#mood_explore option:selected").val();
    if(mood_explore_id > 0)
    {
        search_params["d_mood_explore"] = mood_explore_id;
    }
    instrument_explore_id = $("#instrument_explore option:selected").val();
    if(instrument_explore_id > 0)
    {
        search_params["d_instrument_explore"] = instrument_explore_id;
    }
},
initSearchResultRender:function(arr,flag,genreid,append_title_flag){
    console.log(arr);
    my.routingAjax(my.config.siteUrl+"exp_search",arr,"",function(response){    
        $("#explore_songs").html("");           
        if(response.success == "success")
        {               
            $.template("#profileHeader",my.config.loaded_template['browse_music_header']);
            $.template("#arrayData",my.config.loaded_template['browse_pop_new_songs']);
            $.template("#loadMore",my.config.loaded_template['loadmore']);  
            $.template("tabrender",my.config.loaded_template['tab_render']);
            $.tmpl("tabrender",response).appendTo("#explore_songs");
            search_params = {};
            
        }
        else if(response.error == "error")                      
        {
            $("#explore_songs").html("No records contains on this parameter."); 
            my.ShowNotification("info","Info",response.msg);
            search_params = {};
        }
        if(typeof flag != "undefined" && flag == "genre_search")
        {

            $("#tags_ul").html("");
            $.template("tagsrender",my.config.loaded_template['exploretags']);      
            $.tmpl("tagsrender",response).appendTo("#tags_ul");                         
            if(typeof append_title_flag != "undefined" && append_title_flag == "no_append")
            {
                $("#tags_header").text(response.header_main_title); 
            }
            else{
                if($("#tags_header").text() == "SEARCH WITH TAGS")
                {
                    var header_html = "<span id='header_tags_div'><a href='Javascript:void(0)' data-id='"+genreid+"' class='tags_header_maintitle selected_tag'>"+tagname+"</a></span> <span class='tags_header_maintitle' data-id='"+genreId+"'> + </span> <span class='ellipsis'>...</span>";
                    $("#tags_header").html(header_html);
                }else{
                    var header_html = " <span class='tags_header_maintitle' data-id='"+genreId+"'> + </span> <a href='Javascript:void(0)' data-id='"+genreid+"' class='tags_header_maintitle selected_tag'>"+tagname+"</a>";
                    $("#header_tags_div").append(header_html);  
                }
            }
            
        }
        
    },false,false);
},
initPopupSearch:function(){
    var char_tot = jQuery("#search_input_box").val().length;
    if(char_tot >= 1)
    {   
        var keyword = jQuery("#search_input_box").val();
        try {           
            var arr = {};
            arr["keyword"] = keyword;                       
            my.routingAjax(my.config.siteUrl+"search",arr,"",function(response){        
                if(response.success == "success")
                {
                    $("#search-items").html("");
                    $.template("#artistsRow",my.config.loaded_template['search_res_artist_row']);
                    $.template("#songsRow",my.config.loaded_template['search_res_track_row']);
                    $.template("#playlistsRow",my.config.loaded_template['search_res_pl_row']);
                    $.template("songs_cont",my.config.loaded_template['search_results']);
                    $.tmpl("songs_cont",response.data).appendTo("#search-items");
                    
                }
                else if(response.error == "error")                      
                {

                    $("#search-items").html("<span class='nosearchres'>Your search did not return any results.Please try again.</span>");
                }
            },false,false);
        } catch(e) {
            console.log("Error in redirection - " + e);
        }                           
    }
    else if(char_tot == 0 || char_tot == "undefined"){              
    }
    else{
    }
},
initPopup:function(){
    $("body").on("click",".popup",function(e){
        e.preventDefault();
        var temp = $(this).attr("data-t");
        var href=$(this).attr("href");
        if(!$('.modal').is(":visible")){
            my.config.history_redirect_url = document.URL;
        }
        var a={"temp":temp};
        my.routingAjax(href,{},JSON.stringify(a),function(response){            
            if(typeof(response)!='undefined'){
                console.log('Ajax response for Popup: ',response);
                ajax_popupdata = response;
                $('.modal').modal('hide');
                $("#popup").html('');                       
                var $a=$.template("popUpContent",my.config.loaded_template[response.extra.temp]);
                
//                var temp = $.tmpl("popUpContent",response);
//                console.log('Temp html: ',temp[0], typeof temp);
//                var new_html = temp[0].toString();
//                new_html = new_html.replace(/[object Object]/g, '42');
//                temp[0] = new_html;
//                temp.appendTo("#popup");
                //$("#popup").append(temp);
                //temp.appendTo("#popup");
                $.tmpl("popUpContent",response).appendTo("#popup");                                                
                
                $.when($a).then(function(){    
                    console.log(response.extra.temp);       
                    //reload FB sdk after ajax (because it only loads for initial page)
                    if (response.extra.temp == 'sign_in' || response.extra.temp == 'sign_up') {
                        setTimeout(function(){
                            intializeFB()},300); 
                        //intializeFB();
                    }                                        
                        if (response.extra.temp == 'invite') {                            
                            setTimeout(function(){
                            my.initPlugin()},1000); 
                        }
                    
                    $(".modal."+response.extra.temp).modal("show");
                    if($(".invite_page").size()>0){
                        $(".invite").addClass("show");
                        my.initPlugin();
                    }
                    if(temp == "edit_profile")
                    {
                        var croppic4 = new Croppic('edit_profile_img_modal', cropOpt_prof_main_pic);  
                    }
                });
            }               
        },true,false);      
    });
},
initSignUp:function(){
    $("body").on('show.bs.modal','.sign_up_mail', function (e) {
        $("#signup_form").validationEngine("attach",{                   
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){
                    $("#signup_form").addClass("disabled");
                    /*my.addProgressBarButton(function(){*/

                       var postarray=$("#signup_form").serializeArray();
                       postarray['progressButton']=$("#submitsignup");

                       my.routingAjax(my.config.siteUrl+"api/signup",postarray,"",function(response){
                        if(response.id > 0)
                        {
                            $("#signup_form").removeClass("disabled");
                            my.refreshLeftPanel(response);
                            my.redirectLoginAfter("Registered Successfully.");  
                            if(response.role_added == "n")
                            {
                                $('<a href="'+my.config.siteUrl+'setup" id="setup" data-t="setup" class="popup"></a>').appendTo("body").click();                                    
                            }   
                        }                       
                    },false,false); 

                       /*});*/
}
}                           
});
});
    $("body").on('show.bs.modal','.sign_up_middlepage', function (e) {
        $("#signup_form_middle").validationEngine("attach",{            
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){
                    $("#signup_form_middle").addClass("disabled");
                    /*my.addProgressBarButton(function(){*/
                     var postarray=$("#signup_form_middle").serializeArray();
                     postarray['progressButton']=$("#signup_btn_mpage");
                     my.routingAjax(my.config.siteUrl+"api/signup",$("#signup_form_middle").serializeArray(),"",function(response){
                        if(response.id > 0)
                        {
                            $("#signup_form_middle").removeClass("disabled");
                            my.refreshLeftPanel(response);
                            my.redirectLoginAfter("Registered Successfully.");  
                            if(response.role_added == "n")
                            {
                                $('<a href="'+my.config.siteUrl+'setup" id="setup" data-t="setup" class="popup"></a>').appendTo("body").click();                                    
                            }   
                        }                       
                    },false,false); 
                     /*});*/
}
}                           
});
});
    $("body").on("click","#submitsignup",function(e){
        e.preventDefault();
        if(!$("#signup_form").hasClass("disabled")){
            $("#signup_form").submit();                 
        }               
    });
    $("body").on("click","#signup_btn_mpage",function(e){
        e.preventDefault();
        if(!$("#signup_form_middle").hasClass("disabled")){
            $("#signup_form_middle").submit();                  
        }                       
    });
},
initSuccessSocialLogin:function(data,flag){
    if(flag==true){
        $('.modal').modal('hide');
        $("#popup").html('');                       
        var $a=$.template("popUpContent",my.config.loaded_template["sign_up_middlepage"]);
        $.tmpl("popUpContent",data).appendTo("#popup");
        $.when($a).then(function(){
            $(".sign_up_middlepage").modal("show");
        });
    }
    else{
        my.refreshLeftPanel(data);  
        my.redirectLoginAfter("logout");            
    }
},
initGiftcouponapply:function(){
    $("body").on('click','#submitcouponform', function (e) {    
        e.preventDefault();
        url = $(this).attr("href");
        my.routingAjax(my.config.siteUrl+"api/apply_coupon_api",$("#giftcoupon_form").serializeArray(),"",function(response){
            if(response.status == "success")
            {
                my.ShowNotification("info","Info",response.msg);
                my.initPlugin();
            }
            else{
                my.ShowNotification("info","Info",response.msg);
                my.initPlugin();
            }       
        },false,false); 
    });
},
initMembership:function(){
    $("body").on('click','.membership_buy_btn', function (e) {  
        e.preventDefault();
        url = $(this).attr("href");
        my.routingAjax(my.config.siteUrl+"api/membershipcheck","","",function(response){
            if(response.status == "plan_active")
            {
                var temp = confirm("Your previous plan is active till "+response.endDate+" are you sure to upgrade existing plan?");
                if(temp != false)
                {   
                    window.location.href = url;
                }else{
                }
                my.initPlugin();
            }
            else if(response.status == "no_plan_active"){
                window.location.href = url;
            }       
        },false,false); 
    });
    $("body").on('click','.membership_cancel_plan', function (e) {
        e.preventDefault();
        var temp = confirm("Are you sure to cancel current plan? Your account will be converted into fremium plan.");
        var url = $(this).attr("href");
        if(temp == true)
        {
            my.routingAjax(url,"","",function(response){                
                //andy
                if(response.status == "success")
                {
                     $(".membership_cancel_plan").html('Buy Now');  
                     var plan = $(".membership_cancel_plan").attr("data-plan");
                     
                     console.log(plan);
                     plan = '/imusify/membership/'+plan;
                     $(".membership_cancel_plan").attr("href", plan); 
                     $(".membership_cancel_plan").addClass("membership_buy_btn");
                     //$(".membership_cancel_plan").addClass("membership_remove");
                     $(".membership_cancel_plan").removeClass("membership_cancel_plan");
                     //location.reload();
                     my.ShowNotification("success","Success", response.msg);
                     my.initPlugin();
                     
                }
                else if(response.status == "failed"){
                    my.ShowNotification("info","Info", response.msg);
                    my.initPlugin();
                } else if(! response.status || response.status == 'false'){
                     my.ShowNotification("info","Info", response.error);
                     my.initPlugin();
                }        
            },false,false); 
        }
        else{
            return false;
        }
    });
},
initInviteFriends:function(){
    /*$("body").on('show.bs.modal','.invite', function (e) {  
        e.preventDefault();
        $("#invite_form").validationEngine("attach",{               
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){                                       
                    $("#invite_form").addClass("disabled");
                    var postarray=$("#invite_form").serializeArray();
                    postarray['progressButton']=$("#submitinvite");
                    my.routingAjax(my.config.siteUrl+"api/invitefriends",postarray,"",function(response){
                        if(response.status == "success")
                        {
                            my.ShowNotification("Friends invited successfully.");   
                        }       
                    },false,false); 
                }
            }                           
        });
});*/
    $("body").on("click","#submitinvite",function(e)
    {
        e.preventDefault();
        var seldata = $(".invite_email_tagmanage").tagsManager('tags');
        //if(seldata.length == 0 || typeof seldata == "undefined")
        if(typeof seldata == "undefined")
        {
            e.preventDefault();
            $("#invite_form").validationEngine();
            if($("#invite_form").validationEngine('validate'))
            {
                my.ShowNotification("info","info",'Please enter to add an email address OR email is not valid');
            }
        }
        else{
            if(!$("#invite_form").hasClass("disabled")){
                $("#invite_form").addClass("disabled");
                var postarray=$("#invite_form").serializeArray();
                postarray['progressButton']=$("#submitinvite");
                my.routingAjax(my.config.siteUrl+"api/invitefriends",postarray,"",function(response){                    
                    if(response.status == "success")
                    {
                        $(".invite_email_tagmanage").tagsManager('empty');
                        my.ShowNotification("success","success","Friends invited successfully.");   
                    }  
                    else{
                        my.ShowNotification("info","info",response.msg); 
                    }
                    $("#invite_form").removeClass("disabled");
                    
                },false,false); 
                $("#invite_form").removeClass("disabled");
            } 
        }
    });
},
initAccount:function(){
    $("body").on("click","a[data-role=account]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");
                
                $.template("#templaterow",my.config.loaded_template['account_change_password']);                                    
                $.template("#contentPanel",my.config.loaded_template['account_main']);                                                                
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.initPlugin();
                
            }
        },true,false,"",true);
    });

    $("body").on("click","a[data-role=account_change_password]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  

                $("#account_content").html("");
                $.template("#contentPanel",my.config.loaded_template['account_change_password']);                                                                    
                $.tmpl('#contentPanel',response).appendTo("#account_content");
                my.initPlugin();

            }
        },true,false,"",true);
    });

    $("body").on("click","a[data-role=stripe_connect]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                if(response.status == "connected")
                {
                    temp_name = "stripe_connected";
                }
                else{
                    temp_name = "account_stripe_connect";
                }
                
                $("#account_content").html("");
                $.template("#templaterow",my.config.loaded_template[temp_name]);
                $.tmpl('#templaterow',response).appendTo("#account_content");
                my.initPlugin();

            }
        },true,false,"",true);
    });

    $("body").on("click","#submitchangepassword",function(e){
        e.preventDefault();
        $("#change_password_form").validationEngine();
        if($("#change_password_form").validationEngine('validate'))
        {
            my.routingAjax(my.config.siteUrl+"api/change_password_api",$("#change_password_form").serializeArray(),"",function(response){
                if(response.status == "success")
                {
                    $("#change_password_form")[0].reset();
                    $("a[data-role='home']").click();
                    my.ShowNotification("success","Success","Password changed successfully.");
                }
            },false,false);
        }                       
    });
},
suc_stripe_connect:function(status,msg){
    if(status == "success")
    {
        my.ShowNotification("success","Success","Your account is connected with stripe successfully.");
        $("#stripe_connect").html("");
    }
    else{
        my.ShowNotification("error","Error",msg);
    }
},
initStipeconnect:function(){
    $("body").on('click','.stripe_connect', function (e) { 
        e.preventDefault();
        var type = $(this).attr("data-type");
        var href= $(this).attr("href");
        var win = window.open(href, "aaa", "width=800, height=700");
        var timer = setInterval(function() {   
            if(win.closed) {  
                clearInterval(timer);  
                my.routingAjax(my.config.siteUrl+"api/check_user_connect","","",function(response){     
                    if(response.status == "success")
                    {
                        $("#account_content").html("");
                        var $a=$.template("tempcont",my.config.loaded_template["stripe_connected"]);
                        $.tmpl("tempcont",response).appendTo("#account_content");

                    }
                    else if(response.status == "error"){

                    }
                    if(response.msg){
                        my.ShowNotification(response.status,response.status,response.msg);
                    }

                },false,false);
            }  
        }, 1000); 
    });

},
initLogin:function(){
    $("body").on('click','.sociallogin', function (e) { 
        var type = $(this).attr("data-type");
        if(type == "fb")
            url = "fblogin";
        else if(type == "sc")
            url = "sclogin";
        else if(type == "in")
            url = "linklogin";

//        var xhr = my.config.siteUrl+url ;
//        window.open(xhr, "", "width=800, height=700");
    });

    $("body").on('show.bs.modal','.sign_in', function (e) { 
        $("#forgot_pwd_form").validationEngine("attach",{               
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){                                       
                    $("#forgot_pwd_form").addClass("disabled");
                    /*my.addProgressBarButton(function(){ */

                       var postarray=$("#forgot_pwd_form").serializeArray();
                       postarray['progressButton'] = $("#submitresetform");

                       my.routingAjax(my.config.siteUrl+"api/forgotupwd",postarray,"",function(response){
                        if(response.success)
                        {
                            my.redirectLoginAfter("Please check mail for reset password");  
                        }       
                    },false,false); 

                       /*});*/
    }
}                           
});
    });
    $("body").on("click",".for-pass",function(e){
        e.preventDefault();
        var type = $("#type").val();        
        if(type == "sign_in")
        {
            $(".type").val("forgot_pwd");
            $(".for-pass").text("Back to login");
            $("#forgot_pwd_form").removeClass("displaynone").addClass("display");
            $("#login_form").addClass("displaynone");
        }else if(type == "forgot_pwd"){
            $(".type").val("sign_in");
            $(".for-pass").text("Forgot password?");
            $("#login_form").removeClass("displaynone").addClass("display");
            $("#forgot_pwd_form").addClass("displaynone");
        }
    });
    $("body").on("click","#submitloginform",function(e){
        e.preventDefault();
        var submit_type = $("#type").val();
        $("#login_form").validationEngine();
        if($("#login_form").validationEngine('validate'))
        {
           var postarray=$("#login_form").serializeArray();
           postarray['progressButton']=$(this);
           if(submit_type == "forgot_pwd")
           {
             my.routingAjax(my.config.siteUrl+"api/resetuserpwd",postarray,"",function(response){
             },false,false); 

         }
         else if(submit_type == "sign_in"){
            parenturl = $("#parenturl").val();
            my.routingAjax(my.config.siteUrl+"api/login",postarray,"",function(response){
                if(response.id > 0)
                {
                    //set all values for config object
                    config.loggedIn = true;
                    config.never_sell = response.never_sell;
                    config.userIdJs = response.id;         
                    
                    if (!response.artist) {
                        //console.log('hide upload');
                        config.usertype = 'user';   
                        //normal user, not artist or admin                        
                    } else config.usertype = 'artist';                       
                    $("body").data("avail_space",response.avail_space);
                    if (typeof(Storage) != "undefined" ) {
                        localStorage.setItem("localstorage_avail_space",response.avail_space);
                    }
                    if(parenturl != "" && parenturl != false)
                    {
                        location.href = parenturl;
                        return false;
                    }
                    else{
                     my.refreshLeftPanel(response);                      
                     my.redirectLoginAfter("Logged in successfully.");
                 }
                 if(response.role_added == "n")
                 {
                    $('<a href="'+my.config.siteUrl+'setup" id="setup" data-t="setup" class="popup"></a>').appendTo("body").click();                    
                }                                                               
            }
        },false,false);
}
}                       
});
},
initResetPassword:function(){
    $("body").on('show.bs.modal','.reset', function (e) {
        $("#reset_pwd_form").validationEngine("attach",{                
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){                                       
                    $("#reset_pwd_form").addClass("disabled");
                    var postarray=$("#reset_pwd_form").serializeArray();
                    postarray['progressButton'] = $("#submitresetpwdform");
                    my.routingAjax(my.config.siteUrl+"api/login",postarray,"",function(response){                   
                    },false,false); 
                }
            }                           
        });
    });
    $("body").on("click","#submitresetpwdform",function(e){
        e.preventDefault();
        if(!$("#reset_pwd_form").hasClass("disabled")){
            $("#reset_pwd_form").validationEngine();
            if($("#reset_pwd_form").validationEngine('validate'))
            {
                my.routingAjax(my.config.siteUrl+"api/login",$("#reset_pwd_form").serializeArray(),"",function(response){     
                    $("#reset_pwd_form")[0].reset();
                    my.ShowNotification("info","Info","Password changed successfully.");              
                },false,false); 
            }
        }               
    });  
    $("body").on("click","#submitresetform",function(e){
        e.preventDefault();
        if(!$("#forgot_pwd_form").hasClass("disabled")){
            $("#forgot_pwd_form").submit();                 
        }               
    });
},
initFollowing:function(){
    $("body").on("click","a[data-role=following]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            $(".right_box").html("");
            if(typeof(response)!='undefined'){
                $.template("#member-list",my.config.loaded_template['member-list']);
                $.template("#textfeed",my.config.loaded_template['textfeed']);
                $.template("#follow-member",my.config.loaded_template['follow-member']);
                $.template("#article",my.config.loaded_template['article']);
                $.template("#following_tab_render",my.config.loaded_template['following_tab_render']);                    
                $.template("#external_link",my.config.loaded_template['external_link']);
                $.template("#comment-list",my.config.loaded_template['follow_comment_row']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);  
                $.template("#contentPanel",my.config.loaded_template['following']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                setTimeout(function(){
                    my.initPlugin();                    
                },500);
                console.log('following ajax');
                my.removeIds();
            }
        },true,false);
});
    jQuery("body").on('click', ".repost_js",function(e) { 
        e.preventDefault();     
        var feedpostid = $(this).attr('data-feedpostid');
        var arr = {};
        arr["feedpostid"] = feedpostid; 
        mya = $(this);
        if(feedpostid > 0)
        {
            my.routingAjax(my.config.siteUrl+"api/feed_repost",arr,"",function(response){
                mya.text("Reposted");      
                my.ShowNotification("info","Success",response.msg);   
            },false,false); 
            return false;
        }
    }); 
    jQuery("body").on('click', ".delete_feed_js",function(e) {     
        e.preventDefault();   
        if(confirm("Are you sure want to delete feed?"))
        {
            disable = false;
            var id = $(this).attr("data-id");
            arr = {};
            arr["id"] = id;
            if(disable != true)
            {
                my.routingAjax(my.config.siteUrl+"api/feed_delete",arr,"",function(response){
                    $("#following_feed_"+id).fadeOut().remove();
                    App.ShowNotification("info","Success",response.msg); 
                    $cur_rec = $('#users_feed').size();
                    if($cur_rec < 2)
                    {
                        $("body").find(".load_more").click();
                        setTimeout(function(){                
                        if($(".videoPostPlay:visible").length > 0 ){
                            $(".videoPostPlay:visible").click();
                            $(".videoPostPlay:visible").hide();
                        }                        
                        }, 500);
                    }
                },false,false); 
            }
            return false;
        }
    });
    jQuery("body").on('click', ".edit_feed_js",function(e) {     
        e.preventDefault();   
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');            
        if ($(this).hasClass('editing_feed_js')){
            //confirm to edit
            var new_value = $("input[data-id="+id+"]").val();
            console.log('new value ', new_value, type);
            if (new_value){
                $("h3[data-id="+id+"]").html(new_value);
                arr = {};
                arr["id"] = id;
                arr["value"] = new_value;
                arr["type"] = type;
                
            if (type == 'text'){
                my.routingAjax(my.config.siteUrl+"api/feed_edit",arr,"",function(response){                    
                    App.ShowNotification("info","Success",response.msg);                                    
                },false,false);     
            } else {
                //video or soundcloud
                if (typeof App.data.video !== 'undefined'){
                    arr["iframe"] = App.data.video.videoIframe;
                    arr["image"] = App.data.video.images;
                    arr["description"] = App.data.video.description;
                    arr["canonicalUrl"] = App.data.video.canonicalUrl;
                    my.routingAjax(my.config.siteUrl+"api/feed_edit",arr,"",function(response){                    
                        App.ShowNotification("info","Success",response.msg);                     
                        delete App.data.video;
                    },false,false);  
                }           
             }            
             
            }
            $(this).removeClass('editing_feed_js');
            $(this).css('background-position', '0 bottom');                        
            $("h3[data-id="+id+"]").show();
            $("input[data-id="+id+"]").hide();
            
        } else {
            if (type != 'text'){
                //$("input[data-id="+id+"]").change(function() {
                $("input[data-id="+id+"]").on('change paste', function(e) {    
                    setTimeout(function () { 
                    var new_value = $("input[data-id="+id+"]").val();
                    var arr = {};
                    arr['text'] = new_value;
                    arr['crawlurl'] = new_value;
                    arr['imagequantity'] = -1;
                    console.log('new value video link ', new_value);
                    //crawlText();
                    //ajax to fetch link detail  api/linkcrawler
                    my.routingAjax(my.config.siteUrl+"api/linkcrawler",arr,"",function(response){                                                                                        
                        var iframe = response.videoIframe ? response.videoIframe: '';
                        if(iframe != ''){
                            App.data.video = response;
                            var temp = iframe.split('"');
                            var video_link = ''; 
                            for(var i=0; i < temp.length; i++){
                                if (/www.youtube.com\/embed/i.test(temp[i])) video_link = temp[i];
                                if (/soundcloud.com/i.test(temp[i])) video_link = temp[i];
                            }
                            if(video_link != ''){
                            var description = response.description;
                            var images = response.images;
                            console.log(iframe, description, images, video_link);          
                            //update iframe src
                            $("#videoPostPlay_"+id).next().attr('src', video_link);
                            $("figure[data-id="+id+"] img").attr('src', images);
                            $("article[data-id="+id+"] .previewSpanDescription").html(description);
                            }
                        }       
                    },false,false);     
                                                        
                    }, 200);
                });
            }                
            $(this).css('background-position', '0 top');
            $(this).addClass('editing_feed_js');
            console.log('Edit id ', id, type);
            $("h3[data-id="+id+"]").hide();
            $("input[data-id="+id+"]").show();
            
        }                               
    });   
    
    
    
    jQuery("body").on('click', "#submitfeedcomment",function(e) {     
        e.preventDefault();     
        disable = $(this).hasClass('disable');
        var id = $("#feedlogId").val();
        if(disable != true)
        {
            my.routingAjax(my.config.siteUrl+"api/feed_new_comment",$("#feed_new_comment_form").serializeArray(),"",function(response){
                if(response.id > 0)
                {
                    $("#feed_new_comment_form").removeClass("disabled");
                    $("#feed_com_cont_"+id).html("").fadeOut();
                    $.template("u_tmpl_container_e",my.config.loaded_template['follow_comment_row']);
                    $.tmpl("u_tmpl_container_e",response.feed_comments).appendTo("#feed_com_cont_"+id);     
                    $("#feed_com_cont_"+id).fadeIn();              

                }      
                App.ShowNotification("info","Success",response.msg); 
                App.initPlugin(true);      
            },false,false); 
        }
        return false;
    });
    //cancel reply feed
        jQuery("body").on('click', "#cancelreply",function(e) {     
        e.preventDefault();     
        disable = $(this).hasClass('disable');
        var id = $("#feedlogId").val();
        
        $("#feed_new_comment_form").removeClass("disabled");
        $("#feed_com_cont_"+id).html("").fadeOut();
        $.template("u_tmpl_container_e",my.config.loaded_template['follow_comment_row']);
        $.tmpl("u_tmpl_container_e",response.feed_comments).appendTo("#feed_com_cont_"+id);     
        $("#feed_com_cont_"+id).fadeIn();  
        console.log('Cancel reply', id);
        App.initPlugin(true);   
        return false;
    });
    
    
},
initEditProfile:function(){
    if ($('#description').length > 0) setTimeout(function(){
            $('#description').jqte({source:false, b: false, br: false, color: false, center: false, fsize: false, format: false,i: false, u:false,ol: false,ul: false, outdent: false, p: false,remove:false,rule:false, right:false,left:false, sup: false,strike:false, sub:false,indent:false,
        linktypes: ['Web Address']});
            if ($("#description").length > 0)   $('#description').jqte({source:false, b: false, br: false, color: false, center: false, fsize: false, format: false,i: false, u:false,ol: false,ul: false, outdent: false, p: false,remove:false,rule:false, right:false,left:false, sup: false,strike:false, sub:false,indent:false,
        linktypes: ['Web Address']});
        } ,200)        
    jQuery("body").on('show.bs.modal','.edit_profile', function (e) {
        //show Edit Profile popup modal 
        //select active country if the user has
        if ($('#description').length > 0) setTimeout(function(){
            $('#description').jqte({source:false, b: false, br: false, color: false, center: false, fsize: false, format: false,i: false, u:false,ol: false,ul: false, outdent: false, p: false,remove:false,rule:false, right:false,left:false, sup: false,strike:false, sub:false,indent:false,
        linktypes: ['Web Address']});           
        } ,200)
        
        
        $("#edit_profile_form").validationEngine("attach",{             
            onValidationComplete: function(form, status, json_response_from_server, options) {
                if(status==true){
                    
                    $("#edit_profile_form").addClass("disabled");                                                                                     
                    
                    //var postarray=$("#login_form").serializeArray();
                    var postarray = $("#edit_profile_form").serialize();                    
                    postarray['progressButton']=$(this);    

                    my.routingAjax(my.config.siteUrl+"api/editprofile",postarray,"",function(response){
                        if(response.success == "success")
                        {
                            $("#edit_profile_form").removeClass("disabled");
                            $(".edit_profile").modal("hide");
                            my.redirectLoginAfter(response.msg);    
                            App.ShowNotification("success","Success",response.msg);
                        }                       
                    },false,true);        


                }
            }                           
        }); 
    });
    //choose country , load state list
    $("body").on('change','#country',function (e) {
        e.preventDefault();
        $("#state, #city").find("option:gt(0)").remove();
        $("#state").find("option:first").text("Loading...");
        $.getJSON(my.config.siteUrl+"state_list", {
            country_id: $(this).val()
        }, function (json) {
            $("#state").find("option:first").text("Please select state.");
            for (var i = 0; i < json.length; i++) {
                $("<option/>").attr("value", json[i].location_id).text(json[i].name).appendTo($("#state"));
            }
        });
    });
    //choose state , load city list
    $("body").on('change','#state',function (e) {
        e.preventDefault();
        $("#city").find("option:gt(0)").remove();
        $("#city").find("option:first").text("Loading...");
        $.getJSON(my.config.siteUrl+"city_list", {
            state_id: $(this).val()
        }, function (json) {
            $("#city").find("option:first").text("Please select city.");
            for (var i = 0; i < json.length; i++) {
                $("<option/>").attr("value", json[i].location_id).text(json[i].name).appendTo($("#city"));
            }
        });
    });
    $("body").on("click","#edit_btn_editprofile",function(e){
        //andy update profile not use
        e.preventDefault();             
        //if(!$("#edit_profile_form").hasClass("disabled")){
            $("#edit_profile_form").submit();                   
            
        //}               
    });
},
initMasonary:function(){
    if($("#all-items-masonary").size() > 0){

        $container = $("#all-items-masonary");
        $container.imagesLoaded( function() {
            $container.masonry();
        });         
        my.scrollElem($("#main").find(".mcs_container"),".home_page");
        
    }   
},
initMenuLinks:function(){
  var documenturl=document.URL;
  var page_url=documenturl.substring(my.config.siteUrl.length);
  page_url=(page_url=="")?"home":page_url;
  var pagename="";
  switch(page_url){
    case "home":
    case "music":
    case "instrumental":
    case "license":
    pagename="[data-role='home']";
    break;
    case "liked":
    pagename="[data-role='liked']";
    break;
    case "browse":
    case "explore":
    pagename="[data-role='recommended']";
    break;
    case "sets":
    pagename=".playlist_popup";
    break;
    case "upload":
    pagename="[data-role='upload']";
    break;

}
$("#home_left_menu li a").removeClass("active");
if(pagename!=''){
    $("#home_left_menu li a"+pagename).addClass("active");
}

},
initHome:function()
{


 /* nav-menus*/


 $("body").on("click","a[data-role=home]",function(e){
    e.preventDefault();     
    my.routingAjax(my.config.siteUrl,{},'',function(response){                  
        if(typeof(response)!='undefined'){
            $('.modal').modal('hide').removeClass("show");  
            $(".right_box").html("");
            $.template("#contentPanel",my.config.loaded_template['headlines']);
            $.template("#trackRow",my.config.loaded_template['home_music_row']);
            $.template("#articleRow",my.config.loaded_template['home_article_row']);
            $.template("#tabRender",my.config.loaded_template['home_tab_render']);
            $.template("#loadMore",my.config.loaded_template['loadmore']);            
            $.tmpl('#contentPanel',response).appendTo(".right_box");
            my.initPlugin();
        }
    },true,false,"",true);
});
 $("body").on("click","a[data-role=home_music]",function(e){
    e.preventDefault();     
    my.routingAjax($(this).attr("href"),{},'',function(response){               
        if(typeof(response)!='undefined'){

            $("#all-items-masonary").html("");
            $.template("#recordsRow",my.config.loaded_template['home_music_row']);
            $.tmpl("#recordsRow",response.records).appendTo("#all-items-masonary");
            my.initPlugin();

        }
    },true,false,"",true);
});
 $("body").on("click","a[data-role=home_overview]",function(e){
    e.preventDefault();     
    my.routingAjax($(this).attr("href"),{},'',function(response){               
        if(typeof(response)!='undefined'){
            $("#all-items-masonary").html("");
            $.template("#recordsRow",my.config.loaded_template['home_music_row']);
            $.tmpl("#recordsRow",response.records).appendTo("#all-items-masonary");
            my.initPlugin();
        }
    },true,false,"",true);
}); 
 $("body").on("click","a[data-role=home_instrumental]",function(e){
    e.preventDefault();     
    my.routingAjax($(this).attr("href"),{},'',function(response){               
        if(typeof(response)!='undefined'){

            $("#all-items-masonary").html("");
            $.template("#recordsRow",my.config.loaded_template['home_music_row']);
            $.tmpl("#recordsRow",response.records).appendTo("#all-items-masonary");
            my.initPlugin();

        }
    },true,false,"",true);
});
 $("body").on("click","a[data-role=home_license]",function(e){
    e.preventDefault(); 
    my.routingAjax($(this).attr("href"),{},'',function(response){   
        if(typeof(response)!='undefined'){
            $("#all-items-masonary").html("");
            $.template("#recordsRow",my.config.loaded_template['home_music_row']);
            $.tmpl("#recordsRow",response.records).appendTo("#all-items-masonary");
            my.initPlugin();
        }
    },true,false,"",true);
}); 
},
initContent:function()
{
    $("body").on("click","a[data-role=content]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            if(typeof(response)!='undefined'){
                $(".right_box").html("");
                $.template("#contentPanel",my.config.loaded_template['content']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
            }
        },true,false,"",true);
    });
},
setBuyitems:function(){
    buy_items = [];
    $(".buy_row_li").each(function(){
     value = $(this).attr('data-id');
     if($(this).hasClass("active"))
     {
        buy_items.push(value);
    }
       /* else{
            buy_items.pop();
        } */
    });
},
api_cart:function(trackid,values,is_prev_flag,url){
    url = (typeof url != "undefined") ? url : my.config.siteUrl+"api/cart";
    if(typeof values == "undefined")
    {
        values = "";
    }
    if(typeof is_prev_flag == "undefined")
    {
        is_prev_flag = false;
    }
    my.routingAjax(url,{values:values,trackid:trackid,is_prev_flag:is_prev_flag},'',function(response){   
        if(response.album_fully_buyable == true)
        {
            html_ap = "";
            $.each(response.track_list, function(index, val) {
                html_ap += '<li><p>'+val.title+'</p></li>';
            });
            $("#licence_cart_cont").append(html_ap);
            $("#cart_total").html(response.album_price);
            $("#albumid").val(response.albumid);
            $("#cart_cont").removeClass('displaynone');
            $("#list_cont").html("This track can be buy as full album buy.");
        }
        else{
            my.setBuyitems();
            $("#list_cont").html("");   
            $.template("#trackDetail",my.config.loaded_template['buy_list_row']);
            $.tmpl("#trackDetail",response).appendTo("#list_cont");
            $("#next_btn").attr("data-url",response.nexturl);
            $("#prev_btn").attr("data-url",response.prevurl);
            $("#head_title").html(response.head_title);
            if(typeof response.orderid != "undefined" && response.orderid)
            {
                $("#orderid").val(response.orderid);
            }

            if(typeof response.cart_final_price != "undefined" && response.cart_final_price > 0)
            {
                $("#licence_cart_cont").html(response.cart_html);
                $("#cart_total").html(response.cart_final_price);
            }

            if(response.nexturl == false)
            {
                /*$("#next_btn").prop("disabled",true);*/
                $("#next_btn").hide();
            }else{
                /*$("#next_btn").prop("disabled",false);*/
                $("#next_btn").show();
            }

            if(response.prevurl == false)
            {
                /*$("#prev_btn").prop("disabled",true);*/
                $("#prev_btn").hide();
            }else{
                /*$("#prev_btn").prop("disabled",false);*/
                $("#prev_btn").show();

            }
        }
        if(response.display_cart_div == true)
        {
            $("#cart_cont").removeClass("displaynone");
        }
        
    },false,false,"",true);
},
stripeAlbumToken:function(res){
    var $input = $('<input type=hidden name=stripeToken />').val(res.id);
    var tokenId = $input.val();
    setTimeout(function(){
      $.ajax({
        url:config.siteUrl+'stripeoperations/albumpayment',
        cache: false,
        data:{
            token:tokenId,order_random_id:order_random_id 
        },
        type:'POST',
        dataType:'json',
        success: function(data) {
            if(data.status == "success")
            {
                my.ShowNotification("success","Success",data.msg);
                
                $("#buy_track_btn").click();
            }
        }
    }).done(function(data){            
    }).error(function(){
        my.ShowNotification("error","Error",data.msg);
    });
},500);

    $('form:first-child').append($input);
    return false;

},

stripeToken:function(res){
 var $input = $('<input type=hidden name=stripeToken />').val(res.id);
 var tokenId = $input.val();
 /* var email = res.email;*/
 //after successful payment
 setTimeout(function(){
  $.ajax({
    url:config.siteUrl+'stripeoperations/trackpayment',
    cache: false,
    data:{token:tokenId,order_random_id:order_random_id },
    type:'POST',
    dataType:'json',
    success: function(data) {
        if(data.status == "success")
        {
            //Successful payment after Stripe
            my.ShowNotification("success","Success",data.msg);
            //$("#buy_track_btn").click();
        }
    }
}).done(function(data){            
}).error(function(){
    my.ShowNotification("error","Error",data.msg);
});
},500);

 $('form:first-child').append($input);
 return false;


},
initTrackBuy:function(){
    trackid = $("#trackid").val();
    $( ".licence_type_sel_js").off( "click" );
    $("body").off("click",".buy_row_li");
    $("body").on('click','.buy_row_li',function(){
        $(".buy_row_li").not(this).removeClass("active");
        $(this).toggleClass('active');
    });

    $('body').on('click','.licence_type_sel_js', function(e) {
        e.preventDefault(); 
        var price = $(this).attr("data-price");
        var name = $(this).attr("data-name");
        var id = $(this).attr("data-id");
        var orderid = $("#orderid").val();
        
        click_url = my.config.siteUrl+"api/cartitem";
//        my.routingAjax(click_url,{id:id,trackid:trackid,price:price,orderid:orderid},'',function(response){
//           //Update cart after success message
//           console.log('Cart updated ', response);
//           if(response.status == "success"){            
//                console.log('Response: ', response);                            
//                my.ShowNotification("success","Success",response.msg);                 
//                //andy refresh the page to update the cart
//                setTimeout(function(){
//                          location.reload(); },1500);                                                               
//           }            
//           else {
//               my.ShowNotification("success","Success",response.status);               
//           }                   
//        },false,false,"",true);
        
         $.ajax({
              url: click_url,              
              type:'POST',
              cache: false,              
              data: {
                 id:id,
                  trackid:trackid,
                  price:price,
                  orderid:orderid,                 
              },                          
              dataType:'json',
              success: function(response) {
                console.log('Cart updated ', response);
           if(response.status == "success"){            
                console.log('Response: ', response);                            
                my.ShowNotification("success","Success",response.msg);                 
                //andy refresh the page to update the cart
//                setTimeout(function(){
//                          location.reload(); },1500);                                                               
                }            
                else {
                    my.ShowNotification("success","Success",response.status);               
                }    
              },
              error: function(e){
                console.log(e);
              },
                  
              });
        
        
        
        

            $cur_class = $(this).hasClass("active");
            $(this).toggleClass("active");
            $cart_total = $("#cart_total").html();
            $cart_total = parseFloat($cart_total);
            if($cur_class)
            {
                $("#subitem"+id).remove();
                new_price = $cart_total - parseFloat(price);
            }else{
                html = '<li id="subitem'+id+'"><p>'+name+'<span>$'+price+'</span></p></li>';
                $("#licence_cart_cont").append(html);
                new_price = $cart_total + parseFloat(price);
            }
            console.log('Update new price:',new_price);
            $("#cart_total").html(new_price);
       
    });

    if($(".buy_btn_js").size() > 0)
    {
        my.api_cart(trackid);    
    }
    
    $('body').on('click','#next_btn,#prev_btn',function(e){ 
        e.preventDefault(); 
        var flag = true;
        var is_prev_flag = false;
        if($(this).attr("id") == "next_btn")
        {
            my.setBuyitems(); 
            flag = false;              
        }        
        if($(this).attr("id") == "prev_btn")
        {
            is_prev_flag = true;
        }    
        if(flag == true || buy_items.length > 0)
        {
            /* var url = config.siteUrl+"api/cart";*/
            url = $(this).attr('data-url');
            if(typeof url == "undefined")
            {
                url = config.siteUrl+"api/cart";
            }
            values = "";;
            $(".buy_row_li.active").each(function() { 
             values += $(this).attr('data-id') + ",";
         });
            my.api_cart(trackid,values,is_prev_flag,url);
        }   
        else{
            my.ShowNotification("error","Error","Please select step.");
        }
    });


    $('body').on('click','#album_track_btn',function(e){ 
        e.preventDefault();
        var url = config.siteUrl+"api/albumbuy";
        albumid = $("#albumid").val();
        my.routingAjax(url,{albumid:albumid},'',function(response)
        {               
            if(typeof(response)!='undefined'){
                if(response.status == "success"){
                    $('.modal').modal('hide').removeClass("show");
                    order_random_id = response.order_random_id;
                    StripeCheckout.open({
                        key         :   config.stripe_public_key, 
                        address     :   false,
                        amount      :   response.total,
                        temp : "test",
                        currency    :   'usd',
                        name        :   my.config.siteName,
                        description :   response.title,
                        panelLabel  :   'Buy',
                        token       :   my.stripeAlbumToken
                    });
                    return false;
                }else{
                    my.ShowNotification(response.status,response.status,response.msg);                    
                }
            }
        },false,false,"",true);
});

    $('body').on('click','#buy_track_btn',function(e){ 
        e.preventDefault();
        var url = config.siteUrl+"api/buy";
        values = "";;
        $(".licence_type_sel_js.active").each(function() { 
         values += $(this).attr('data-id') + ",";
     });
        trackid = $("#trackid").val();
        my.routingAjax(url,{values:values,trackid:trackid},'',function(response){               
            if(typeof(response)!='undefined'){
                if(response.status == "success"){
                    $('.modal').modal('hide').removeClass("show");
                    order_random_id = response.order_random_id;
                    StripeCheckout.open({
                        key         :   config.stripe_public_key, 
                        address     :   false,
                        amount      :   response.total,
                        temp : "test",
                        currency    :   'usd',
                        name        :   my.config.siteName,
                        description :   response.title,
                        panelLabel  :   'Buy',
                        token       :   my.stripeToken
                    });
                    return false;
                }else{
                    my.ShowNotification(response.status,response.status,response.msg);
                    
                }
            }
        },false,false,"",true);
});

//Setup Paypal Express checkout In-contect Ajax


//    paypal.checkout.setup('8VZFVH6MTK2HJ', {
//      environment: 'sandbox',
//      container: 'checkout_paypal',
//      click: function (e) {
//        e.preventDefault();
//        paypal.checkout.initXO();
//        var url = config.siteUrl+"api/paypal/buy";
//        var action = $.post(url);
//
//        action.done(function (data) {
//          paypal.checkout.startFlow(data.token);
//        });
//
//        action.fail(function () {
//          paypal.checkout.closeFlow();
//        });
//      }
//    });


  $('body').on('click','#paypal_checkout',function(e){ 
        e.preventDefault();
        var url = config.siteUrl+"api/paypal/buy";
        values = "";;
        $(".licence_type_sel_js.active").each(function() { 
         values += $(this).attr('data-id') + ",";
     });
        trackid = $("#trackid").val();
        
        var url_current  = window.location.href;
        if(/\?/g.test(url_current)){
            var temp = url_current.split('?');
            url_current = temp[0];
        }
        my.routingAjax(url,{values:values,trackid:trackid, url: url_current},'',function(response){               
            if(typeof(response)!='undefined'){
                if(response.status == "success"){    
                    var environment = 'sandbox';
                    if (window.location.host != 'local.imusify.com' 
                            && window.location.host != 'beta.imusify.com' && window.location.host != 'dev.imusify.com') environment = 'production'
                    paypal.checkout.setup(response.merchant_account_id, {
                    environment: environment,
                    container: 'checkout_paypal',
                    });
                    
                    paypal.checkout.initXO();  
                 
                  //get token Start Paypal Window/Lightbox
                 var token = decodeURI(response.token);
                 console.log('Token: ', token);
                 paypal.checkout.startFlow(token);                                  
                 //Failed token                  
                  return false;
                }else{
                    //error
                    my.ShowNotification(response.status,response.status,response.msg);
                   // paypal.checkout.closeFlow();
                }
            }
        },false,false,"",true);
});


},
initTrackDetail:function(){
    //when load track detail page directly
    //console.log('2946 Init Track Detail');
    //the current link should have buy text
    var current_url = window.location.href;
    if(/buy/.test(current_url)) my.initTrackBuy();    
    $("body").on("click","a[data-role=trackdetail]",function(e){
        e.preventDefault();     
        init_trackcover = false;
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            console.log('2951 Ajax Track Detail', response);
            if(typeof(response)!='undefined'){
                $(".right_box").html("");   
                try {
                $.template("#trackDetail",my.config.loaded_template['trackdetail']);
                $.template("#followRow",my.config.loaded_template['profile_follow_row']);
                $.template("#folowingRow",my.config.loaded_template['profile_following_row']);
                $.template("#newComment",my.config.loaded_template['newcomment']);
                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#arrayData",my.config.loaded_template['comment_row']);
                $.template("#similarRow",my.config.loaded_template['similar_music_row']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);                                
                $.tmpl("#trackDetail",response).appendTo(".right_box");                
                my.initPlugin();
                my.hint(upload_similar_steps);
                my.initTrackBuy();
                my.trackcover_cropic();
                } catch(e){ }                                
                $( document ).ajaxComplete(function() {                    
                    console.log('Trigger Player 2969');  
                    //Register event for track cover cropic after 1 second
                    if (! init_trackcover) {
                        setTimeout(function(){
                            my.trackcover_cropic()},1000);                         
                        init_trackcover = true;
                    }
                    
                    if (! playback){
                        $('#play_track_detail').addClass("load_click");
                        $('#play_track_detail').trigger("click");
                        playback = true;                                                                       
                    }
                    //Register Share link popup
                    my.sharePopup();   
                });
                
            }
            
            
        },true,false,"",true);
    });

    $("body").on("click","a[data-role=trackdetail-comments],a[data-role=trackdetail-likes]", function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $("#tab_td_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                          

                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#arrayData",my.config.loaded_template[response.app_temp_name]);
                $.template("#loadMore",my.config.loaded_template['loadmore']);                          
                $.tmpl("#tabRender",response).appendTo("#tab_td_content .tab-pane");
                my.initPlugin();
                
            }
        },true,false,"",true);
    }); 

    //andy Track detail Load Buy tab   
    $("body").on("click","a[data-role=trackdetail-buy]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $("#tab_td_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                          
                $.template("#tabRender",my.config.loaded_template['buynow']);
                $.template("#licenceType",my.config.loaded_template["buynow_licence_row"]);
                $.tmpl("#tabRender",response).appendTo("#tab_td_content .tab-pane");  
                $( ".licence_type_sel_js").off( "click" );
                setTimeout(function(){
                        my.initTrackBuy()},300);
                //hide Previous and Next if got error for returned message
                if(typeof response.status !== 'undefined'){
                    
                    if (response.status == 'error'){
                        $("#buynow_main_cont .buy_btns").hide()
                       if(typeof response.msg !== 'undefined') $("#buynow_main_cont .space15").html(response.msg)                        
                    }
                }
            } 
        },true,false,"",true);
    }); 


    $("body").on("click","a[data-role=trackdetail-biography]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $("#tab_td_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                          
                $.template("#tabRender",my.config.loaded_template['biography']);
                $.tmpl("#tabRender",response).appendTo("#tab_td_content .tab-pane");
                my.initPlugin();
            }
        },true,false,"",true);
    }); 


    $('body').on('click','aside#songs-lists-box .slide-btn',function(e){    
        e.preventDefault();
        var $parent = $(this).parent('aside#songs-lists-box');
        $parent.toggleClass("open-slide");               
        var navState = $parent.hasClass('open-slide') ? "hide" : "show";
        $(this).attr("title", navState + " navigation");
        setTimeout(function(){
            console.log("timeout set");
            $('aside#songs-lists-box .slide-btn').toggleClass("active").toggleClass("aside#songs-lists-box .slide-btn");
        }, 200);                    
        if($('aside#songs-lists-box').css("right") == "0px")
        {
            $('#main-box').animate({
                marginRight: 0
            }, 500);
        }
        else 
        {
            $('#main-box').animate({
                marginRight: 260
            }, 500);
        }   
    });



},
initProfile:function(){
    $("body").on("click","a[data-role=profile]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            $(".right_box").html("");
            if(typeof(response)!='undefined')
            {
                if(response.user_type == "user")
                {

                    $.template("#followRow",my.config.loaded_template['profile_follow_row']);
                    $.template("#folowingRow",my.config.loaded_template['profile_following_row']);
                    $.template("#playsetRow",my.config.loaded_template['profile_playset']);                             
                    $.template("#tabRender",my.config.loaded_template['profile_tab_render']);                               
                    $.template("#profileHeader",my.config.loaded_template['profile_music_header']);
                    $.template("#arrayData",my.config.loaded_template['profile_recent_listen']);
                    $.template("#loadMore",my.config.loaded_template['loadmore']);
                    $.template("#contentPanel",my.config.loaded_template['profile']);
                    $.tmpl('#contentPanel',response).appendTo(".right_box");

                    $('#search_panel').modal('hide');

                    my.removeIds();
                    var croppic2 = new Croppic('edit_cover_img_modal', croppicHeaderOptions_ucover);    
                    $('#slider-container_prof').gallery();
                    
                    
                    
                }
                else if(response.user_type == "artist")
                {                           
                    $.template("#followRow",my.config.loaded_template['profile_follow_row']);
                    $.template("#folowingRow",my.config.loaded_template['profile_following_row']);
                    $.template("#tabRender",my.config.loaded_template['profile_tab_render']);                               
                    $.template("#profileHeader",my.config.loaded_template['profile_music_header']);
                    $.template("#arrayData",my.config.loaded_template['profile_artist_popular_songs']);
                    $.template("#loadMore",my.config.loaded_template['loadmore']);
                    $.template("#contentPanel",my.config.loaded_template['artist_profile']);
                    $.tmpl('#contentPanel',response).appendTo(".right_box");

                    my.removeIds();
                    var croppic2 = new Croppic('edit_cover_img_modal', croppicHeaderOptions_ucover);                                        
                    
                }
                //Profile loading ajax
                 //andy
                $( document ).ajaxComplete(function() {                    
                    console.log('Initialize Share Popup');                                       
                    my.sharePopup();   
                    //Hide Top playlist for Profile page
                    if ($("#slider-box2").length == 1){
                        if($("#slider-box2 #slider-container_prof .songs").length == 0) $("#slider-box2").hide();
                    } 
                });
                
            }
        },true,false,"",true);
});
    $("body").on("click","a[data-role=p-albums],a[data-role=p-followers],a[data-role=p-followings],a[data-role=p-feed]",function(e){
        e.preventDefault();
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");
                $("#tab_p_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);   
                $("#tab_p_content .tab-pane").html("");

                $.template("#tabRender",my.config.loaded_template['profile_tab_render']);
                $.template("#arrayData",my.config.loaded_template['profile_recent_listen']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl("#tabRender",response).appendTo("#tab_p_content .tab-pane"); 

                my.removeIds();


                
            }
        },true,false,"",true);
    }); 
    $("body").on("click","a[data-role=p-uploaded-songs],a[data-role=p-new-songs]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $("#tab_p_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                           

                $.template("#tabRender",my.config.loaded_template['profile_tab_render']);
                $.template("#profileHeader",my.config.loaded_template['profile_music_header']);
                $.template("#arrayData",my.config.loaded_template['profile_artist_popular_songs']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);                          
                $.tmpl("#tabRender",response).appendTo("#tab_p_content .tab-pane");
                my.removeIds();


            }
        },true,false,"",true);
    }); 
},
initMessage:function(){
    $("body").on("click",".members-list ul li",function(e){
        e.preventDefault();
        if(e.target.className == "noclickplay")
        {
            return false;
        }

        $(".members-list ul li").removeClass("active");
        $(this).addClass("active");
        $("#msg_to").val($(this).attr("data-profile-id"));  
        my.routingAjax($(this).attr("data-url"),{},'',function(response){       $(".msg-box").html("");
            if(typeof(response)!='undefined'){                      

                $.template("#chatBox",my.config.loaded_template['msg_chat_box']);
                $.tmpl('#chatBox',response.message).appendTo(".msg-box");
                my.addScroll();
                my.removeIds();
                temp = $(".msg-box .conversation-text").last();
                my.scrollElem($("#main").find(".mcs_container"),temp);


            }
        },true,false,"",true);      
    });
    $("body").on("click","#inbox div.message",function(e){
        e.preventDefault();     
        $("#msg_to").val($(this).attr("data-profile-id"));  
        my.routingAjax($(this).attr("data-url"),{conv_panel:true},'',function(response){                
            $(".right_box").html("");
            if(typeof(response)!='undefined'){                      
                $.template("#chatBox",my.config.loaded_template['msg_chat_box']);
                $.template("#memberRow",my.config.loaded_template['msg_member_row']);
                $.template("#memberList",my.config.loaded_template['msg_member_list']);
                $.template("#setTemplate",my.config.loaded_template['messages_list']);
                $.template("#contentPanel",my.config.loaded_template['msg_message']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.addScroll();
                my.removeIds();
            }
        },true,false,"",true);      
    });
    $("body").on("click","a[data-role=message]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{conv_panel:true},'',function(response){                
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");           
                $.template("#chatBox",my.config.loaded_template['msg_chat_box']);
                $.template("#memberRow",my.config.loaded_template['msg_member_row']);
                $.template("#memberList",my.config.loaded_template['msg_member_list']);
                $.template("#setTemplate",my.config.loaded_template['messages_list']);
                $.template("#contentPanel",my.config.loaded_template['msg_message']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.removeIds();
                my.initMessageCompose();
                
            }
        },true,false,"",true);
    });
    $("body").on("click","a[data-role=newmessage_fullpage]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{conv_panel:true},'',function(response){                
            if(typeof(response)!='undefined'){              
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");       
                $.template("#memberRow",my.config.loaded_template['msg_member_row']);
                $.template("#memberList",my.config.loaded_template['msg_member_list']);
                $.template("#setTemplate",my.config.loaded_template['new_message_main']);
                $.template("#contentPanel",my.config.loaded_template['msg_message']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.removeIds();
                my.initMessageCompose();

            }
        },true,false,"",true);
    });
    $("body").on("click","a[data-role=compose_halfview]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{conv_panel:true},'',function(response){                
            if(typeof(response)!='undefined'){                  
                $(".msg-box").html("");
                $.template("#setTemplate",my.config.loaded_template['new_message_main']);
                $.tmpl('#setTemplate').appendTo(".msg-box");
                my.removeIds();
            }
        },true,false,"",true);
    });
    $("#msg_to").val($(".members-list ul li.active").attr("data-profile-id"));
    $("body").off("submit","#form-msg");
    $("body").on("submit","#form-msg",function(event){
        event.preventDefault();
        jQuery.ajax({
            url: my.config.siteUrl+"api/message",
            type: "post",
            data: $(this).serialize(),
            success: function(data) {
                conversation_id = data.conversation_id;             
                var exist = $("#conversation_members").find("li[data-conversation-id='" + conversation_id + "']").size();
                if(exist > 0)
                {
                    $("#conversation_members li").filter(function() {
                        return $.trim($(this).attr("id")) === "member_conv_"+conversation_id;
                    }).prependTo("#conversation_members");
                    $("#member_conv_"+conversation_id).click();                 
                }
                else{   
                    $.template("#tmp",my.config.loaded_template['msg_member_row']);
                    $.tmpl('#tmp',data).prependTo("#conversation_members");
                    $("#member_conv_"+data.conversation_id).click();
                }

                $.template("#chatBox",my.config.loaded_template['msg_chat_box']);
                $.tmpl('#chatBox',data.messages).appendTo(".msg-box");
                my.removeIds();                 
                
                $("#msg_content").val(""); 
            },
            error:function(d){          
            }
        });
});
    jQuery("body").on('keyup',"#inputString",function(event) {
        my.initLookup($(this).val());
    }); 


    jQuery("body").on('click','.delete_conversation',function(e){
        e.preventDefault();


        if(confirm("Are you sure want to delete?"))
        {
            var arr = {};
            id = $(this).attr("data-id"); 
            arr["id"] = id;

            my.routingAjax($(this).attr("href"),arr,'',function(response){                
                if(typeof(response)!='undefined'){                  
                   if(response.status == "success")
                   {
                    my.ShowNotification("success","success","Conversation deleted successfully.");
                    $("#member_conv_"+id).remove();
                    $("#conversation_members li:first").click();  
                } 

            }
        },false,false);
        }

        

    });
    


},
hint:function(steps,skiponcookie){
    if(typeof steps != "undefined"){
        localstorage_hint_status = localStorage.getItem(config.controller+"_hint");

        if(localstorage_hint_status != "yes")
        {
            var enjoyhint_instance = new EnjoyHint({});
            var enjoyhint_script_steps = steps;    
            enjoyhint_instance.set(enjoyhint_script_steps);
            enjoyhint_instance.run();

            if (typeof(Storage) != "undefined" ) {
                localStorage.setItem(config.controller+"_hint","yes");
            }
        }

        

    }
    
},


initUpload:function(){

 if($(".trackdetail_page").size()>0)
 {
   my.hint(upload_similar_steps);
}  

 /*$('body').on('click','.licence_type_sel_js', function() {
    var price = $(this).attr("data-price");
    var name = $(this).attr("data-name");
    var id = $(this).attr("data-id");
    $cur_class = $(this).hasClass("active");
    $(this).toggleClass("active");
    $cart_total = $("#cart_total").html();
    $cart_total = parseInt($cart_total);
    if($cur_class)
    {
        $("#subitem"+id).remove();
        new_price = $cart_total - parseInt(price);
    }else{
        html = '<li id="subitem'+id+'"><p>'+name+'<span>$'+price+'</span></p></li>';
        $("#licence_cart_cont").append(html);
        new_price = $cart_total + parseInt(price);
    }
    $("#cart_total").html(new_price);
});*/

    $('body').on('click','.upload-contents #all-details .albums .album-details .album-tracks', function() {
        var trig = $(this).parents('.album-details');
        if ( trig.hasClass('active-arrow') ) {
            trig.parents(".albums-list").find(".song-info").slideToggle('slow');
            trig.removeClass('active-arrow');
        } else {
            $('.active-arrow').next('.upload-contents #all-details .albums .song-info').slideToggle('slow');
            $('.active-arrow').removeClass('active-arrow');
            my.scrollElem("",trig);
            trig.parents(".albums-list").find(".song-info").slideToggle('slow');
            trig.addClass('active-arrow');
        };
        return false;
    });

    $("body").on('click','.switch',function() {
        $(this).toggleClass('On').toggleClass('Off');
    }); 
    var fileupd = $("#fileupload");
    //$('#mainupload').fileupload('destroy');
    $('.vocal_switch').bootstrapSwitch('state', true);
    $("#drag_block").on('dragleave', function(e){
        $("#drag_block").hide();
    });
    var i=0;
    $('body').on('change', '#footer_upload_btn',function() {
        var totalFiles = $('#footer_upload_btn').get(0).files.length;
        var uploaded_files_size;
        var allowed_size;
        for (i = 0; i < files.length; i++)
        {
            uploaded_files_size += files[i].size;
        }

        return false;
    });
    //stop registration event for no Upload form
    //andy upload form
    if($("#mainupload").length > 0) {
    
    fn = fileupd.fileupload({
        maxChunkSize: 1048576, 
        url: my.config.siteUrl+'api/uploadfiles/',
        dropZone: $("#mainupload"),
        type:"POST",
        dragover: function (e, data) {    
            $("#drag_block").show();
        },
        maxFileSize:config.max_upload_file_size,
        //andy reduce audio format
        //acceptFileTypes: /(\.|\/)(mp3|mpeg|mpeg3|mpg|x-mpeg|ogg|wav|aiff|flac|alac|mp2|aac|amr|wma)$/i,  
        acceptFileTypes: /(\.|\/)(mp3|mpeg|mpeg3|mpg|wav|flac|alac|mp2)$/i,  
        add: function (e, data) {
                        
            console.log("Add => Type of uploaded file ", data.originalFiles[0]['type']);             
            //console.log("Original file ", data.originalFiles[0]);
            
            if(data.originalFiles[0]['type'] == 'audio/mp3' || data.originalFiles[0]['type'] == 'audio/mpeg') loadFromFile(data.originalFiles[0]);
                        
            var uploadErrors = [];
            var acceptFileTypes = /\/(mp3|mpeg|mpeg3|mpg|x-mpeg|ogg|wav|aiff|flac|alac|mp2|aac|amr|wma)$/i;
            if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                //uploadErrors.push('Please upload valid track.Accepted file types are .mp3,.wav,.ogg,.flac,.aac,.amr,.wma,.aiff');
                uploadErrors.push('Please upload valid track.Accepted file types are .mp3,.wav,.flac,.alac');
            }
            if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > config.max_upload_file_size) {
                uploadErrors.push('Filesize is too big');
            }

            var get_ext = data.originalFiles[0]['type'].split('/');
            upd_file_ext_ar = get_ext.reverse();
            upd_file_ext = upd_file_ext_ar[0].toLowerCase();

            if(uploadErrors.length > 0) {
                var notification = uploadErrors.join("\n");
                my.ShowNotification("info","Info",notification);
            } else {
                if(loaded == false)
                {   
                    loaded = true;          
                }
                var that = this;
                i++;
                $r = my.initRandomString();
                $("#drag_block").hide();
                var $this = $(this);
                var rows = $("#upload-files-list");
                var file=data.files[0];              
                var row =  my.config.loaded_template['upload_song_row'];
                localstorage_avail_space = localStorage.getItem("localstorage_avail_space");

                if(parseInt(file.size) > parseInt(localstorage_avail_space) && parseInt(localstorage_avail_space) != parseInt("-1"))
                {
                    my.ShowNotification("info","Info","Another upload is going on please wait till it completes...");
                    return false;
                }
                if(parseInt(localstorage_avail_space) != parseInt("-1"))
                {
                    localstorage_avail_space = localstorage_avail_space - file.size;
                    localStorage.setItem("localstorage_avail_space", localstorage_avail_space); 
                    cur_song_space = file.size;
                }
                current_avail_space = $("body").data("avail_space");

                if(parseInt(file.size) > parseInt(current_avail_space) && parseInt(current_avail_space) != parseInt("-1"))
                {
                    $("#u_container_"+$r).find(".close-icon a").click();
                    my.ShowNotification("info","Info","Not enought space in current plan.Please <a target='_blank' href='"+my.config.siteUrl+"membership'>upgrade</a> your plan to upload more files.");
                    return false;
                }
                else{
                    if(parseInt(current_avail_space) != parseInt("-1"))
                    {
                        new_avail_space = current_avail_space - file.size;
                        $("body").data("avail_space",new_avail_space);
                    }
                }
                var name=file.name;
                $tmp = {};
                $tmp["name"] =  file.name;
                $tmp["title"] =  file.name;
                $tmp["size"] =  my.initFormatFileSize(file.size);
                $tmp["userId"] =  config.userIdJs;
                $tmp["delid"] =  file.name;
                $tmp["img"] = my.config.AssetUrl+"images/";
                $tmp["i"] = i;
                $tmp["r"] = $r;
                $tmp["genre_list"] = response_genre;
                $tmp["sec_genre_list"] = response_sec_genre;
                $tmp["sound_like_list"] = response_soundlike;
                $tmp["moods_list"] = mood_list;     
                $tmp["album_list"] = response_album_list;
                $tmp["instuments_list"] = response_instruments_list;
                $tmp["licence_type_list"] = licence_types_list;
                $tmp["sell_type_list"] = sell_type_list;
                $tmp["np_type_list"] = np_type_list;
                $tmp["el_type_list"] = el_type_list;
                $tmp["track_upload_type_list"] = track_upload_type_list; 
                $tmp["trackuploadType"] = '';             
                $tmp["deltype"] = "fu";
                $tmp["action_class"] = "delete";
                $tmp["upd_file_ext"] = upd_file_ext;
                var d = new Date();
                var month = d.getMonth()+1;
                if(month <= 9)
                    month = '0'+month;
                var day= d.getDate();
                if(day <= 9)
                    day = '0'+day;
                $tmp["release_mm"] = month;
                $tmp["release_dd"] = day;
                $tmp["release_yy"] = d.getFullYear();
                $tmp["save_class"] = 'save_track';
                $tmp["display"] =  "";  
                $tmp["displaynone"] =  "displaynone";   
                $tmp["title"] =  "";    
                $tmp["description"] =  "";  
                $tmp["genreId"] =  "";  
                $tmp["track_image"] =  my.config.AssetUrl+"images/track-img.jpg";
                $tmp["display_checkbox_class"] =  "displaynone";
                var currentTime = new Date();
                $tmp["release_min_year"] =  currentTime.getFullYear() - 100;    
                $tmp["release_max_year"] =  currentTime.getFullYear();  

                if(jQuery.inArray(upd_file_ext,higher_type_list) >= 0)
                {
                 $tmp["higher_type"] =  "true"; 
             }
             else{
                 $tmp["lower_type"] =  "true"; 
             }

             upload_queue.push($r);
             main_q.push($r);
             $.template("u_tmpl_container",my.config.loaded_template['upload_song_row']);
             data.context = $.tmpl('u_tmpl_container',$tmp).appendTo("#song_appen_row");
             
             //andy upload image and crop it             
             croppicHeaderOptionstrack[$r] = {
                cropData:{
                    "r":$r
                },                      
                cropUrl:my.config.siteUrl+'crop/index/trackImg',    
                customUploadButtonId:'track_image_'+$r, 
                //andy
                acceptFileTypes: /(\.|\/)(jpeg|png|jpg|gif)$/i,  
                modal:true,
                processInline:true,
                loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
                onBeforeImgUpload: 	function(){                     
                    setTimeout(function(){                     
                        if ($(".cropImgWrapper").length == 0){
                        console.log('wrong type');                                               
                        my.ShowNotification("info","Info","Please only upload image");
                        setTimeout(function(){
                            $(".image_upd").show();
                            jQuery(".image_upd").show();
                        }, 1000);                                                
                        }                    
                    }, 1000);                                                
                        
                    
                },            
                onAfterImgCrop:function(){
                    if($(".croppedImg").length>0)
                    {
                        $("#track_d_image_"+$r).attr("src",$(".croppedImg").attr("src"));
                    }
                    console.log('Cropped image');
                },
                onError:function(errormessage){
                    console.log('onError: '+errormessage)
                },
                onReset:function(){ 
                    console.log('onReset') 
                },
            }
            var croppic_$r = new Croppic('croppic_'+$r, croppicHeaderOptionstrack[$r]);            
            //console.log('Crop ', croppic_$r, name)
            //croppic_$r.destroy(); 
            //add new valid extension for Image upload
             var id_crop = '#croppic_'+$r+' input';
             console.log('ID Crop ', id_crop, name);
             $(id_crop).attr("accept", "image/*");
             
            $("#dnd-upload-desc").hide();
            rows.show();            
            $('select.common').customSelect();
            rows = rows.append(row);
            data.i=i;
            
            console.log("Image cropped ", data.originalFiles[0]['type']);
            
            upload_queue[i]=data;
            ref_q[$r] = name;
            jqXHR[$r]=data.submit(); 

            jQuery("#sec_genner"+$r).select2({
                placeholder: "Select a Genre",
                allowClear: true
            });
            jQuery("#sound_like"+$r).select2({
                placeholder: "Select Sound Like",
                allowClear: true,
                minimumInputLength :3,
            });
            jQuery("#mood"+$r).select2({
                placeholder: "Select a mood",
                allowClear: true
            });
            jQuery("#instruments"+$r).select2({
                placeholder: "Select a Instrument",
                allowClear: true
            });
            my.scrollElem($("#main").find(".mcs_container"),"#u_container_"+$r);
            my.initSwitchApply();
            my.initCheckboxApplies();
            my.initEditableCheckbox();
            $("#u_container_"+$r).find(".song_det").click();
            $("#u_container_"+$r).find('#title').focus();
            
        }

    },
    progress: function (e, data) {
        var progress = Math.floor(data.loaded / data.total * 100);
        if (data.context) {
            data.context.find('.prog-bar')
            .attr('aria-valuenow', progress)
            .css(
                'width',
                progress + '%'
                );              
            data.context.find('.current_file_upd').html(my.initFormatFileSize(data.loaded));
        }
    },
    progressall: function (e, data) {
        $(".album-box").show();
        if (config.never_sell == 'y' && !add_save_never_sell){
            add_save_never_sell = true;
            var datar = $(".text-right .next-upload-btn").attr("data-r");
            $('<a href="javascript:void(0)" id="" class="pink-btn creat-btn save_track progress-button page1" '+ 
            'data-loading="Working.." data-finished="Saved" data-type="background-horizontal" ' +
            'data-r="'+datar+'">Save</a>').insertBefore(".text-right .next-upload-btn");
            $(".text-right .next-upload-btn").html("Sell My Music");            
            $(".text-right .next-upload-btn").css("margin-left", "10px");
            $(".text-right .next-upload-btn").css("margin-top","-24px");
        }
        var progress = Math.floor(data.loaded / data.total * 100);
        $(".pace").removeClass("hidden");
        $(".pace").find('.pace-activity').text(progress+"%");
        if(progress==100)
        {
            $(".pace").addClass("hidden");
        }
        
    },
    done: function (e, data) {
        console.log("Done => ");
        add_save_never_sell = false;        
        //andy edit never_sell
        //Edit buttons if config.never_sell 'y' (default is 'n' )
                      
        var result = $.parseJSON(data.result);
        console.log(result);
        
        console.log("Saved Q");
        console.log(saved_q);

        console.log("Completed Q");
        console.log(completed_q);

        my.ShowNotification("success","Success","file uploaded successfully.");
        if(typeof(result.files[0].error) =="undefined" || result.files[0].error=="")
        {
            console.log("Inside Done");
            //andy show form
            $(".album-box").removeClass("displaynone");
            $(".album-box").show();
            
            var name=result.files[0].name;
            var rn=result.files[0].r;           
            my.initRemoveItem(upload_queue,rn);
            if(upload_queue.length==0)
            {
                uploaded=true;
            }           
            completed_q[rn]=result;
            completed_f.push(rn);
            if(saved_q.hasOwnProperty(rn))
            {
                my.initSaveTrack(rn);
            }            
            var temp = name.replace(/.mp3/g, '');
            temp = 'Uploaded track '+ temp;
            //add Upload feed for this user Following page
            $.ajax({
              url:App.config.siteUrl+'api/feed_save',              
              type:'POST',
              cache: false,              
              data: {
                 text: temp,
                 feedType: 'text',
              },                          
              dataType:'json',
              success: function(data) {
                //console.log('Response Feed:', data);
              }});
                      
                      
            
        }
    },
    fail:function(e,data){
        add_save_never_sell = false;
        var temp = data.formData.r;
        $.ajax({
            url: my.config.siteUrl+'api/uploadfiles/?file='+data.files[0].name,
            dataType: 'json',
            type: 'DELETE',
            success: function(data) {
                $("#u_container_"+temp).fadeOut();
            }
        });
        $(".pace").addClass("hidden");
        if(data.context){
            row=data.context;
            if (data.errorThrown === "abort") {                 
                var er="Cancelled";
                row.find('.error-msg').text(er).attr("title",er);
                row.find('.status-col').find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-remove").removeClass("cancel")
            }
            else{
                var er="Failed";
                row.find('.error-msg').text(er).attr("title",er);
                row.find('.status-col').find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-remove").removeClass("cancel")
            }
            row.find('.upload-progress-bar')
            .attr('aria-valuenow', 0)
            .css(
                'width',
                0 + '%'
                );
        }
    }   
}).on('fileuploadchunksend', function (e, data) {
})
.on('fileuploadchunkdone', function (e, data) {
})
.on('fileuploadchunkfail', function (e, data) {
})
.on('fileuploadchunkalways', function (e, data) {
}).on('fileuploadsubmit', function (e, data) {
    data.formData = {
        'i': i,
        'r':$r
    }
});

    }//end

  

$("body").on("click",'.delete',function(e){
    $userId = ($(this).attr("data-userid") > 0) ? $(this).attr("data-userid") : config.userIdJs;
    $delId = $(this).attr("data-delid");
    $deltype = $(this).attr("data-deltype");
    $r = $(this).attr("data-r");
    $delete_type = $(this).attr("data-type");
    //var r = confirm("Are you sure?Want to delete this song?");
    //if(r == true)
    if(true)
    {        
            if($delete_type == "a")
            {
                var r = confirm("Are you sure?Want to delete this song?");
                if(r == true) {
                my.routingAjax(my.config.siteUrl+"album_delete",{'userId':$userId,'albumId':$delId,'deltype':$deltype},"",function(response){
                    if(response.status == "success")
                    {   
                        $(".album_row_"+$delId).fadeOut().remove();                    
                        my.ShowNotification("success","Success",response.msg);
                    }else{
                        my.ShowNotification("info","info",response.msg);
                    }   

                },false,false);
            }
        }
        else{
            if($deltype == "fu"){
                    my.routingAjax(my.config.siteUrl+"track_delete",{'userId':$userId,'trackId':$delId,'deltype':$deltype},"",function(response){
                    if(response.status == "success")
                    {   
                     if(typeof response.track_type != "undefined" && response.track_type == "n")
                     {

                        $("#u_container_"+$r).fadeOut();
                    }else{

                        $(".music_row_"+$delId).fadeOut().remove();
                    }
                    var current_space = $("body").data("avail_space");
                    var current_new_space = current_space +  response.track_space;
                    $("body").data("avail_space",current_new_space);
                    //Ensure album form hidden
                    $(".album_form").addClass("displaynone");
                    my.ShowNotification("success","Success",response.msg);
                }else{
                    my.ShowNotification("info","info",response.msg);
                }   

            },false,false); 
            
           } else {
               var r = confirm("Are you sure?Want to delete this song?");
                if(r == true) {
                      my.routingAjax(my.config.siteUrl+"track_delete",{'userId':$userId,'trackId':$delId,'deltype':$deltype},"",function(response){
                        if(response.status == "success")
                        {   
                         if(typeof response.track_type != "undefined" && response.track_type == "n")
                         {

                            $("#u_container_"+$r).fadeOut();
                        }else{

                            $(".music_row_"+$delId).fadeOut().remove();
                        }
                        var current_space = $("body").data("avail_space");
                        var current_new_space = current_space +  response.track_space;
                        $("body").data("avail_space",current_new_space);
                        //Ensure album form hidden
                        $(".album_form").addClass("displaynone");
                        my.ShowNotification("success","Success",response.msg);
                    }else{
                        my.ShowNotification("info","info",response.msg);
                    }   

                },false,false); 
                    
                }
                
           }
            

        }
    }else{
    }       
});
    
    $("body").on("change",'.genre_select',function(e){
        e.preventDefault();
        $r = $(this).attr("data-r");
        genreId = $("#genre"+$r).val();
        my.routingAjax(my.config.siteUrl+"secondary_genre_list",{'genreId':genreId},"",function(response){
            var html_d;
            if(response.sub_genres.length > 0){
                $("#sec_genner"+$r+".select2").select2('data', {});
                $.each(response.sub_genres, function(index, val) {
                    html_d += "<option id="+val.id+">"+val.genre+"</option>";
                });
                $("#sec_genner"+$r).html(html_d);
                $("#sec_genner"+$r).select2();     
            }
        },false,false);
    });
    
    /*$("body").on('click',"#mainupload",function(e){
        e.preventDefault();
        $("#footer_upload_btn").click();        
    });*/
    $("body").on('click',".song_det",function(e){
        e.preventDefault();
        if($(e.target).hasClass("delete_song") == false)
        {
            $(this).next().slideToggle(function(){
                $(this).parent().children().removeClass('displaynone');
                my.scrollElem($("#main"),$(this).parent());
            });
        }
    });
    jQuery("body").off("click",".delete_song");
    $("body").on('click',".delete_song",function(e){
        e.preventDefault();
        var fl=$(this).attr("data-r");
    });


    $("body").off('click',"#create_album_link");
    $("body").on('click',"#create_album_link",function(e){
        //add a new album
        e.preventDefault();
        croppicHeaderOptions_album  = {
            cropUrl:my.config.siteUrl+'crop/index/album_image',
            modal:true,
            imgEyecandyOpacity:0.8,
            processInline:true,
            loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
            customUploadButtonId:'album_image_upd',
            onBeforeImgUpload: function(){},
            onAfterImgUpload: function(){},
            onImgDrag: function(){},
            onImgZoom: function(){},
            onBeforeImgCrop: function(){},
            onAfterImgCrop:function(){
                if($(".croppedImg").length>0)
                {
                    $("#album_image_preview").attr("src",$(".croppedImg").attr("src"));
                }                   
            },
            onError:function(errormessage){ console.log('onError:'+errormessage) }
        }   
        var croppic2 = new Croppic('album_img_modal', croppicHeaderOptions_album);  
        $(".album_form").removeClass('displaynone');
        $(".album_form").fadeIn(function(){
            my.scrollElem($("#main").find(".mcs_container"),"#albumform");
            jQuery("#sec_tags").select2({
                placeholder: "Select a secondary Genre",
                allowClear: true
            });
        });

        my.initalbumSwitchApply();

    });
    jQuery("body").off("click",".cancel_album_btn");
    $("body").on('click',".cancel_album_btn",function(e){
        e.preventDefault();
       /* $r = $(this).attr("data-r");
        if(typeof $r != "undefined")
            {*/
                formid = $(this).parents(".album-form");
                formid[0].reset();
                formid.fadeOut();

            /*$("#albumform"+$r).find(".album-form")[0].reset();
            $("#albumform"+$r).fadeOut().remove();
            if($("#album_row_"+$r).find(".album-box").hasClass("displaynone"))
            {
                $("#album_row_"+$r).find(".album-box").removeClass("displaynone").fadeIn(); 
            }*/
        /*}else{                      
            $(".album_form").fadeOut();     
            
        }*/
    });
    jQuery("body").off("click",".cancel-btn");
    $("body").on('click',".cancel-btn",function(e){
        e.preventDefault();
        id = $(this).attr("data-r");
        if($(this).hasClass('edit_track'))
        {   
            $("#album_track_edit_"+id).fadeOut();           
        }
        else if($(this).hasClass('track_cancel'))
        {       
            $elem = $("#album_track_edit_"+id).find(".left");
            $("#u_container_"+id).remove();
            if($elem.size() >0)
            {
                $("#album_track_edit_"+id).children().removeClass('displaynone').fadeIn();
            }else{
                $("#music_row_"+id).find(".album-box").removeClass('displaynone').fadeIn(); 
            }
        }   
        else{
            $(".album_form").fadeOut();
        }
    });
    jQuery("body").off("click",".create_album_submit");
    $("body").on("click",".create_album_submit",function(e){
        e.preventDefault();             
        //andy create a new album
        $("#album_create_form").find(".select2-container").removeClass('validate[required]');
        var datamodal = $(this).attr('data-modal');
        var formid = $(this).parents('.album-form');
        var formid_post = $(this).parents('.album-form')[0];

        if(!formid.hasClass("disabled")){
            if(!formid.hasClass("val_applied"))
            {
                formid.validationEngine();
                formid.addClass("val_applied")
            }               
            if(formid.validationEngine('validate'))
            {
                //enable to add disable if want user not to click again
                //formid.addClass("disabled");
                
                //var postarray = $(formid_post).serializeArray();
                //data: $("form").serialize(),
                var postarray = $("#album_create_form").serialize();
                
                //postarray['progressButton'] = $(this);
                
                my.routingAjax(my.config.siteUrl+"create_album",postarray,"",function(response){
                    if(response)
                    {
                        formid.removeClass("disabled");
                        my.ShowNotification("success","Success","Album created successfully.");
                        formid_post.reset();
                        formid.find('#album_image_preview').attr("src","");
                        if(datamodal == "true")
                        {   

                        }else{
                            $("#albumform").fadeOut(); 

                            if($("#album_li_cont").hasClass("active"))
                            {
                                $.template("u_tmpl_container_e",my.config.loaded_template['albumListrow']);    
                                $.template("albumListrow",my.config.loaded_template['album_track_list_row']);  
                                $.tmpl("u_tmpl_container_e",response).prependTo("#my_album_row");
                            }
                        }
                    }                       
                },false,false,true);    
}               
}               
});
    jQuery("body").off("click",".album_image");
    jQuery("body").on("click",".album_image",function(e){
        e.preventDefault();
        $(this).parents("div").find(".album_image_upd").trigger("click");     
    }); 
    jQuery("body").off("click",".image_upd");
    jQuery("body").on("click",".image_upd",function(e){
        //no use Useless
        e.preventDefault();
        
        var target=$(this).attr("data-target");        
        //console.log('andy upload images clicked', target);        
        $("#"+target).click();  
        
    });
    jQuery("body").off("click",".track_image_upd");
    jQuery("body").on("change",".track_image_upd",function(e){
        var r=$(this).attr("data-i");
        var c=$(this).attr("data-c");
    });
    jQuery("body").off("click",".save_track");
    jQuery("body").on("click",".save_track",function(e){
        e.preventDefault();
        console.log("Save click");
        var r = $(this).attr("data-r");
        saved_q[r]=r;
        $("#track_form"+r).find(".select2-container").removeClass('validate[required]');
        $("#track_form"+r).validationEngine();
        if($("#track_form"+r).validationEngine('validate'))
        {
            console.log("Form validated");
            console.log(completed_q.hasOwnProperty(r));
            if(completed_q.hasOwnProperty(r))
            {       
                console.log("before save function called");
                if ( $(this).hasClass("page1") ) my.initSaveTrack(r,'no_change_never_sell');
                else my.initSaveTrack(r);
                my.initGeneral();
            }
            return false;   
        }
        else{
            console.log("Else 2 Form invalid!");
            
        }
    });
    jQuery("body").off("click",".edit_track");
    jQuery("body").on("click",".edit_track",function(e){                
        e.preventDefault();
        var r = $(this).attr("data-r");             
        $("#track_form"+r).find(".select2-container").removeClass('validate[required]');
        $("#track_form"+r).validationEngine();
        if($("#track_form"+r).validationEngine('validate'))
        {
            my.initSaveTrack(r,"edit");
            return false;
        }
        else{
        }
    });
    jQuery("body").off("click",".check_btn");
    jQuery("body").on("click",".check_btn",function(e){
        id = $(this).attr("data-id");
        val = $(this).attr("data-val");
        if($(this).hasClass('active'))
        {
            $("#music_vals_"+id).val(val);
        }else{
            $("#music_vals_"+id).val("");
        }
    });
    $(document).on('shown.bs.tab', '.u_song_detail a[role="tab"]', function (e) {
        /*my.scrollElem($("#main"),$(this).parents(".u_container"));*/
    })
    jQuery("body").on("click",".next-upload-btn",function(e){
        var r = $(this).attr("data-r");     
        $("a[data-target='#sell-your-music"+r+"']").tab("show");
        my.scrollElem("",$("#sell-your-music"+r));

    });
    jQuery("body").on("click",".prev-upload-btn",function(e){
        var r = $(this).attr("data-r");     
        $("a[data-target='#basic"+r+"']").tab("show");
        my.scrollElem("",$("#basic"+r));
    });

    jQuery("body").on("click",".next-album-btn",function(e){
        $("a[data-target='#sell-your-album']").tab("show");        
    });
    jQuery("body").on("click",".prev-album-btn",function(e){
        $("a[data-target='#album_basics_tab']").tab("show");  
    });


    jQuery("body").on("click",".next-album-modal-btn",function(e){
        $("a[data-target='#sell_your_album_modal_tab']").tab("show");        
    });
    jQuery("body").on("click",".prev-album-modal-btn",function(e){
        $("a[data-target='#album_basics_modal_tab']").tab("show");  
    });

    /* jQuery("body").on('show.bs.tab','a[data-target="#sell_your_album_modal_tab"]', function (e) {
         my.initalbumSwitchApply();
     });*/

    jQuery("body").on('show.bs.modal','.albumedit', function (e) {
        my.initalbummodalSwitchApply();
        $('select.common').customSelect();  
        
        jQuery("#modal_sec_tags").select2({
            placeholder: "Select a Genre",
            allowClear: true
        });

        croppicHeaderOptions_modal_album  = {
            cropUrl:my.config.siteUrl+'crop/index/album_image',
            modal:true,
            imgEyecandyOpacity:0.8,
            processInline:true,
            loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
            customUploadButtonId:'album_image_modal_upd',
            onBeforeImgUpload: function(){

            },
            onAfterImgUpload: function(){},
            onImgDrag: function(){},
            onImgZoom: function(){},
            onBeforeImgCrop: function(){

            },
            onAfterImgCrop:function(){
                if($(".croppedImg").length>0)
                {
                    $("#album_image_modal_preview").attr("src",$(".croppedImg").attr("src"));
                }                   
            },
            onError:function(errormessage){ console.log('onError:'+errormessage) }
        }  
        var croppic2 = new Croppic('album_img_modal', croppicHeaderOptions_modal_album);        

    });

    jQuery("body").on('show.bs.modal','.trackedit', function (e) {
        //var $r = $("#r_val").val();
        
        var url = window.location.href;
        var temp = url.split('/');
        var track_id = temp[temp.length - 1];  
        var $r = track_id;    
        console.log('Track Edit');
        croppicHeaderOptionstrack[$r] = {
            cropData:{
                "r": $r
            },                      
            cropUrl:my.config.siteUrl+'crop/index/trackImg',    
            customUploadButtonId:'track_image_'+$r, 
            modal:true,
            processInline:true,
            loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
            onAfterImgCrop:function(){
                if($(".croppedImg").length>0)
                {
                    $("#track_d_image_"+$r).attr("src",$(".croppedImg").attr("src"));
                }   
            },
            onError:function(errormessage){
                console.log('onError:'+errormessage)
            }
        }
        var croppic = new Croppic('croppic_'+track_id, croppicHeaderOptionstrack[track_id]);
        

        $('select.common').customSelect();                          
        jQuery("#sec_genner").select2({
            placeholder: "Select a Genre",
            allowClear: true
        }); 
        jQuery("#sound_like").select2({
            placeholder: "Select Sound Like",
            allowClear: true,
            minimumInputLength :3,
        }); 
         jQuery("#mood"+$r).select2({
                placeholder: "Select a mood",
                allowClear: true
            });
            
        jQuery("#moods_list").select2({
            placeholder: "Select a mood",
            allowClear: true
        });
        jQuery("#instruments").select2({
            placeholder: "Select Instuments",
            allowClear: true
        });
        my.initCheckboxApplies();
        my.initEditableCheckbox(); 
        my.initSwitchApply();        
        my.initUploadDetails();
        
        
        setTimeout(function(e){            
            var i = 0;
            if(typeof ajax_popupdata.data_array !== 'undefined'){
                if (ajax_popupdata.data_array[i].id != track_id) i = i+1;   
                //Initialize data for Track detail
            var thumnail_link = ajax_popupdata.data_array[i].track_image;
            $(".u_song_detail  figure img").attr('src', thumnail_link);
            $(".u_song_detail #title").attr('value', ajax_popupdata.data_array[i].title);
            $(".u_song_detail input[name='dd']").attr('value', ajax_popupdata.data_array[i].release_dd);
            $(".u_song_detail input[name='mm']").attr('value', ajax_popupdata.data_array[i].release_mm);
            $(".u_song_detail input[name='yy']").attr('value', ajax_popupdata.data_array[i].release_yy);            
            $(".u_song_detail textarea[name='desc']").html(ajax_popupdata.data_array[i].description);
            $(".upload_item_box h1").html('Edit track - '+ajax_popupdata.data_array[i].title);
                                
            }
            
            
            
        }, 500)
        
        
        
    });
    

    $("body").on("click","a[data-role=upload]",function(e){
       e.preventDefault();     
       my.routingAjax($(this).attr("href"),{},'',function(response){   
        if(typeof(response)!='undefined'){
            $('.modal').modal('hide').removeClass("show");  
            $(".right_box").html("");
            $.template("#contentPanel",my.config.loaded_template['upload']);
            $.template("#uploadtabRender",my.config.loaded_template['uploadtabrender']);
            $.template("#arrayData",my.config.loaded_template['uploaded_song_row']);
            $.template("#loadMore",my.config.loaded_template['loadmore']);
            $.tmpl('#contentPanel',response).appendTo(".right_box");
            my.removeIds();
            my.initUploadDetails();
        }
    },true,false,"",true);
   });

    /*New custom */
    $("body").on("click","a[data-role=uploadaudio]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $("#tab_content .tab-pane").html("");
                $("#tab_content .tab-pane").attr("id",response.tabid);
                $("#"+response.tabid).attr("class",response.class);
                $.template("#uploadtabRender",my.config.loaded_template['uploadtabrender']);
                $.template("#arrayData",my.config.loaded_template['uploaded_song_row']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl("#uploadtabRender",response).appendTo("#tab_content .tab-pane");   
                my.initPlugin();
            }
        },true,false,"",true); 
    });


    $("body").on("click","a[data-role=uploadalbum]",function(e){
       e.preventDefault();     
       my.routingAjax($(this).attr("href"),{},'',function(response){               
        if(typeof(response)!='undefined'){
            $('.modal').modal('hide').removeClass("show");  
            $("#tab_content .tab-pane").html("");
            $("#tab_content .tab-pane").attr("id",response.tabid);
            $("#"+response.tabid).addClass(response.class_nm);
            $.template("#uploadtabRender",my.config.loaded_template['uploadtabrender']);
            $.template("#arrayData",my.config.loaded_template['albumListrow']);
            $.template("#albumListrow",my.config.loaded_template['album_track_list_row']);
            $.template("#loadMore",my.config.loaded_template['loadmore']);
            $.tmpl("#uploadtabRender",response).appendTo("#tab_content .tab-pane");
            my.initPlugin();
        }
    },true,false,"",true);        
   });

    

    /*New custom ends*/

    $("body").on("click",".uploadmenu",function(e){
        e.preventDefault();
        if($(this).hasClass("music"))
        {
            if(!$(this).parent().hasClass("active"))
                $(".upload_audio").click();
            
            $("#footer_upload_btn").click(); 
        }
        else if($(this).hasClass("album")){
            $(".upload_album").click();
        }
    });

},
initLikedSongs:function(){
    $("body").on("click","a[data-role=liked]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");
                $.template("#songsRow",my.config.loaded_template['liked_song_row']);   
                $.template("#songslistRow",my.config.loaded_template['liked_track_list_row']);   
                $.template("#contentPanel",my.config.loaded_template['liked']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.removeIds();
            }
        },true,false,"",true);
    }); 
},

initArticledetail:function(){
 $("body").on("click","a[data-role=article_detail]",function(e){
    e.preventDefault();     
    my.routingAjax($(this).attr("href"),{},'',function(response){               
        if(typeof(response)!='undefined'){
         $('#popup').html("");
         $.template("#articledetail",my.config.loaded_template['article_detail']);
         $.template("#articleRow",my.config.loaded_template['article_detail_row']);
         $.tmpl('#articledetail',response).appendTo("#popup");
         $("#myModal").addClass("in show");
         my.initPlugin();
     }
 },true,false,"",true);
});    
},

initAbout:function(){

    /*$('#carousel-example-generic').on('slid.bs.carousel', function () {
        $holder = $( "ol li.active" );
        $holder.removeClass('active');
        var idx = $('div.active').index('div.item');
        $('ol.carousel-indicators li[data-slide-to="'+ idx+'"]').addClass('active');
    });

    $("body").on("click",'ol.carousel-indicators  li',function(){ 
        $('ol.carousel-indicators li.active').removeClass("active");
        $(this).addClass("active");
    });

    $('.carousel').on('slid', function() {
    var to_slide = $('.carousel-inner .item.active').attr('id');
    $('.carousel-indicators').children().removeClass('active');
    $('.carousel-indicators [data-slide-to=' + to_slide + ']').addClass('active');
});*/

    $("body").on("click","a[data-role=about]",function(e){
        e.preventDefault();     
        my.config.history_redirect_url = document.URL;
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
             $('#popup').html("");

             $.template("#aboutdet",my.config.loaded_template['about']);
             $.template("#teamRow",my.config.loaded_template['team_row']);
             $.template("#industry_professional_row",my.config.loaded_template['industry_professional_row']);           
             $.tmpl('#aboutdet',response).appendTo("#popup");
             $("#myModal").modal("show");
                //$("body").addClass("modal-open");
                my.initPlugin();

            }
        },true,false,"",true);
    });    
},
initBrowse:function(){
    $("body").on("click","a[data-role=recommended]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");
                $.template("#profileHeader",my.config.loaded_template['browse_music_header']);
                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#popUsers",my.config.loaded_template['browse_pop_artist']);
                $.template("#arrayData",my.config.loaded_template['browse_pop_new_songs']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.template("#contentPanel",my.config.loaded_template['browse_recommended']);
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.initPlugin();
                
            }
        },true,false,"",true);
    });
    $("body").on("click","a[data-role=popular-songs],a[data-role=new-songs]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $("#tab_content .tab-pane").html("");
                $("#tab_content .tab-pane").attr("id",response.tabid);

                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#arrayData",my.config.loaded_template['browse_pop_new_songs']);
                $.template("#profileHeader",my.config.loaded_template['browse_music_header']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl("#tabRender",response).appendTo("#tab_content .tab-pane");   
                my.initPlugin();
                
            }
        },true,false,"",true);
    }); 
    $("body").on("click","a[data-role=pop-artists],a[data-role=new-artists]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $("#tab_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                         
                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#arrayData",my.config.loaded_template['browse_popartist']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl("#tabRender",response).appendTo("#tab_content .tab-pane");   
                my.initPlugin();
            }
        },true,false,"",true);
    });
    $("body").on("click","a[data-role=pop-playlist],a[data-role=new-playlist]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{conv_panel:true},'',function(response){                
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $("#tab_content").find(".tab-pane").html("").addClass(response.class_nm).attr("id",response.tabid);                         
                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#arrayData",my.config.loaded_template['browse_popartist']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);
                $.tmpl("#tabRender",response).appendTo("#tab_content .tab-pane");   
                my.initPlugin();
            }
        },true,false,"",true);
    });     
},
initPlaylist:function()
{
    $("body").on("click",".playlist_popup",function(e){
        e.preventDefault();
        var href=$(this).attr("href");
        if($(".your-playlist_popup").hasClass('open')){
            $('.your-playlist_popup').removeClass("new");
            $("#playlist_text").html("Add new playlist");
            $(".create_playlist").removeClass("cancel");        
            /*$("#main_cont").removeClass('playset_position');*/
            $('.your-playlist_popup').removeClass('open').addClass('close');
            my.redirectLoginAfter();
            return true;                
        }
        my.config.history_redirect_url=document.URL;
        my.routingAjax(href,{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".your-playlist_popup").html("");


                $.template("#playlist_list",my.config.loaded_template['playlist_ls']);
                $.template("#playlistDetail",my.config.loaded_template['playlist_info']);
                $.template("#playlistsongsHeader",my.config.loaded_template['songs_header_four']);
                $.template("#songsData",my.config.loaded_template['songs_row_four_pllist']);
                $.template("#playlistContent",my.config.loaded_template['playlistContent']);
                $.tmpl('#playlistContent',response).appendTo(".your-playlist_popup");

                my.initPlugin();
                var w=0;
                $(".your-playlist_popup").removeClass('close').addClass('open');
                /*$("#main_cont").addClass('playset_position');*/
                my.initPlaysetMinHeight("#your-playlist","#playlist-song");


            }
        },true,false,"",true);
});
    $("body").on("click",".create_playlist",function(e){
        e.preventDefault();
        $("#croppic").empty();
        tgl_playlist = $(".your-playlist_popup");
        $("#playlist_nm").val('');
        $('#playlist_id').val('0');
        delete croppicHeaderOptions.loadPicture;
        if(tgl_playlist.hasClass("new"))
        {
            tgl_playlist.removeClass("new");
            $("#playlist_text").html("Add new playlist");
            $(this).removeClass("cancel"); 
        }
        else
        {
            tgl_playlist.addClass("new");
            $("#playlist_text").html("Cancel");
            $(this).addClass("cancel");
            var croppic = new Croppic('croppic', croppicHeaderOptions);
        }       
    });
    $("body").on("click",".edit_playlist",function(e){
        e.preventDefault();
        $("#croppic").empty(); 
        plid= $(this).attr('data-plid');
        plname= $(this).attr('data-plname');
        plimg= $(this).attr('data-plimage');
        delete croppicHeaderOptions.loadPicture;
        croppicHeaderOptions.loadPicture=plimg;
        $("#playlist_nm").val(plname);
        $('#playlist_id').val(plid);
        tgl_playlist = $(".your-playlist_popup");
        if(tgl_playlist.hasClass("new"))
        {
            tgl_playlist.removeClass("new");
            $("#playlist_text").html("Add new playlist");
            $(this).removeClass("cancel"); 
        }
        else
        {
            tgl_playlist.addClass("new");
            $("#playlist_text").html("Cancel");
            $(this).addClass("cancel");
            var croppic = new Croppic('croppic', croppicHeaderOptions);
        }       
    });
    $("body").on("click","#create_btn",function(event){
        playlist_name = $("#playlist_nm").val();
        if(playlist_name == "" || playlist_name == "")
        {
            my.ShowNotification("error","error","Please insert playset name.");
        }else if(pl_pic_upd ==false)
        {   
            my.ShowNotification("error","error","Please upload playset photo.");
        }else{
            var arr = {};
            arr["name"] = playlist_name;    
            arr["randomnumber"] = pl_r_no;
            my.routingAjax(my.config.siteUrl+"create_playlist",arr,"",function(response){       
                if(response.success == "success")
                {
                    my.ShowNotification("success","success",response.msg);                  
                }
                else if(response.error == "error")                      
                {
                    my.ShowNotification("error","Error",response.msg);
                }
                $("#playlist_nm").val('');
                $(".cropControlRemoveCroppedImage").trigger("click");
                pl_pic_upd = false;
                
                my.ShowNotification("success","success",response.msg);
            },false,false); 
        }
    });
    $("body").on("click",".your-playlist_popup",function(event){        
        if(event.target.className=='your-playlist_popup' || event.target.className=='your-playlist_popup open' || event.target.className=='playlist-main')
        {
            $(".your-playlist_popup").removeClass("open");
            $(".your-playlist_popup").html(""); 
            my.redirectLoginAfter();        
        }
    });
    $("body").on("click","a[data-role=playlist-detail],div.active[data-role=playlist-detail]",function(e){
        e.preventDefault(); 
        my.config.history_redirect_url=document.URL;
        $(".playlist_tracks").fadeOut(function(){
            $("#pl_loading").removeClass('displaynone');
            $(".playlist_tracks").addClass('displaynone');
        });
        my.routingAjax($(this).attr("href"),{},'',function(response){   
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                

                if(!$(".your-playlist_popup").hasClass('open'))
                {
                    $.template("#playlist_list",my.config.loaded_template['playlist_ls']);
                    $.template("#playlistDetail",my.config.loaded_template['playlist_info']);
                    $.template("#playlistsongsHeader",my.config.loaded_template['songs_header_four']);
                    $.template("#songsData",my.config.loaded_template['songs_row_four_pllist']);
                    $.template("#playlistContent",my.config.loaded_template['playlistContent']);        
                    $.tmpl('#playlistContent',response).appendTo(".your-playlist_popup");
                    my.initPlugin();
                }
                else{
                    $(".playlist_tracks").html('');
                    $("#playlist_detail").html('');
                    $.template("#playlistDetail",my.config.loaded_template['playlist_info']);
                    $.tmpl('#playlistDetail',response).appendTo("#playlist_detail");
                    if(response.songs!=null){
                        $.template("#songsData",my.config.loaded_template['songs_row_four_pllist']);
                        $.tmpl('#songsData',response.songs).appendTo(".playlist_tracks");
                    }
                }

                my.removeIds();
                $(".playlist_tracks").fadeIn(function(){
                    $("#pl_loading").addClass('displaynone');
                    $(".playlist_tracks").removeClass('displaynone');
                });
                if(!$(".your-playlist_popup").hasClass('open'))
                {
                    $('.your-playlist_popup').off('webkitAnimationEnd oanimationend  msAnimationEnd animationend'); 
                    $(".your-playlist_popup").addClass('open');
                }
                var w=0;                    
                my.initPlaysetMinHeight("#your-playlist","#playlist-song");
            }
        },true,false,"",true);
});
},
initExplore:function(){ 
    my.initPlugin();
    $("body").on("click","a[data-role=explore]",function(e){
        e.preventDefault();     
        my.routingAjax($(this).attr("href"),{},'',function(response){               
            if(typeof(response)!='undefined'){
                $('.modal').modal('hide').removeClass("show");  
                $(".right_box").html("");                       
                $.template("#contentPanel",my.config.loaded_template['explore']);           
                $.template("#tagsManage",my.config.loaded_template['exploretags']);
                $.template("#tabRender",my.config.loaded_template['tab_render']);
                $.template("#profileHeader",my.config.loaded_template['browse_music_header']);
                $.template("#arrayData",my.config.loaded_template['browse_pop_new_songs']);
                $.template("#loadMore",my.config.loaded_template['loadmore']);          
                $.tmpl('#contentPanel',response).appendTo(".right_box");
                my.initPlugin();
            }
        },true,false,"",true);
    }); 
    $("body").on('change','.drop_search',function (e) {
        e.preventDefault();
        my.initSearchCondition()
        try {                                           
            var arr = {};
            arr["search_params"] = search_params;           
            if(search_params["d_genre_explore"] > 0)
                arr["type"] = "genre";
            my.initSearchResultRender(arr);
        } catch(e) {
            console.log("Error in redirection - " + e);
        }
    });
    $("body").on('click','.ptags',function (e) {
        e.preventDefault();
        genreId = $(this).attr("data-id");
        tagname =  $(this).text();
        if($(this).hasClass('genre'))
        {
            genretype = "genre";
            $("#select_tags_cont").val(genreId);
        }   
        else if($(this).hasClass('subgenre'))
        {
            genretype = "subgenre";
            var temp_val = $("#select_subtags_cont").val();
            if(temp_val != "")
                var new_val = temp_val+","+genreId;
            else
                var new_val = genreId;  
            $("#select_subtags_cont").val(new_val);
        }
        my.initSearchCondition();
        try {                                           
            var arr = {};
            arr["search_params"] = search_params;
            if(search_params["subgenre_explore_list"] != "")
            {
                genretype = "subgenre";
            }
            else if(search_params["genre_explore_list"] != "")
            {
                genretype = "subgenre";
            }
            else{
                genretype = "allgenre";
            }
            arr["extra"] = genretype;           
            my.initSearchResultRender(arr,"genre_search",genreId);
        } catch(e) {
            console.log("Error in redirection - " + e);
        }       
    });
    $("body").on('click','.selected_tag',function (e) {
        e.preventDefault();
        genreId = $(this).attr("data-id");
        tagname =  $(this).text();
        $selector_gen = $("#select_tags_cont");
        $selector_subgen = $("#select_subtags_cont");
        var s = $selector_gen.val();    
        if(s.indexOf(genreId) > -1)
        {
            $new_vl = my.initRemoveValue(s,genreId);
            $selector_gen.val($new_vl);
        }
        var s1 = $selector_subgen.val();    
        if(s1.indexOf(genreId) > -1)
        {
            $new_vl = my.initRemoveValue(s1,genreId);
            $selector_subgen.val($new_vl);  
        }       
        $(".tags_header_maintitle[data-id='" + genreId + "']").fadeOut().remove();
        my.initSearchCondition();
        try {                                           
            var arr = {};
            arr["search_params"] = search_params;
            if(search_params["subgenre_explore_list"] != "")
            {
                genretype = "subgenre";
            }
            else if(search_params["genre_explore_list"] != "")
            {
                genretype = "subgenre";
            }
            else{
                genretype = "allgenre";
            }
            arr["extra"] = genretype;           
            my.initSearchResultRender(arr,"genre_search",genreId,"no_append");
        } catch(e) {
            console.log("Error in redirection - " + e);
        }   
    });
},
initSubmitRoles:function(){

    $("body").on('click','.rolecheckbox',function(){
        $('#none_above').removeAttr('checked');       
    });

    $("body").on('click','#none_above',function(){
        $('.rolecheckbox').removeAttr('checked');       
    });

    $("body").on("click","#submitroles",function(e){
        e.preventDefault();
        $("#setup_form").validationEngine();
        if($("#setup_form").validationEngine('validate'))
        {
            
            //check whether the user is an artist or not
            var artist = true;
            var form_data = $("#setup_form").serializeArray();
            console.log('role setup', form_data);
            if (form_data.length == 1){
                if (form_data[0].name == "none_above") artist = false; //music lover
            } 
            else if (form_data.length > 0){
                var i = 0;
                for(i; i< form_data.length; i++){
                    if(form_data[i].value == 14 || form_data[i].value == 15 || form_data[i].value == 13 || form_data[i].value == 4
                || form_data[i].value == 23 || form_data[i].value == 38 || form_data[i].value == 16 || form_data[i].value == 36 || form_data[i].value == 17
                || form_data[i].value == 34 || form_data[i].value == 19 || form_data[i].value == 31 || form_data[i].value == 29 || form_data[i].value == 26 
                || form_data[i].value == 27) {
                        artist = true;
                        break;
                    } else artist = false;
                }                
            }
                                    
            my.routingAjax(my.config.siteUrl+"api/userrolesapi",$("#setup_form").serializeArray(),"",function(response){
                if(response)
                {                    
                    if (artist){                        
                        //$("#upload_page").show();
                        //show Upload page
                        $("#upload_page").click(); 
                        config.usertype = 'artist';
                        $('.modal').modal('hide').removeClass("show");
                    } else {
                        config.usertype = 'user';
                        $('.modal').modal('hide').removeClass("show");
                        my.redirectLoginAfter("Roles updated successfully.");  
                        //$("#upload_page").hide();
                    }
                    
                }                       
            },false,false);
        }
    });  
},
initPlugin:function($common){
    my.initMenuLinks();
    my.leftPanelWidthset();  
    if(typeof $common != "undefined" && $common == true)
    {

        my.removeIds();

        my.addScroll();

    }else{
        $('select.styled').customSelect();
        if($(".invite_page").size()>0){
            jQuery(".invite_email_tagmanage").tagsManager({
                delimiters: [9, 13, 44],
                backspace: [8],
                blinkBGColor_1: '#FFFF9C',
                blinkBGColor_2: '#CDE69C',        
                deleteTagsOnBackspace: true,
                tagsContainer: "#testcontainer",
                tagClass: '',
                validator: my.validate_email,
                onlyTagList: false
            });
            $(".invite_email_tagmanage").on('tm:spliced tm:popped', function (event, tag) {
                if($(".tags_cust").length == 0)
                {
                    $("#display_invited_cont").fadeOut().addClass("displaynone");;
                }
            });
        }   



        if($(".explore_page").size()>0)
        {
            $('.explore_loop_switch').bootstrapSwitch('state');
            $('.explore_loop_switch').on('switchChange.bootstrapSwitch', function (event, state) {              
            });
            $('.explore_vocal_switch').bootstrapSwitch('state');
            $('.explore_vocal_switch').on('switchChange.bootstrapSwitch', function (event, state) {             
            });
            $("#explore_duration").ionRangeSlider({
                min: 0,
                max: 5,
                from: 4.2,
                step: 0.1,
                onStart: function (data) {
                },
                onChange:function (data) {
                    my.initSearchCondition();   
                    try {                                           
                        var arr = {};
                        search_params["time_from"] = 0;
                        search_params["time_to"] = data.from;
                        arr["search_params"] = search_params;
                        my.initSearchResultRender(arr);
                    } catch(e) {
                        console.log("Error in redirection - " + e);
                    }
                },
                onFinish:function (data) {
                },
                onUpdate:function (data) {
                }
            });
            $("#explore_price_range").ionRangeSlider({
                type: "double",
                min: 0,
                max: 100,
                from: 15,
                to: 50,
                prefix: "$",
                onStart: function (data) {
                },
                onChange:function (data) {
                    my.initSearchCondition();       
                    try {                                           
                        var arr = {};
                        search_params["price_from"] = data.from;
                        search_params["price_to"] = data.to;
                        arr["search_params"] = search_params;
                        my.initSearchResultRender(arr);
                    } catch(e) {
                        console.log("Error in redirection - " + e);
                    }
                },
                onFinish:function (data) {
                },
                onUpdate:function (data) {
                }
            });
            jQuery("body").on('click', ".exploretags",function(e) {     
                e.preventDefault();     
                temp = $(this).hasClass('right');
                page = $(this).attr("data-page");
                disable = $(this).hasClass('disable');
                if(temp == true && page > 1 && disable != true)
                {
                    var startlimit = (page - 1)*10;
                    var arr = {};
                    arr["startlimit"] = startlimit;
                    my.routingAjax(my.config.siteUrl+"explore_search_tags",arr,"",function(response){           
                        if(response.success == "success")
                        {
                            if(response.explore_genres)
                            {
                                $("#exp_s_tag_container .item").removeClass('active');
                                $("#exp_s_tag_container").append("<div class='active item'><ul></ul></div>");
                                $.template("searchtags",my.config.loaded_template['exploretags']);                          
                                $.tmpl("searchtags",response).appendTo("#exp_s_tag_container .item.active ul");
                                if(page < response.last_page)
                                {
                                    $(".exploretags.right").attr("data-page",page+1);
                                    $(".exploretags.left").attr("data-page",page);
                                }   
                                else
                                    $(".exploretags.right").addClass('disable');    
                            }
                            else{
                            }                       
                        }
                        else                    
                        {
                        }
                    },false,false,"",true);
}
else
{
    if(page != "1")
    {
        var temp = $("#exp_s_tag_container .item.active");
        last_item = $("#exp_s_tag_container .item.active").prev();
        temp.removeClass('active');
        last_item.addClass('active');
        $(".exploretags.right").attr("data-page",page);
        $(".exploretags.left").attr("data-page",page-1);
        $(".exploretags.right").removeClass('disable');
    }
}       
return false;
});
}
if($(".playlist_page").size()>=1){
    var current = $('#slider-container .dg-wrapper .songs.current').index();
    $('#slider-container').gallery({
        current:current
    }); 
    $('#slider-container').find(".songs").eq(current).addClass('active')
    jQuery(".all_playlist nav .dg-next").on( 'click.gallery',function( e ) {        
        e.preventDefault();
        var $selector=$(".all_playlist");
        var total=$selector.find(".songs").length-1;
        var $elem = $selector.find(".songs.active");
        var index= $elem.index();
        $selector.find(".songs.active").removeClass('active');
        if(index==total){
            $selector.find(".songs").eq(0).addClass('active').click();
        }       
        else if(index<total){
            $elem.next().addClass('active').click();
        }
    });
    jQuery(".all_playlist nav .dg-prev").on( 'click.gallery', function( e ) {   
        e.preventDefault();
        var $selector=$(".all_playlist");
        var total=$selector.find(".songs").length-1;
        var $elem = $selector.find(".songs.active");
        var index= $elem.index();
        $selector.find(".songs.active").removeClass('active');
        if(index==0){
            $selector.find(".songs").eq(total).addClass('active').click();
        }
        else if(index<=total){
            $elem.prev().addClass('active').click();
        }
    });
}   


if($(".following_page").size()==1){    
    if($("#previewLoading_feed_post").size() == 0)
    {
        $('#feed_post').linkPreview();
        //click Play to show all videos and waveforms
        setTimeout(function(){
            //load all available videos and audios
            //hide all Play buttons
            $(".videoPostPlay").click();
            $(".videoPostPlay").hide();
        }, 500);
        
    }
    $('aside#following-aside #conts-box, #chat-msg-container .members-list, #chat-msg-container .members-list .other, #chat-msg-container .chat-box').css("height","100%")
    $("aside#following-aside #conts-box").mCustomScrollbar("update");
}  

if($(".about_page").size() == 1)
{

    $('#signdp-carousal').carousel();
}


my.removeIds();

/*my.initGenerateWaveform();*/
my.addScroll();

}
},
initSimplePopup:function(){
    $(".js-modal-close, .modal-overlay").click(function() {
        $(".modal-box, .modal-overlay").fadeOut(500, function() {
            $(".modal-overlay").remove();
        });
    });
    $(window).resize(function() {
        $(".modal-box").css({
            top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
            left: ($(window).width() - $(".modal-box").outerWidth()) / 2
        });
    });
    $(window).resize();
},
initFollowingfeed:function(){
},

trackcover_cropic:function(){
 croppicHeaderOptions_tcover = {
    cropData:{
        "trackId":$("#edit_trackcover_img").attr("data-tid")
    },          
    cropUrl:my.config.siteUrl+'crop/index/track_cover',
    modal:true,
    imgEyecandyOpacity:0.8,
    processInline:true,
    loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
    customUploadButtonId:'edit_trackcover_img',
    onBeforeImgUpload: function(){},
    onAfterImgUpload: function(){},
    onImgDrag: function(){},
    onImgZoom: function(){},
    onBeforeImgCrop: function(){},
    onAfterImgCrop:function(){
        if($(".croppedImg").length>0)
        {
            //$("#track_cover_img").attr("src",$(".croppedImg").attr("src"));
            var link = $(".croppedImg").attr("src");
            $("#track_cover_img").css("background-image",'url(' + link + ')');
        }
        my.ShowNotification("success","success","Cover image uploaded successfully.");
    },
    onError:function(errormessage){ console.log('onError:'+errormessage) }
}   
var croppic3 = new Croppic('edit_trackcover_img_modal', croppicHeaderOptions_tcover);
},  

initGeneral:function(){
    my.leftPanelWidthset();
    setInterval(function(){ my.leftPanelWidthset(); }, 3000);
    $("#myModal").on("hidden.bs.modal",function(){
        $('#myModal').removeData('bs.modal');
    });  

    jQuery("textarea[class*=expand]").TextAreaExpander();           
    $("body").on("click",".form-tabs li a",function(e){
        e.preventDefault();
        $(this).tab('show');
    });
    $(window).resize(function() {
        my.addScroll();
        if($(".home_page").size() > 0)
            my.leftPanelWidthset();
        my.initGenerateWaveform("","","true");
    });


     /*$("#volume").slider({
        range: "min",
        min: 0,
        max: 100,
        value: 60,
        slide: function( event, ui ) {
           $(this).find('.ui-slider-handle').text(ui.value);
        },
        create: function(event, ui) {
            var v=$(this).slider('value');
            $(this).find('.ui-slider-handle').text(v);
        }
    }); */

    $(window).on('beforeunload', function() {
        if(cur_song_space > 0)
        {
            localstorage_avail_space = localStorage.getItem("localstorage_avail_space");
            localstorage_avail_space = parseInt(localstorage_avail_space) + parseInt(cur_song_space);
            localStorage.setItem("localstorage_avail_space", localstorage_avail_space);              
        }  
        if (ref_q.length>0) {
            r_flag = true;
            return 'A file is being uploaded. If you leave or refresh the site, your upload will be lost. Continue?';
        }
    });
    $(window).on('unload', function() {
        if(ref_q.length>0){
            $.each(ref_q, function(id, data) {
                $.ajax({
                    url: my.config.siteUrl+'api/uploadfiles/?file='+data,
                    dataType: 'json',
                    type: 'DELETE',
                    success: function(data) {
                    }
                });
            });
            return false;       
        }    
    });  
    $(".right_box").off("scroll"); 
    $(".right_box").on("scroll",function(){
        //console.log($(".right_box").scrollTop());
        //console.log($(document).height() - $(window).height());

        if($(".right_box").scrollTop() == $(document).height() - $(window).height()) {
            $("body").find(".load_more").click();   
        }
    }); 
    $(document.body).on('show.bs.modal', function () {
        my.addScroll();     
    });
    $(document.body).on('hidden.bs.modal', function () {
    });
    $("body").on('click','.left_menu_set li',function(e){
        var id = $(this).find("a").attr("data-id");
        var temp_top  = e.currentTarget.offsetTop+e.currentTarget.offsetHeight;
        var temp_left  = e.currentTarget.offsetLeft;
        if(id == "notifications")
        {
            my.initNotificationRead();

        }
        if($("#"+id).hasClass('displaynone'))
        {
            $(".profile-infor-msg-box").addClass('displaynone');
            $("#"+id).css({'left':temp_left, 'top':temp_top,'z-index':125}).removeClass('displaynone').fadeIn(function(){
                $(".mCustomScrollbar").mCustomScrollbar("update");
            });

        }else{
            $("#"+id).css({'z-index':1}).addClass('displaynone');   
        }       
    });
    /*$("body").mouseup(function(e)
    {
        var subject = $(".popupmenu_custom"); 

        if(e.target.id != subject.attr('id') && !subject.has(e.target).length)
        {
            subject.fadeOut();
        }
    });*/
    switch(my.config.current_tm){
        case "sign_in":
        case "reset":
        case "sign_up":
        case "setup":
        case "sign_up_mail":
        case "edit_profile":
        case "invite":
        case "membership":
        case "membership_plan_purchase":        
        case "giftcoupon":  
        case "article":
        case "about":  
        case "albumedit":
        case "trackedit":        
        $("."+my.config.current_tm).modal("show");
        break;
    }
    if(my.config.notification != "" && my.config.notification != null)
    {
        my.ShowNotification("info","Info",my.config.notification);
    }
    $('#cleartoasts').click(function () {
        toastr.clear();
    });
    $("body").on('show.bs.modal','.modal', function (e) {
        $('.modal-backdrop').css({
            'position': 'relative'
        });
        $('.modal-backdrop').remove();      
    });
    $("body").on("click",".close_popup",function(e){
        e.preventDefault();
        $('.modal').modal('hide');
        my.redirectLoginAfter();
    });
    jQuery("body").off("click",".search_input_head");
    jQuery("body").on("click",".search_input_head",function(e){
        $(".search_panel").modal("show");
        setTimeout(function(){
            $("#search_input_box").focus();
        }, 150);
    });
    jQuery("#search_input_box").on("keyup", function(event){
        event.preventDefault(); 
        if(event.keyCode == 27)
        {
            jQuery(".close_search").trigger("click");
        }
        else{
            my.initPopupSearch();
        }
    });
    jQuery(document).on('keyup', function(event) {  
        if(event.keyCode == 39)
        {
            $(".sc-next").trigger('click');
            return;
        }
        if(event.keyCode == 37)
        {
            $(".sc-prev").trigger('click');
            return;
        }
        if(event.keyCode == 107 || (event.keyCode == 187 && event.shiftKey == true))
        {
            $("#footer_plus_icon").trigger('click');
            return;
        }
        if(event.keyCode == 109 || (event.keyCode == 189 && event.shiftKey == true))
        {
            $("#big-player .player-close-icon").trigger('click');
            return;
        }
        if(event.keyCode == 32)
        {   
            if(!$("input,textarea").is(":focus")){
                if($(".big_player").hasClass('playing'))
                    $("#big-player .sc-pause").trigger('click');
                else
                    $("#big-player .sc-play").trigger('click');
            }
            return;
        }
        if(event.keyCode >= 48 && event.keyCode <= 90 && event.ctrlKey == false && event.altKey == false && event.metaKey == false && event.shiftKey == false){
            if(!$(".modal").hasClass('in'))
            {
                if(!$('.search_panel').hasClass('in'))
                {
                    if(!$("input,textarea").is(":focus")){
                        $(".search_panel").modal("show");   
                        $("#search_input_box").focus();
                        setTimeout(function() { 
                            $("#search_input_box").focus();
                            sr_val = $("#search_input_box").val();
                            var keyval = String.fromCharCode(event.which).toLowerCase();
                            if(sr_val.length != "undefined" && sr_val.length > 0)
                                keyval = sr_val+keyval;                 
                            $("#search_input_box").val(keyval);
                            my.initPopupSearch();
                        }, 500);
                    }
                }
            }
        }       
    });
/*    $("body").on({
        mouseenter: function(){
            if(!$(this).hasClass('playing'))
            {
                $(this).find(".waveform_img_div").addClass('whitecanvas');
            }
        },
        mouseleave: function(){
            if(!$(this).hasClass('playing'))
            {
                $(this).find(".waveform_img_div").removeClass('whitecanvas');
            }
        }
    }, '.waveform-hover ul.songs-list');  */
    $("body").on("click",".left_panel a",function(event){   
        if($(".your-playlist_popup").hasClass('open')){
            $(".your-playlist_popup").removeClass("open");
            $(".your-playlist_popup").html("");                     
        }       
    });
    $("body").on("click","#btn_logout",function(e){
        e.preventDefault();     
        my.routingAjax(my.config.siteUrl+"ulogout","","",function(response){            
            my.refreshLeftPanel(response);  
            my.redirectLoginAfter("Logout Successfully.");      
        },false,false);             
    }); 
    $("body").on("keypress","form[data-role='submitenter']",function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            $(this).find(".progress-button").trigger("click");      
        }
    });
    jQuery("body").off("click",".follow_js");
    jQuery("body").on("click",".follow_js",function(e){
        e.preventDefault();
        if(my.config.loggedIn == true)
        {
            var that = jQuery(this);
            var toid = $(this).attr("data-toid");
            var onlyfollow = $(this).attr("data-onlyfollow");
            var refreshpanel = $(this).attr("data-refreshpanel");
            var arr = {};
            arr["toid"] = toid;     
            if(refreshpanel == "yes")
            {
                arr["refreshpanel"] = "yes";                     
            }
            var item = {toid: toid};
            var a={arr:arr};    
            my.routingAjax(my.config.siteUrl+"follow",arr,"",function(response){        
                console.log(response);  
                if(response.success == "success")
                {
                    if(onlyfollow == "yes")
                    {
                        $("#followsuggestion_"+toid).fadeOut();
                        if(refreshpanel == "yes")
                        {

                            $("#following_cont").html("");
                            $.template("u_tmpl_container_e",my.config.loaded_template['feed_follow_suggestion_row']);
                            //check if there is no data
                            if (response.data){
                            $.tmpl("u_tmpl_container_e",response.data).appendTo("#following_cont");  
                            }
                            
                        }
                    }else{
                        $("#prof_follow_btn").html("");
                        $.template("u_tmpl_container_e",my.config.loaded_template['profile_following_row']);
                        $.tmpl("u_tmpl_container_e",response.data).appendTo("#prof_follow_btn");
                        $("#folowingRow a").attr('data-toid', toid);
                    }                   
                    my.ShowNotification("success","success",response.msg);  
                }
                else if(response.error == "error")                      
                {
                    my.ShowNotification("error","Error",response.msg);
                }
                
            },false,false); 
}
else{
    my.ShowNotification("info","info","Kindly login to access this page.");
    $("#sign_in").click();  
}
return false;                   
});
    jQuery("body").off("click",".unfollow_js");
    jQuery("body").on("click",".unfollow_js",function(e){
        e.preventDefault();
        name = $(this).attr("data-name");
        //var confirm_alert = confirm("Are you sure you want to unfollow "+name);
        //if(confirm_alert == true)
        // {
            if(my.config.loggedIn == true)
            {
                var that = jQuery(this);
                var toid = $(this).attr("data-toid");
                var arr = {};
                arr["toid"] = toid;                     
                my.routingAjax(my.config.siteUrl+"unfollow",arr,"",function(response){      
                    console.log('ToID', toid);  
                    if(response.success == "success")
                    {
                        $("#prof_follow_btn").html("");
                        $.template("u_tmpl_container_e",my.config.loaded_template['profile_follow_row']);
                        $.tmpl("u_tmpl_container_e",response.data).appendTo("#prof_follow_btn");
                        $("#followRow a").attr('data-toid', toid);
                        my.ShowNotification("success","success",response.msg);
                        
                    }
                    else if(response.error == "error")                      
                    {
                        my.ShowNotification("error","Error",response.msg);
                    }
                },false,false);
            }else{
                my.ShowNotification("info","info","Kindly login to access this page.");
                $("#sign_in").click();
            }
//        }else{
//        }       
        return false;                   
    });
    jQuery("body").off("click",".like_js");
    jQuery("body").on("click",".like_js",function(e){
        e.preventDefault();
        myvar = this;
        if(my.config.loggedIn == true)
        {
            var trackId = $(this).attr("data-tid");
            var track_name = $(this).attr("data-tname");
            track_name = 'Liked track '+ track_name;
            var arr = {};
            arr["trackId"] = trackId;                        
            if(trackId > 0)
            {                
                my.routingAjax(my.config.siteUrl+"like_track",arr,"",function(response){                    
                    if(response.success == "success")
                    {
                        $(".like_track_counter").html(response.data.likes);                        
                        $(myvar).removeClass('like_js').addClass('dislike_js active');
                        $(myvar).parent().removeClass('like-icon').addClass('unlike-icon');
                        if($("#like").size() > 0)
                        {
                            $.template("u_tmpl_container_e",my.config.loaded_template['box3']);
                            $.tmpl("u_tmpl_container_e",response.data).prependTo("#like");                            
                        }
                        my.ShowNotification(response.msgtype,response.msgtitle,response.msg);
                        //add Like feed for this user Following page
                        $.ajax({
                          url:App.config.siteUrl+'api/feed_save',              
                          type:'POST',
                          cache: false,
                          //contentType: 'multipart/form-data',
                          //contentType:false,
                          //processData: false,
                          data: {
                             text: track_name,
                             feedType: 'text',
                          },                          
                          dataType:'json',
                          success: function(data) {
                            console.log('Response:', data);
                          }});
                    }
                    else if(response.error == "error")                      
                    {
                        my.ShowNotification("error","Error",response.msg);
                    }
                },false,false);         
    return false;       
}   
}else{
    my.ShowNotification("info","info","Kindly login to access this page.");
    $("#sign_in").click();
}
});
    jQuery("body").off("click",".dislike_js");
    jQuery("body").on("click",".dislike_js",function(e){
        e.preventDefault();         
        myvar = this;
        if(my.config.loggedIn == true)
        {   
            var trackId = $(this).attr("data-tid");
            var arr = {};
            arr["trackId"] = trackId;                    
            if(trackId > 0)
            {
                my.routingAjax(my.config.siteUrl+"dislike_track",arr,"",function(response){                 
                    if(response.success == "success")
                    {                       
                        $(".like_track_counter").html(response.data.likes);
                        $(myvar).removeClass('dislike_js active').addClass('like_js');
                        $(myvar).parent().removeClass('unlike-icon').addClass('like-icon');
                        if($("#like").size() > 0)
                        {
                            $(response.removeid).fadeOut().remove();    
                        }
                        my.ShowNotification(response.msgtype,response.msgtitle,response.msg);
                        
                    }
                    else if(response.error == "error")                      
                    {
                        my.ShowNotification("error","Error",response.msg);
                    }
                },false,false);         
                return false;       
            }   
        }                       
    });
    jQuery("body").off("click",".add_to_pl");
    jQuery("body").on("click",".add_to_pl",function(e){
        e.preventDefault();
        my.config.history_redirect_url = document.URL;
        if(my.config.loggedIn == true)
        {
            var trackid = $(this).attr("data-tid");
            var playlistid = $(this).attr("data-plid");
            var arr = {};
            arr["trackid"] = trackid;
            arr["playlistid"] = playlistid;
            console.log(arr);       
            my.routingAjax(my.config.siteUrl+"addtoplaylist",arr,"",function(response){     
                if(response.success == "success")
                {
                    my.ShowNotification("success","success",response.msg);
                    
                    $(".close_popup").trigger("click");
                }
                else if(response.error == "error")                      
                {
                    my.ShowNotification("error","Error",response.msg);
                    $(".close_popup").trigger("click");
                }
            },false,false);         
            return false;   
        }
    });
    $('#slider-container_prof').gallery();
    $("body").on('click','.cust-header .profile-box .profile-infor li a',function(){
        if(!$(this).next().hasClass('open'))
            $(".cust-header .profile-box .profile-infor li").find(".profile-infor-msg-box").css("display","none").removeClass('open');
        $(this).next().toggle().addClass('open');
    }); 
    $("body").on('click','#following_btn',function(){
        $('.following_menu').toggle();
    }); 
    $("body").on('click','span.close-btn',function(){
        /*$('.cust-header .profile-box #msg-box').hide();*/
        $(this).parents().find(".profile-infor-msg-box").hide();
    });
    $("body").on('click','footer .player-bg .file-lists a.plus-icon',function(){
        $("#big-player").addClass("big_player_topset");
        $('#big-player').animate({'top':'0px'},200);
        var h=$('#big-player').outerHeight();
        $(".right_box").animate({'top':h+'px'},200);
        
        if($("#big-player").hasClass("big_player_topset")){
          $(this).removeClass("icons plus-icon").addClass("player-close-icon");
      }
      $('#carousel-example-generic').carousel();
  });

    $("body").off('click','footer .player-bg .file-lists a.list-icon');
    $("body").on('click','footer .player-bg .file-lists a.list-icon',function(){
        $('#slide-toggle-box').slideToggle();
    });

    $('#slide-toggle-box').mouseleave(function () {
      $('footer .player-bg .file-lists a.list-icon').click();
  });

    $("body").on('click','#big-player .player-track .settings .right-part a.player-close-icon,.player-close-icon',function(){
        $("#big-player").removeClass("big_player_topset");
        $('#big-player').animate({'top':'-700px'},200);
        $(".right_box").animate({'top':'0px'},200);
        if(!$("#big-player").hasClass("big_player_topset")){
            $("footer .player-bg .file-lists a#footer_plus_icon").removeClass("player-close-icon").addClass("icons plus-icon");
        }
    });
    $('body').off('click','#your-playlist .info h3 span');
    $('body').on('click','#your-playlist .info h3 span',function(){
        $('#your-playlist .info h3 .items-box').toggle();
    });
    $('body').on('click','.menu .options',function(){
        $(this).find('.option-list').toggle();
    }); 
    $('#following_btn .tooltip_option').click(function(){
        $('#following_btn .tooltip_option .option-list').toggle();
    });
    $('#following_btn').click(function(){       
        $('#following_btn .tooltip_option .option-list').toggle();
        $(this).css({'display':'block'});       
    });
    $('body').on('click','.ul_li_active li',function(){
        $(".ul_li_active li").removeClass("active");
        $(this).addClass("active");
        my.scrollElem($("#main").find(".mcs_container"),$(this));
    }); 

    

    $('body').on('click','.ul_li_active_a li a',function(){
        $(".ul_li_active_a li a").removeClass("active");
        $(this).addClass("active");
    }); 
    $('body').on('click','#home_left_menu li a',function(){
        if(!$(this).hasClass('playlist_popup') && $("#home_left_menu li a.playlist_popup").hasClass('active')){
         $(".your-playlist_popup").removeClass("open");
         $(".your-playlist_popup").html("");
     }
     $("#home_left_menu li a").removeClass("active");
     $(this).addClass("active");
     $("#home_left_menu li a span").removeClass("active");
     $(this).find(".spanicon").addClass('active');
 });
    $('body').on('click','#uprofile_biotabs li a',function(){       
        clickdiv = $(this).attr("data-tab");
        $("#uprofile_biotabs li").removeClass("active");
        $(this).parent().addClass("active");
        $("#profile_right_panel .tab-pane").removeClass("in active");
        $("#"+clickdiv).addClass("in active");      
    });
    my.addScroll();
    $('body').on('click','.users_suggestions',function(){       
        name = $(this).find(".fromname").val();    
        $("#inputString").val(name);
        toId = $(this).find(".fromId").val();
        $("#msg_to").val(toId); 
        $('#suggestions').fadeOut();
        $("#msg_content").focus();
    });

    $("body").on('click','.notification_row_click',function(e){
        e.preventDefault();
        link = $(this).attr("data-link");
        role = $(this).attr("data-role");
        if(link != "" && role != "")
        {
            a_hmtl = "<a href='"+link+"' data-role='"+role+"' id='notification_link'></a>";
            $("body").append(a_hmtl).click().remove();            
        }
    });

//ajax setInterval to fetch Notification
//andy turn off due to development environment
/*
    setInterval(function() {
        if(my.config.loggedIn == true)
        {
            
            jQuery.ajax({
                url: my.config.siteUrl+"api/unread",
                success: function(data) {                       
                    if(jQuery.isEmptyObject(data.conversations) == false && data.conversations.length > 0)
                    {   
                        new_message_counter = (data.conversations.length > 0) ? data.conversations.length : 0;
                        if(new_message_counter > 0)
                            $(".inbox_msg_counter").removeClass('displaynone').html(new_message_counter)
                        $.each( data.conversations, function( key, value ) {
                            var exist = $("#message_conv_"+value.conversation_id).size();
                            if(exist > 0)
                            {
                                $("#inbox div.message").filter(function() {
                                    return $.trim($(this).attr("id")) === "message_conv_"+value.conversation_id;
                                }).prependTo("#inbox"); 
                                $("#message_conv_"+value.conversation_id).find(".content").html(value.content);
                            }
                            else{
                                $.template("#msgrow",my.config.loaded_template['message_row']);
                                $.tmpl('#msgrow',value).prependTo("#inbox");
                            }
                            if($("#chat-msg-container").size() > 0)
                            {
                                var exist = $("#conversation_members").find("li[data-conversation-id='" + value.conversation_id + "']").size();
                                console.log(exist);
                                if(exist > 0)
                                {
                                    $("#conversation_members li").filter(function() {
                                        return $.trim($(this).attr("id")) === "member_conv_"+value.conversation_id;
                                    }).prependTo("#conversation_members");
                                    $("#member_conv_"+value.conversation_id).find(".last_message").html(value.content);
                                }
                                else{   

                                    $.template("#tmp",my.config.loaded_template['msg_member_row']);
                                    $.tmpl('#tmp',value).prependTo("#conversation_members");

                                }
                            }
                        }); 
    
    $.template("#chatBox",my.config.loaded_template['msg_chat_box']);
    $.tmpl('#chatBox',data).appendTo(".msg-box");

    $("#msg_content").val("");  
}   
else{
    $(".inbox_msg_counter").html("0");
    $("#inbox").html("");
}




},
error:function(d){                          
}
}); 
}
}, 20000);

     */    
//end of notification fetch

    jQuery("body").off("click",".remove_from_pl");
    jQuery("body").on("click",".remove_from_pl",function(e){
        e.preventDefault();
        if(my.config.loggedIn == true)
        {
            var trackid = $(this).attr("data-tid");
            var playlistid = $(this).attr("data-plid");
            var arr = {};
            arr["trackid"] = trackid;
            arr["playlistid"] = playlistid;
            my.routingAjax(my.config.siteUrl+"removefromplaylist",arr,"",function(response){        
                if(response.success == "success")
                {
                    $("#playlist_songs_"+playlistid+"_"+trackid).fadeOut('slow/400/fast', function() {
                    });
                    $("#songs_"+playlistid).html(response.songs_count);
                    my.ShowNotification("success","success",response.msg);
                    
                }
                else if(response.error == "error")              
                {
                    my.ShowNotification("error","Error",response.msg);
                }
            },false,false);         
            return false;   
        }
    });
    jQuery("body").off("click",".add_pl_popup");
    jQuery("body").on("click",".add_pl_popup",function(e){
        e.preventDefault();
        my.config.history_redirect_url = document.URL;
        var tid = $(this).attr("data-tid");
        var arr = {};
        arr["tid"] = tid;           
        var item = {tid: tid};
        var a={arr:arr};    
        my.routingAjax(my.config.siteUrl+"myplaylist",arr,"",function(response){        
            console.log(response);  
            if(response.success == "success")
            {   
                $.template("u_tmpl_container_e",my.config.loaded_template['addplaylist_popup']);
                $.tmpl("u_tmpl_container_e",response).appendTo("#popup");
                $(".addtoplaylist").modal("show");
                $(".add_to_pl").attr("data-tid",tid);
            }
            else                    
            {
                console.log(response);
            }
        },false,false);             
        return false;                   
    }); 
    jQuery("body").off("click",".load_more");
    jQuery("body").on("click",".load_more",function(e){
        e.preventDefault(); 
        var cpage = $(this).attr("data-page");
        var url = $(this).attr("data-url");
        var template = $(this).attr("data-template");
        var load_extra_class = $(this).attr("data-load_extra_class");
        var container = $(this).attr("data-cont"); 
        my.routingAjax(my.config.siteUrl+url+"/"+cpage,"","",function(response){    
            if(response)
            {
                if(load_extra_class ==  "following_loadmore")
                {

                    $.template("#"+template,my.config.loaded_template[template]);        
                    $.template("#textfeed",my.config.loaded_template['textfeed']);
                    $.template("#external_link",my.config.loaded_template['external_link']);
                    $.template("#comment-list",my.config.loaded_template['follow_comment_row']);
                    $.tmpl("#"+template,response.feeds).appendTo(container);    
                    if(response.page > response.last_page)
                    {
                        $("body").find(".load_more_tmpl").remove(); 
                        my.ShowNotification("info","Info","There are no more records to display.");
                    }
                    else
                    {  
                        $(".load_more").attr("data-page",response.page);
                    } 
                    my.initPlugin();                                

                }   
                else if(load_extra_class ==  "upload_album_loadmore"){



                    $.template("#main"+template,my.config.loaded_template[template]);        
                    $.template("#albumListrow",my.config.loaded_template['album_track_list_row']);

                    $.tmpl("#main"+template,response.data).appendTo(container);    
                    if(response.page > response.last_page)
                    {
                        $("body").find(".load_more_tmpl").remove(); 
                        my.ShowNotification("info","Info","There are no more records to display.");
                    }
                    else
                    {  
                        $(".load_more").attr("data-page",response.page);
                    } 
                    my.initPlugin();                                


                }
                else{

                    $.template("u_tmpl_container_e",my.config.loaded_template[template]);
                    $.tmpl("u_tmpl_container_e",response.data).appendTo(container);                         
                    if(response.page > response.last_page)
                    {
                        $("body").find(".load_more_tmpl").remove(); 
                        my.ShowNotification("info","Info","There are no more records to display.");
                    }
                    else
                        $(".load_more").attr("data-page",response.page);
                    my.initPlugin();                                

                }
            }
            else                    
            {
                console.log(response);
            }
        },false,false);
    return false;                   
});

    pl_r_no = my.initRandomString();
    croppicHeaderOptions = {
        cropData:{
            "playlist_name":"",
            "playlist_id":"",
            "randomnumber" : pl_r_no
        },
        cropUrl:my.config.siteUrl+'crop',                       
        modal:false,
        processInline:true,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        onBeforeImgUpload: function(){              
        },
        onAfterImgUpload: function(){},
        onImgDrag: function(){},
        onImgZoom: function(){},
        onBeforeImgCrop: function(){},
        onAfterImgCrop:function(){
            $("#playlist_nm").val('');
            pl_pic_upd = true;
            
        },
        onError:function(errormessage){ console.log('onError:'+errormessage) }
    }   
    croppicHeaderOptions_ucover = {
        cropUrl:my.config.siteUrl+'crop/index/user_cover',
        modal:true,
        imgEyecandyOpacity:0.8,
        processInline:true,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        customUploadButtonId:'edit_cover_img',
        onBeforeImgUpload: function(){},
        onAfterImgUpload: function(){},
        onImgDrag: function(){},
        onImgZoom: function(){},
        onBeforeImgCrop: function(){},
        onAfterImgCrop:function(){
            if($(".croppedImg").length>0)
            {
                $("#p_cover_img").attr("src",$(".croppedImg").attr("src"));
                $("#cover_image_cont").css("background-image",'url('+$(".croppedImg").attr("src")+')');
            }
            my.ShowNotification("success","success","Cover image uploaded successfully.");
        },
        onError:function(errormessage){ console.log('onError:'+errormessage) }
    }   

    var croppic2 = new Croppic('edit_cover_img_modal', croppicHeaderOptions_ucover);
    
    my.trackcover_cropic();



    cropOpt_prof_main_pic = {
        cropData:{
            "userId":$("#user_prof_link").attr("data-uid")
        },          
        cropUrl:my.config.siteUrl+'crop/index/user_profile',    
        modal:true,
        imgEyecandyOpacity:0.8,
        processInline:true,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
        customUploadButtonId:'user_prof_link',
        onBeforeImgUpload: function(){},
        onAfterImgUpload: function(){},
        onImgDrag: function(){},
        onImgZoom: function(){},
        onBeforeImgCrop: function(){},
        onAfterImgCrop:function(){
            if($(".croppedImg").length>0)
            {
                $("#user_prof_img").attr("src",$(".croppedImg").attr("src"));
                $("#left_profile_image").attr("src",$(".croppedImg").attr("src"));                
            }
            my.ShowNotification("success","success","Profile image uploaded successfully.");                    
        },
        onError:function(errormessage){ console.log('onError:'+errormessage) }
    }   
    var croppic4 = new Croppic('edit_profile_img_modal', cropOpt_prof_main_pic);
    $("body").on("focus","input,select,textarea",function(e){
        var $this=$(this);
        $(".formError").hide();
        $(".error").removeClass("error");
        if($this.prev(".formError").length>0){
            $this.addClass("error");
            $this.prev(".formError").show();
        }       
    });
},  
addScroll:function(){
    var headerH = $(window).height();
    var otherH = 170;
    var headerV = 18;
    /* $(".footer-menu").height($(".footer_player").outerHeight(true));*/
    logo_height = $(".logo").outerHeight(true);
    profile_box = $(".profile-box").outerHeight(true);
    footer_menu = $(".footer-menu").outerHeight(true);
    $("#nav-menus").height(window_height - logo_height - profile_box - footer_menu);
    $(".mCustomScrollbar").mCustomScrollbar("update");

},
tof:function(a){
    return (typeof(a)=="undefined")?true:false;
},  
addProgressBarButton:function($func){
    var progress = 0;
    var $pb=$('.progress-button');
    if($pb.length>0 && loaded!==true){
        $pb.progressInitialize();
        $func();
        loaded=true;
    }
    $pb.progressTimed(2, function() {
        loaded=false;
    });
},
removeLoading:function(){
    var l=$("#loading_cont");
    if(l.is(":visible")){l.fadeOut("slow",function(){l.find(".progressbar").css('width',"0%")});};
},
removeIds:function($elm){
    if($(".tmpl").length>0 ){
        $(".tmpl").each(function(){
            if(typeof($(this).attr('id'))!='undefined' && $(this).attr("id")!='' && $("#"+ $(this).attr("id")).length>0){
                $(this).removeAttr('id');   
            } 
        });         
    } 
},
checkloginstatus:function(){
    if(my.config.loggedIn == true)
        return true;
    else 
        return false;
},
renderTemplate:function(t,flag,loading){
    if(typeof(loading)==="undefined" || loading==''){
        loading=true;
    }
    if(my.tof(my.config.loaded_template[t])) {  
        /*console.log(my.config.templates[t]);*/
        return $.get(my.config.ViewUrl+my.config.templates[t]+".html").done(function(d){ 
            my.config.loaded_template[t] = d;    


        })
        
    }
    else{
        return true;
    }
},
routing:function(title,url,data,callback_arg,start_callback,replaceState){
    var state = {
        sc:start_callback.toString(),
        ca:callback_arg.toString(),
        t:'',
        u:url,
        d:data,
        n:title
    };
    if (my.tof(replaceState) || replaceState == false) {
        history.pushState(state,title,url);
        my.config.stack[url]=state;
    } else {
        history.replaceState(state,title,url);
        my.config.stack[url]=state;                 
    }

},
routingAjax:function(url,data,callback_arg,start_callback,routing,replaceState,formpost,allowabort){
    var fp,cp,context;
    if(routing==true){
        my.routing(document.pg_title,url,data,callback_arg,start_callback,replaceState);
    }
    if(request_array.length > 0 && typeof allowabort != "undefined" && allowabort == true)
    {
        $.each(request_array, function(index, val) {
            val.abort();
        });                 
    }
    if (my.tof(replaceState) || replaceState == false) {
        if(typeof(data)=='object' && formpost==true){
            var a={};
            a.ajax=true;
            
            $.extend(data,a);                   
        } 
        else {

            data['ajax']=true;
        }
    }
    if(typeof(data)=='object' && formpost==true){
        context=(data.progressButton!= undefined)?data.progressButton:{};
        delete data.progressButton;
    }
    else{
        context=(data['progressButton']!= undefined)?data['progressButton']:{};
        delete data['progressButton'];
    }
    function e(error){
        if(error.statusText != "abort")
        {   
            var er=$.parseJSON(error.responseText);
            my.ShowNotification("error","Error",er.error);
        }
        $("#loading_cont .progressbar").css('width','100%').fadeOut();
    }
    function s(response){
        if(typeof response.pg_title != "undefined" && typeof response.pg_title != "")
        {
            document.title=response.pg_title;
        }
        if(typeof response.loggedIn != "undefined" && typeof response.loggedIn != "")
        {
            if(my.config.loggedIn != response.loggedin)
            {
                my.config.changedloggedin = true;
            }else{
                my.config.changedloggedin = false;
            }
            my.config.loggedIn = response.loggedin;
        }
        else{
            my.config.loggedIn = true;
        }          
        response['url']= my.config.siteUrl;
        response['img']= my.config.AssetUrl+"images/";
        response['extra']=(callback_arg!='')?$.parseJSON(callback_arg):'';
        if(routing==true){
            /*my.routing(response.title,url,data,callback_arg,start_callback,true);*/
        }
        if(typeof(start_callback)==="function"){
            start_callback(response);
        }
        else if(typeof(start_callback)==="string"){
            eval("("+start_callback+"(response))");                 
        }                                   
    }

    /*Pace.restart();*/

    if(typeof formpost != 'undefined' && formpost==true)
    {
        req = $.ajax({
            url: url,
            cache: false,
            context:context,
            beforeSend: function(xhr, settings) {
                if (settings.context != undefined && !jQuery.isEmptyObject(settings.context) && settings.context.hasClass('progress-button')) {
                    settings.context.button('loading');
                }
            },
            method: 'post',
            dataType: 'json',
            async: false,
            data: data,
            processData:false,
            contentType:false,
            success: function(response) {
                s(response);
            },
            error:function(error){
                e(error);   
            },
            complete: function() {
                if (typeof this.button != "undefined") {
                    this.button('reset');
                }
            }            
        });
    } else {    
        req = $.ajax({
            url: url,
            cache: false,
            context:context,
            method: 'post',
            dataType: 'json',
            async: true,
            data: data,
            beforeSend: function(xhr, settings) {
                if (settings.context != undefined && !jQuery.isEmptyObject(settings.context) && settings.context.hasClass('progress-button')) {
                    settings.context.button('loading');
                }
            },
            success: function(response) {
                s(response);
                if(typeof response.page_class != "undefined")
                {
                    $("#contentPanel").removeClass().addClass('right_box').addClass(response.page_class);
                }
            },
            error:function(error){
                console.log(error);
                e(error);   
            },
            complete: function() {
                if (typeof this.button != "undefined") {
                    this.button('reset');
                }
            }            
        });         
}
if(typeof allowabort != "undefined" && allowabort == true)
    request_array.push(req);
return req;
},
refreshLeftPanel:function(response){

    $.template("leftPanel",my.config.loaded_template['left_panel']);
    var w=$(".left_panel").width();
    $(".left_panel").animate({
        "left":-w
    },1000,function(){
        $(".left_panel").html("");
        $.when($.tmpl('leftPanel',response).appendTo(".left_panel")).then(function(){
            $(".left_panel").animate({
                "left":0
            },1000);
        });
    });                         

},
//after logging in successfully
redirectLoginAfter:function(msg){
    console.log('Redirect URL from previous link: ',my.config.history_redirect_url);
    var $notification = msg;
    e=my.config.stack[my.config.history_redirect_url];
    f=my.tof(e);
    var $ele=$( '[href="'+my.config.history_redirect_url+'"]:first');
    if(!f){
        if(typeof msg != "undefined")
            my.ShowNotification("success","Success",$notification);
        my.routingAjax(e.u,e.d,e.ca,e.sc,true);
        my.config.history_redirect_url=my.config.siteUrl;
    }
    else if($ele.length>0){
        $ele.click();
    }
    else
    {
        if(typeof msg != "undefined")
            my.ShowNotification("success","Success",$notification);
        $(".logo").find("a").click();
    }   
}

}
google.setOnLoadCallback(function()
{
    var cssObj = { 'box-shadow' : '#888 5px 10px 10px', 
    '-webkit-box-shadow' : '#888 5px 10px 10px', 
    '-moz-box-shadow' : '#888 5px 10px 10px'}; 
    $("#suggestions").css(cssObj);
    $("input").blur(function(){
        $('#suggestions').fadeOut();
    });
});
$(document).ready(function(){   
    App.init(config);   
    
    //update message for successful payment
    var queries = {};    

    $.each(document.location.search.substr(1).split('&'), function(c,q){
        var i = q.split('=');
       if(typeof i[1] !== 'undefined') queries[i[0].toString()] = i[1].toString();
    });
    //console.log(queries);
    if (queries){
        console.log('Queries: ',queries)
        if (queries.payment == 'success') App.ShowNotification("success","Success","Payment was successful")

        if (queries.cancel == 'success') App.ShowNotification("success","Success","Membership was canceled successful")
        if  (queries.payment && queries.payment != 'success') {
            App.ShowNotification("success","Success","You have purchased successfully")
            //update shopping cart with token ID            
        }
        if(queries.cancel && queries.cancel != 'success') {
            //cancel Purchase Order with Token ID
            App.ShowNotification("info","Info","Your transaction was cancelled")
        }
         if(queries.failed) {
            //Failed Purchase Order with Token ID Payment pedning
            App.ShowNotification("info","Info","The transaction was failed. Please try again")
        }
    }
    //Redirect guest to About page
    if (config.userIdJs == 0 ||config.userIdJs == 0){//not log in
        if (window.location.pathname == "/imusify/") {
            $(".heading li a[data-role=about]").click();   
        }        
    }
    //check whether the user type is an artist
    if (config.loggedIn == 'true' || config.loggedIn){
        $.ajax({
              url:config.siteUrl+'usertype_json',              
              type:'GET',
              cache: false,            
              contentType:false,
              processData: false,              
              dataType:'json',
              success: function(data) {    
                   // console.log('User JSON: ', data);
                  if (! data)  config.usertype = 'user';                                     
                  if(data.status == "success" && data.data == "artist")
                  {                                          
                      config.usertype = 'artist';
                  } else config.usertype = 'user';                                     
              },
              complete: function(data){
                   //console.log('User JSON 2: ', data);                 
              }        
          })
    }
     
        
    
    //    App.ShowNotification("success","Success",'')    
    
    /*
    $("body").on('click', "#home_left_menu li a",function(e) {
        var role = $(this).attr("data-role");
        console.log('click Home menu');
        if ( role != 'upload' ) {
          //  console.log('Not upload');
            //Deregister any upload event
            //$('#mainupload').fileupload('disable');
            $('#mainupload').fileupload('destroy');
            $('#fileupload').fileupload('destroy');
            //$('#mainupload').fileupload('destroy');
        }
    });
    */
    
    //Register upload file for Upload page
    $("body").on('click', "li #upload_page",function(e) {  
    console.log('Initiliaze Upload page');
    if (config.usertype == 'user'){        
       App.ShowNotification("info","Info","Please change roles to artists in order to upload your music");                    
       $("#home_left_menu li a:first").click(); 
       return false;
    }
    
        setTimeout(function(){                       
        $('#mainupload').fileupload('destroy');    
        setTimeout(function(){  
            if (! init_upload) {
                my.initUpload();
                init_upload = true;
            }  
        //my.initSwitchApply();
        //Register Image upload
        croppicHeaderOptions_album  = {
            cropUrl:my.config.siteUrl+'crop/index/album_image',
            modal:true,
            imgEyecandyOpacity:0.8,
            processInline:true,
            loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
            customUploadButtonId:'album_image_upd',
            onBeforeImgUpload: function(){},
            onAfterImgUpload: function(){},
            onImgDrag: function(){},
            onImgZoom: function(){},
            onBeforeImgCrop: function(){},
            onAfterImgCrop:function(){
                if($(".croppedImg").length>0)
                {
                    $("#album_image_preview").attr("src",$(".croppedImg").attr("src"));
                }                   
            },
            onError:function(errormessage){ console.log('onError:'+errormessage) }
        }   
        var croppic2 = new Croppic('album_img_modal', croppicHeaderOptions_album);  
        }, 1500);
        }, 300);
    });
    
    
    //Intialize Playlist    
    setTimeout(function(){
        createPlaylist();
    },500);
 
});

$(window).load(function(){
    $("#startup_progress").remove();
    //redirectLoginAfter();
});   

//MP3 meta data extraction
function loadUrl(url, callback, reader) {
    var startDate = new Date().getTime();
    ID3.loadTags(url, function() {
        var endDate = new Date().getTime();
        if (typeof console !== "undefined") console.log("Time: " + ((endDate-startDate)/1000)+"s");
        var tags = ID3.getAllTags(url);
        console.log('Tags ',tags);
        $(".album-form #title").val(tags.title);
        
        var description = 'Artist '+ tags.artist;
        //if(tags.album) description = description+' Album '+ tags.album
        if(tags.track) description = description+' Track number of album '+tags.track;
        if(tags.year) description = description+' Published year '+tags.year;
        $(".album-form #des").val(description);
                
//        $("artist").textContent = tags.artist || "";
//        $("title").textContent = tags.title || "";
//        $("album").textContent = tags.album || "";
//        $("artist").textContent = tags.artist || "";
//        $("year").textContent = tags.year || "";
//        $("comment").textContent = (tags.comment||{}).text || "";
//        $("genre").textContent = tags.genre || "";
//        $("track").textContent = tags.track || "";
//        $("lyrics").textContent = (tags.lyrics||{}).lyrics || "";
        if( "picture" in tags ) {
            var image = tags.picture;
            //console.log(JSON.stringify(image));
            var base64String = "";
            for (var i = 0; i < image.data.length; i++) {
                base64String += String.fromCharCode(image.data[i]);
            }
            var image_base64 = "data:" + image.format + ";base64," + window.btoa(base64String);
            //console.log('Image base64: ', image_base64);
            var full_image_format = image.format+ ";base64";
            var imagebase64 = window.btoa(base64String);
            //not show it until the image base64 is uploaded to the server
//            $(".album-form img").attr("src", image_base64); 
//            $(".album-form #cover_base64").val(image_base64); 
            //end of local thumbnail
            
            //var mediaBlob = base64ToBlob(imagebase64 ,image.format);
            var formData = new FormData();
            formData.append("img", imagebase64); 
            formData.append("imgType", image.format);             
                        
            $.ajax({
              url:config.siteUrl+'crop/index/trackImg',              
              type:'POST',
              cache: false,
              //contentType: 'multipart/form-data',
              contentType:false,
              processData: false,
//              data:{
//                  imgType: image.format, imgUrl: imagebase64,
//                  artwork: true
//              },
              data: formData,
              dataType:'json',
              success: function(data) {
                  //console.log(data);
                  if(data.status == "success")
                  {                      
                      $(".album-form img").attr("src", data.url);  
                      $(".album-form .cover_base64_uploaded").val(data.url);
                      console.log('Artwork inside track Uploaded');
                  }
                                     
              },
              done: function(data){
              
              }        
          })
            
            
            
//	    $("art").src = "data:" + image.format + ";base64," + window.btoa(base64String);
//	    $("art").style.display = "block";
	} else {
	    //$("art").style.display = "none";
	}
	if( callback ) { callback(); };
    },
    {tags: ["artist", "title", "album", "year", "comment", "track", "genre", "lyrics", "picture"],
     dataReader: reader});
}

function base64ToBlob(base64, mime) 
{
    mime = mime || '';
    var sliceSize = 1024;
    var byteChars = window.atob(base64);
    var byteArrays = [];

    for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        var slice = byteChars.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mime});
}

function loadFromFile(file) {
    var url = file.urn ||file.name;
    loadUrl(url, null, FileAPIReader(file));
}

function load(elem) {
    if (elem.id === "file") {
        loadFromFile(elem.files[0]);
    } 
}
//Facebook Login
function fblogin_func(){
    FB.XFBML.parse();
    console.log('FB Sign up');
    var facebook_id;
    FB.login(function(response) {
    if (response.authResponse) {
     console.log('Welcome!  Fetching your information.... ', response);
     facebook_id = response.authResponse.userID;
     FB.api('/me?fields=first_name,last_name,email,gender', function(response2) {
       //console.log('Good to see you, ' + response2.name + '.');
       console.log('Field fetched: ',response2);
       var email;
       if (typeof response2.email !== 'undefined') email = response2.email
       else email = 'N/A';
       var uname = 'fb'+ facebook_id;
       $.ajax({
            url:config.siteUrl+'api/signup',
            cache: false,
            data:{
                'fname':  response2.first_name, 'lname' : response2.last_name,
                'uname': uname, 'fbid': facebook_id,
                'email': email, 'password': 'N/A', 'facebook': true, 'gender' : response2.gender,
                'password': facebook_id,
            },
            type:'POST',
            dataType:'json',
            success: function(response) {                
                my.refreshLeftPanel(response);
                my.redirectLoginAfter("Registered Successfully.");  
                if(response.role_added == "n")
                {
                    $('<a href="'+my.config.siteUrl+'setup" id="setup" data-t="setup" class="popup"></a>').appendTo("body").click();                                    
                }   
                                               
            },
            done: function(response, status){
                console.log(response, status);                
                if (status == 404){
                    App.ShowNotification("info","Info","The email with this Facebook account is already in use. Please try again with other Facebook account.");                    
                }
            }        
        });
    
         
     });
    } else {
        console.log('User cancelled login or did not fully authorize.');
        App.ShowNotification("info","Info","Please log in Facebook to register");
    }
    }, {scope: 'email'});
    //});
}
//Login via facebook ID
function fbsignin_func(){
    //FB.XFBML.parse();
    //intializeFB();
    console.log('Log in');   
    checkLoginFB();            
}

//Reload FB sdk for AJAX call 
function intializeFB(){
 if(typeof(FB) !== "undefined"){
  delete FB;
 }
 $.getScript("http://connect.facebook.net/en_US/all.js#xfbml=1",      function () {
   FB.init({
     appId      : '356111844733367',
     cookie     : true,  // enable cookies to allow the server to access 
                         // the session
     xfbml      : true,  // parse social plugins on this page
     oauth      : true,
     status     : true,
     version    : 'v2.4' // use version 2.4
   });
      
 });
  
}

function checkLoginFB(){
     FB.login(function(response) {
            if (response.authResponse) {
                var facebook_id = response.authResponse.userID;
                var uname = 'fb'+ facebook_id;
                //console.log(response.authResponse);
                $.ajax({
                    url:config.siteUrl+'api/login',
                    cache: false,
                    data:{                
                        'fbid': facebook_id,                
                        'username': uname,
                    },
                    type:'POST',
                    dataType:'json',
                    success: function(response) {  
                        console.log(response);  
                         if(response.id > 0)
                            {
                                //set all values for config object
                                config.loggedIn = true;
                                config.never_sell = response.never_sell;
                                config.userIdJs = response.id;                    
                                //config.usertype = 'user';                    
                                $("body").data("avail_space",response.avail_space);
                                if (typeof(Storage) != "undefined" ) {
                                    localStorage.setItem("localstorage_avail_space",response.avail_space);
                                }

                                 my.refreshLeftPanel(response);                      
                                 my.redirectLoginAfter("Logged in successfully.");

                             if(response.role_added == "n")
                             {
                                $('<a href="'+my.config.siteUrl+'setup" id="setup" data-t="setup" class="popup"></a>').appendTo("body").click();                    
                            }                                                               
                        }
                    },
                    error: function(xhr, textStatus, error){
                        console.log(textStatus, error);                
                        if (error == 'Not Found'){
                            App.ShowNotification("info","Info","No Facebook account is found. Please try again with other Facebook account.");                    
                        }
                    }, 
                });

            } else{
                 App.ShowNotification("info","Info","Please log in Facebook to sign in");
                }    
            });
}

function initFacebooklogin(){    
    $('body').on('click','#fb_login',function (e) {
        fbsignin_func();
    });    
};
 //end of Facebook Login
 
 //Player UI update for Playlist
 
 
 function addTrackToPlaylist(track_link, name, artist,track_image){
     var temp_html = '<article class="tr_item" data-href="'+track_link+'" data-track="'+track_link+'"><a href="javascript:void(0)" class="blue-light-bg"><span>'+name+'</span> '+artist+'</a></article>';
     var sc_tracklist_temp = '<li><div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 item-box"><div class="songs"><figure class="loading"><div class="img"></div><img src="'
     +track_image+'" alt="'+name+'" title="'+name+'" class="img-responsive"></figure><div class="play-btns" data-href="'
     +track_link+'" data-track="'+track_link+'"><a class="pause-icon" data-href="'+track_link+'" data-track="'
     +track_link+'" href="javascript:void(0);"></a></div></div><article><h3>'+name+'</h3><span>a</span></article></div></li>';
     if ( $(".sc-queuelist .tr_item:last").length != 0) {
         $(".sc-queuelist .tr_item:last").after(temp_html);
        //$(".sc-trackslist li:last").after(sc_tracklist_temp);
     } else {
         $(".sc-queuelist").append(temp_html);
         //$(".sc-trackslist").append(sc_tracklist_temp);
     }
     sc_trackslist_current = $(".sc-trackslist").html();     
 }
 
 function removeDuplicateTrack(){
     $(".sc-queuelist .tr_item").each(function(){
        var temp_link = $(this).attr('data-href');
        if(typeof temp_link != 'undefined'){
            if ( $(".sc-queuelist .tr_item[data-href='"+temp_link+"']").length > 1) {
            $(".sc-queuelist .tr_item[data-href='"+temp_link+"']:last").remove();
            console.log('Remove redundant item ', temp_link);
            }        
        }
        
     });     
 }
 
 function createPlaylist(){
     var current_location = window.location.pathname;
     var type;
     switch(current_location){
             case "/imusify/":
                type = 'home';   
             break;
             case "/imusify/browse":
                 type = 'browse';
             break;
             case "/imusify/sets":
                 type = 'playlist';
             break;
             case "/imusify/liked":
                 type = 'favorite';
             break;             
             default:
                  var temp = current_location.split('/');
                  if (temp.length == 4)
                  type = 'trackdetail';
                  else type = false;
             }
         
      if (type !== false){
          $.ajax({
            url:App.config.siteUrl+'initial_playlist',              
            type:'GET',
            cache: false,              
            data: {
               current_link: current_location,
               type: type,
            },                          
            dataType:'json',
            success: function(data) {     
                 if (typeof data.data === 'undefined'){
                     console.log('Invalid data', data);
                 } else {
                    var tracks_array = data.data;    
                    tracks_array.forEach(function(x){
                        if (typeof x.trackLink === 'undefined' || typeof x.title === 'undefined' || typeof x.username === 'undefined') return;
                        addTrackToPlaylist(x.trackLink, x.title, x.username,'');                    
                    })
                 }                                       


             }});
      }
      
          
          
//     addTrackToPlaylist('http://localhost/imusify/a/test_27','Test 27','a','');
//     addTrackToPlaylist('http://localhost/imusify/a/test_29','Test 29','a','');
//     addTrackToPlaylist('http://localhost/imusify/a/test_26','Test 26','a','');
 }
 
 
 