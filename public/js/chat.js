$(document).ready(function() {
	//退出
	$('#logout').click(function(){
		$.get('/User_logout',function(url){
			window.location.href = url;
		})
	})
	
	var msgBtn = $("#sendBtn");
	var msgBoxOld = $("#msg_box .msg_history");
	var msgBoxNew = $("#msg_box .msg_news");
	
	//点击关闭对话 <改为换组>
	$('.title_close').bind('click',function(event) {

	});
	//发送消息
	msgBtn.bind('click', function(event){
		var msgData = $(this).parent('span').prev('textarea');
		if(msgData.val()==''){
			alert('请输入内容');
		}else{
			dialog.sendMsg(msgBoxNew,{'content':msgData.val()});
			msgData.val('');
		}
	})

	//接收消息
	setInterval(function(){dialog.getMsg(msgBoxNew)},1500); //每1秒发送一次请求

	//获取历史消息记录
	$('#msgHistory').bind('click',function(event) {
		if(!$(this).is('.msgBtn_history')) {
			$(this).addClass('msgBtn_history');
			//获取最新的聊天记录id
			msgNewli = msgBoxNew.children();
			msgOldli = msgBoxOld.children();
			queryTimes = 0;

			if(msgNewli.length)
				queryTimes = $(msgNewli).length;
			if(msgBoxOld.length)
				queryTimes = $(msgOldli).length;
			dialog.msgHistory(msgBoxOld,queryTimes);
		}
	});

	var dialog = {
		chatWithId: $('#msg_box').attr('chatwithid'),
		sendMsg: function(msgBoxNew,msg)
		{
			_this = this;
			$.ajax({
				url: '/chat_sendMsg',
				type: 'POST',
				contentType : 'application/json',
				data: JSON.stringify(msg),
				dataType: 'json',//服务器返回的数据类型  与下一个注释 二选一
				success:function(back){
					// back = JSON.parse(back);
					_this.appendMsg(msgBoxNew,back);
				}
			})
		},

		getMsg : function(msgBoxNew)
		{
			_this = this;
			$.getJSON('/chat_record','', 
				function(back, textStatus) {
					if(back){
						$.each(back, function(index, item) {
							_this.appendMsg(msgBoxNew,item);
						});
					}
			});
		},

		appendMsg : function(msgBox,infoData)
		{
			var className = 'to';
			if(infoData.from_user_id == this.chatWithId)
				className = 'from';
			msgBox.append('<li class="msg_'+className+'" msgId="'+infoData.id+'"><span>'+
					infoData.create_time+'</span> <p>'+infoData.content+'</p></li>')
		},

		msgHistory : function(msgBoxOld,times)
		{
			_this = this;
			var url= '/chat_historyRecord';
			if(times != undefined ) {
				url += '/times='  + times;
			}
			$.getJSON(url, function(back, textStatus) {
				if(back){
					$.each(back, function(index, item) {
						_this.historyMore(msgBoxOld,item);
					});
					msgBoxOld.prepend('<li class="history_more"><a href="#">更多...</a></li>');
					$('.history_more a').bind('click',function(event){
						event.preventDefault();
							msgBoxOld.find('.history_more a').parent().remove();
						_this.msgHistory(msgBoxOld);
					});
				}
			});
		},
		historyMore : function(msgBoxOld,infoData){
			var className = 'from';
			if(infoData.to_user_id == this.chatWithId )
					className = 'to';
			msgBoxOld.prepend('<li class="msg_'+className+'"><span>'+
					infoData.create_time+'</span> <p>'+infoData.content+'</p></li>')
		}
	}
	
});