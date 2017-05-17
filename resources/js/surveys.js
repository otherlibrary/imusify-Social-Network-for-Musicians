$(document).ready(function()
{	
	 $('body').on('click','.my-form .add-box',function(){
        var n = $('.text-box').length + 1;
        if( 7 < n ) {
     	   	alert('Maximum 7 options allowed.');
        	return false;
    	}
        var box_html = $('<div class="form-group text-box"><label for="box' + n + '" class="col-sm-3 control-label">Option <span class="box-number">' + n + '</span></label><div class="col-sm-9"> <input type="text" name="options[]" value="" id="options' + n + '" /> <a href="Javascript:void(0)" class="remove-box">Remove</a></div></div>');
        box_html.hide();
        $('.my-form div.text-box:last').after(box_html);
        box_html.fadeIn('slow');
        return false;
    });

	 $('.my-form').on('click', '.remove-box', function(){
	    $(this).parent().parent().css( 'background-color', '#FF6C6C' );
	    $(this).parent().parent().fadeOut("slow", function() {
	        $(this).remove();
	        $('.box-number').each(function(index){
	            $(this).text( index + 1 );
	        });
	    });
	    return false;
	});
});
