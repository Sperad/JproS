@base/common/header.tpl#
@css/chat/panel.css#
<div class="chat">
	<div  id="chat_message">
		<span>消息内容</span>
		<div class="content">
			<ul>
				#foreach $chatHistory $record@
				<li>时间:<span>#$record['create_time']@</span> <p>#$record['content']@</p></li>
				#foreach/@
			</ul>
		</div>
	</div>
	<div id="sendMsg">
		<span>消息编辑</span>
		<textarea class="editMsg">dasdasd</textarea>
	</div>
	<span id="sendBtn">发送</span>
</div>
@js/chat/panel.js#