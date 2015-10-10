@common/header.tpl#
@css/chat/dialog.css#
<div class="chat_dialog">
	<div  id="chat_message" class="message_box">
		<span>消息内容</span>
		<div class="chat_content">
			<!-- <ul>
				#foreach $chatHistory $record@
				<li>时间:<span>#$record['create_time']@</span> <p>#$record['content']@</p></li>
				#foreach/@
			</ul> -->
			<ul>
				<li>
				<span>name</span>
				<span>2015-10-10 15:55:41</span>
				<p>你好</p>
				</li>
				<li>
				<span>name</span>
				<span>2015-10-10 15:55:41</span>
				<p>你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好你好</p>
				</li>
			</ul>
		</div>
	</div>
	<div id="sendMsg" class="sendMsg">
		<textarea placeholder="请输入"></textarea>
	</div>
	<span id="sendBtn" class="sendBtn">发送</span>
</div>
@js/chat/dialog.js#