@common/header.tpl#
@css/chat/dialog.css#
<div class="chat_dialog">
	<div  id="chat_message" class="message_box">
		<span>正在与 (#$nickname@) 聊天</span>
		<div class="chat_content">
			<ul id="msg_box" chatWithId="#$chatWithId@">
				#foreach $chatHistory $record@
				<li class="msg_from">
					<span>#$record['create_time']@</span>
					<p>#$record['content']@</p>
				</li>
				#foreach/@
			</ul>
			<ul id="chat_box">
				<li>
				<span>2015-10-10 15:55:41</span>
				<p>你好</p>
				</li>
				<li class="msg_to">
				<span>2015-10-10 15:55:41</span>
				<p>你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好</p>
				</li>
			</ul>
		</div>
	</div>
	<div id="sendMsg" class="sendMsg">
		<textarea placeholder="请输入" class="editMsg"></textarea>
	</div>
	<a id="sendBtn" class="sendBtn btn">发送</a>
	<a id="msgHistory" class="btn">聊天记录</a>
</div>
@js/chat/dialog.js#