$(document).ready(function() {
	var msgBtn = $("#sendBtn");
	
	//**********************发送消息******************
	msgBtn.bind('click', function(event){
		var msgData = $('.editMsg').val();
		$.ajax({
			url: 'index.php?chat_sendMsg',
			type: 'POST',
			contentType : 'application/json',
			data:JSON.stringify({'msg':msgData}),
			dataType: 'json',//服务器返回的数据类型  与下一个注释 二选一
			success:function(back){
				// back = JSON.parse(back);
				if(back.status == true){
					$('#chat_message ul').append('<li>时间:'+'<span>'+back.times+'</span> <p>'+back.msg+'</p></li>')
				}
			}
		})
	})
	//**********************接收消息**************
	// setInterval(getMsg,1000); //每1秒发送一次请求
	function getMsg()
	{
		$.getJSON('index.php?chat_record','', 
			function(back, textStatus) {
				$('#chat_message ul').append('<li>时间:'+'<span>'+back.times+'</span> <p>'+back.msg+'</p></li>')
		});
	}
});
