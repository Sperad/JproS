@common/header.tpl#
@css/chat/dialog.css#
<div class="chat_dialog">
	<div class="dialog_title">
		<span class="title_option title_name">成都大闹天空</span>
		<span class="title_option ">与 (#$nickname@) 聊天</span>
		<span class="title_option title_close"><a href="#">关闭</a></span>
	</div>
	<div id="chat_msg" class="msg_container">
		<div class="msg_box" id="msg_box" chatWithId="#$chatWithId@" >
			<!--历史纪录-->
			<ul class="msg_history">
				#foreach $chatHistory $record@
					#if $chatWithId==$record['from_user_id']@
						<li class="msg_from">
					#else@
						<li class="msg_to">
					#if/@
					<span> #$record['create_time']@ </span>
					<p>#$record['content']@</p>
				</li>
				#foreach/@
			</ul>
			<ul class="msg_news">
				<!-- <li class="msg_to">
					<span>2015-10-12 15:55:41</span>
					<p>你好</p>
				</li>
				<li class="msg_to">
				<span>2015-10-12 15:55:41</span>
				<p>你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好</p>
				</li> -->
			</ul>
		</div>
		<div class="msg_edit">
			<form action="/">
				<!-- <div id="sendMsg" class=""> -->
				<textarea placeholder="请输入"></textarea>
				<span>
					<button type="button" id="sendBtn" class="sendBtn msg_btn">发送</button>
					<button type="button" id="msgHistory" class="msg_btn">记录</button>
				</span>
			</form>
		</div>
	</div>
</div>
@js/chat/dialog.js#