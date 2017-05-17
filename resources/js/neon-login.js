/**
 *	Neon Login Script
 *
 *	Developed by Arlind Nushi - www.laborator.co
 */

var neonLogin = neonLogin || {};

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		neonLogin.$container = $("#form_login");
		neonLogin.$fp_container = $("#form_fp");
		neonLogin.$rp_container = $("#form_rp");
		
		// Login Form & Validation
		neonLogin.$container.validate({
			rules: {
				username: {
					required: true	
				},
				
				password: {
					required: true
				},
				
			},
			
			highlight: function(element){
				$(element).closest('.input-group').addClass('validate-has-error');
			},
			
			
			unhighlight: function(element)
			{
				$(element).closest('.input-group').removeClass('validate-has-error');
			}
			
		});
		
		neonLogin.$fp_container.validate({
			rules: {
				email: {
					required: true,
					email:true
				}
			},
			
			highlight: function(element){
				$(element).closest('.input-group').addClass('validate-has-error');
			},
			
			
			unhighlight: function(element)
			{
				$(element).closest('.input-group').removeClass('validate-has-error');
			}
			
		});
		
		
		neonLogin.$rp_container.validate({
			rules: {
				rst_password: {
					required: true
				},
				cm_rst_password:{
					required: true,
					equalTo:  "#rst_password"
				}
			},
			
			highlight: function(element){
				$(element).closest('.input-group').addClass('validate-has-error');
			},
			
			
			unhighlight: function(element)
			{
				$(element).closest('.input-group').removeClass('validate-has-error');
			}
			
		});
		
		
		
		$("#form_login").on("submit", function(event) 
		{			
					
					event.preventDefault();
 					console.log($(this).serialize());
					$.ajax({
						url: site_url+"api/login",
						type: "post",
						data: $(this).serialize(),
						success: function(d) {
							
							window.location.href = site_url+'admin/home';
						},
						error:function(d){
							
							var rs=$.parseJSON(d.responseText);
							$(".alert-danger").html(rs.error);
							$(".alert-danger").fadeIn().delay(4000).fadeOut();
							
						}
					});
		});
		
		$("#form_fp").on("submit", function(event) 
		{			
					
					event.preventDefault();
 
					$.ajax({
						url: site_url+"api/login",
						type: "post",
						data: $(this).serialize(),
						success: function(d) {
							$(".alert-success").html(d);
							$(".alert-success").show();
						},
						error:function(d){
							
							var rs=$.parseJSON(d.responseText);
							$(".alert-danger").html(rs.error);
							$(".alert-danger").fadeIn().delay(4000).fadeOut();
							
						}
					});
		});
		
		$("#form_rp").on("submit", function(event) 
		{			
					
					event.preventDefault();
 
					$.ajax({
						url: site_url+"api/login",
						type: "post",
						data: $(this).serialize(),
						success: function(d) {
							$(".alert-success").html(d);
							$(".alert-success").show();
							//window.location.href = site_url+'admin/home';
						},
						error:function(d){
							
							var rs=$.parseJSON(d.responseText);
							$(".alert").html(rs.error);
							//$(".alert").show();
							$(".alert").fadeIn().delay(4000).fadeOut();
							
						}
					});
		});
			
		$("#fp_link").click(function(){
			if(!$('#form_login').is(':visible'))
			{
				$("#form_fp").hide();
				$(this).html("Forgot your password?");
				$("#form_login").show();
			}
			else
			{	$("#form_login").hide();
				$(this).html("Login");
				$("#form_fp").show();
			}
		})
		
		// Lockscreen & Validation
		// Login Form Setup
		neonLogin.$body = $(".login-page");
		neonLogin.$login_progressbar_indicator = $(".login-progressbar-indicator h3");
		neonLogin.$login_progressbar = neonLogin.$body.find(".login-progressbar div");
		
		neonLogin.$login_progressbar_indicator.html('0%');
		
		if(neonLogin.$body.hasClass('login-form-fall'))
		{
			var focus_set = false;
			
			setTimeout(function(){ 
				neonLogin.$body.addClass('login-form-fall-init')
				
				setTimeout(function()
				{
					if( !focus_set)
					{
						neonLogin.$container.find('input:first').focus();
						focus_set = true;
					}
					
				}, 550);
				
			}, 0);
		}
		else
		{
			neonLogin.$container.find('input:first').focus();
		}
		
		// Focus Class
		neonLogin.$container.find('.form-control').each(function(i, el)
		{
			var $this = $(el),
				$group = $this.closest('.input-group');
			
			$this.prev('.input-group-addon').click(function()
			{
				$this.focus();
			});
			
			$this.on({
				focus: function()
				{
					$group.addClass('focused');
				},
				
				blur: function()
				{
					$group.removeClass('focused');
				}
			});
		});
		
		// Functions
		$.extend(neonLogin, {
			setPercentage: function(pct, callback)
			{
				pct = parseInt(pct / 100 * 100, 10) + '%';
				
				// Lockscreen
				if(is_lockscreen)
				{
					neonLogin.$lockscreen_progress_indicator.html(pct);
					
					var o = {
						pct: currentProgress
					};
					
					TweenMax.to(o, .7, {
						pct: parseInt(pct, 10),
						roundProps: ["pct"],
						ease: Sine.easeOut,
						onUpdate: function()
						{
							neonLogin.$lockscreen_progress_indicator.html(o.pct + '%');
							drawProgress(parseInt(o.pct, 10)/100);
						},
						onComplete: callback
					});	
					return;
				}
				
				// Normal Login
				neonLogin.$login_progressbar_indicator.html(pct);
				neonLogin.$login_progressbar.width(pct);
				
				var o = {
					pct: parseInt(neonLogin.$login_progressbar.width() / neonLogin.$login_progressbar.parent().width() * 100, 10)
				};
				
				TweenMax.to(o, .7, {
					pct: parseInt(pct, 10),
					roundProps: ["pct"],
					ease: Sine.easeOut,
					onUpdate: function()
					{
						neonLogin.$login_progressbar_indicator.html(o.pct + '%');
					},
					onComplete: callback
				});
			}
		});
	});
	
})(jQuery, window);