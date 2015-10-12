$(document).ready(function() {
	var msgBtn = $("#sendBtn");
	var msgBox = $("#msg_box");
	
	//发送消息
	msgBtn.bind('click', function(event){
		event.preventDefault();
		var msgData = $('.editMsg').val();
		if(msgData==''){
			alert('请输入内容');
		}else{
			dialog.sendMsg({'content':msgData},msgBox);
		}
	})

	//接收消息
	setInterval(function(){dialog.getMsg(msgBox)},1500); //每1秒发送一次请求

	//获取历史消息记录
	$('#msgHistory').bind('click',function(event) {
		if(!$(this).is('.msg_history')) {
			$(this).addClass('msg_history');
			dialog.msgHistory(msgBox);
		}
	});

});

var dialog = {
	sendMsg: function(msg, msgBox)
	{
		_this = this;
		$.ajax({
			url: 'index.php?chat_sendMsg',
			type: 'POST',
			contentType : 'application/json',
			data: JSON.stringify(msg),
			dataType: 'json',//服务器返回的数据类型  与下一个注释 二选一
			success:function(back){
				// back = JSON.parse(back);
				_this.appendMsg(msgBox,back);
			}
		})
	},

	getMsg : function(msgBox)
	{
		_this = this;
		$.getJSON('index.php?chat_record','', 
			function(back, textStatus) {
				if(back){
					$.each(back, function(index, item) {
						_this.appendMsg(msgBox,item);
					});
				}
		});
	},

	appendMsg : function(msgBox,infoData)
	{
		var chatWithId = msgBox.attr('chatWithId');
		var className = 'from';
		if(infoData.to_user_id == chatWithId )
				className = 'to';
		msgBox.append('<li class="msg_'+className+'"><span>'+
				infoData.create_time+'</span> <p>'+infoData.content+'</p></li>')
	},

	msgHistory : function(msgBox)
	{
		_this = this;
		$.getJSON('index.php?chat_historyRecord','', 
			function(back, textStatus) {

				if(back){
					$.each(back, function(index, item) {
						_this.historyMore(msgBox,item);
					});
				}
				msgBox.prepend('<li><a class="history_more" href="#">more</a></li>');
				$('.history_more').bind('click',function(event){
					event.preventDefault();
						msgBox.find('.history_more').parent().remove();
					_this.msgHistory(msgBox);
				});
				
		});
	},
	historyMore : function(msgBox,infoData){
		var chatWithId = msgBox.attr('chatWithId');
		var className = 'from';
		if(infoData.to_user_id == chatWithId )
				className = 'to';
		msgBox.prepend('<li class="msg_'+className+'"><span>'+
				infoData.create_time+'</span> <p>'+infoData.content+'</p></li>')
	}
}