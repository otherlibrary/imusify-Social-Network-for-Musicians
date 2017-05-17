var croppicHeaderOptions_acover;
function initRandomString(){
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}
$(document).ready(function()
{	
	if($("#id").val() > 0)
	{	
		id = $("#id").val();
		$('#editor1').redactor({
				imageUpload: site_url+'api/article_image_upload',
				fileUpload: site_url+'api/article_file_upload',
				imageGetJson: site_url+"api/article_image_uploaded/"+id
		});
	}else{
		$('#editor1').redactor({
				imageUpload: site_url+'api/article_image_upload'				
		});
	}

	pl_no = initRandomString();
	croppicHeaderOptions_acover = {
		cropData:{
			"randomnumber" : pl_no
		},
		cropUrl:site_url+'crop/index/article_cover',
		modal:true,
		imgEyecandyOpacity:0.8,
		processInline:true,
		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
		customUploadButtonId:'article_image',
		onBeforeImgUpload: function(){},
		onAfterImgUpload: function(){},
		onImgDrag: function(){},
		onImgZoom: function(){},
		onBeforeImgCrop: function(){},
		onAfterImgCrop:function(){
			if($(".croppedImg").length>0)
			{
				$("#p_cover_img").attr("src",$(".croppedImg").attr("src"));
				$("#randomnumber").val(pl_no);
			}
			my.ShowNotification("success","success","Article image uploaded successfully.");
		},
		onError:function(errormessage){ console.log('onError:'+errormessage) }
	}	
	var croppic2 = new Croppic('article_image_modal', croppicHeaderOptions_acover);


});
