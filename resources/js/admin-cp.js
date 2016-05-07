$(document).ready(function(){
	$("body").on("click","#change_password",function(e){
		e.preventDefault();
		$("#form_cp").validationEngine();
		if($("#form_cp").validationEngine('validate'))
		{
			$.ajax({
				url: site_url+"api/cp",
				type: "post",
				data: $(form).serialize(),
				success: function(d) {
					//var rs=$.parseJSON(d.responseText);
					$(".alert-success").html(d.status);
					$(".alert-success").fadeIn().delay(4000).fadeOut();	
					location.href = site_url+"admin";	
				},
				error:function(d){
					var rs=$.parseJSON(d.responseText);
					$(".alert-danger").html(rs.status);
					$(".alert-danger").fadeIn().delay(4000).fadeOut();
				}
			});
		}                       
	});


	
});

