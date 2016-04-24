$(document).ready(function(){ 
	var dialog = $('#dialog');
	$('#login').click(function(){
		dialog.show();
		$('.dialog-title').text('登录');
		$('.dialog-form').attr('action','/Index_login');
	});
	$('.dialog-close').click(function(){
		dialog.hide();
	});
	$('#register').click(function(){
		dialog.show();
		$('.dialog-form').attr('action','/Index_register');
		$('.dialog-title').text('注册');
	})
}); 
