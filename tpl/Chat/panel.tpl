@base/common/header.tpl#
@css/chat/panel.css#
<div class="chat">
	<div id="chat_message">
		<span>消息内容</span>
		<div class="content">
			<ul></ul>
		</div>
	</div>
	<div id="sendMsg">
		<span>消息编辑</span>
		<textarea class="editMsg">dasdasd</textarea>
	</div>
	<span id="sendBtn">发送</span>
</div>

<div id="chat_self">
	<div class="self_container">
		<div class="self_search">
			<span><input type="text" name="搜索" placeholder="search"></span>
			<div>搜索结果列表
				<ul>
					<li>结果1</li>
					<li>结果2</li>
					<li>结果3</li>
					<li>结果4</li>
				</ul>
			</div>
		</div>
		<ul class="self_friend">
			<li><a href="#">联系悟空</a></li>
			<li class="friend_group">组名称_1
				<ul>
					<li>成员1</li>
					<li>成员2</li>
					<li>成员3</li>
					<li>成员4</li>
				</ul>
			</li>
			<li class="friend_group">组名称_2
				<ul>
					<li>成员x1</li>
					<li>成员x2</li>
					<li>成员x3</li>
					<li>成员x4</li>
				</ul>
			</li>
			<li class="friend_group">组名称_3
				<ul>
					<li>成员b1</li>
					<li>成员b2</li>
					<li>成员b3</li>
					<li>成员b4</li>
				</ul>
			</li>
			<li class="friend_group">组名称_4
				<ul>
					<li>成员_1</li>
					<li>成员_2</li>
					<li>成员_3</li>
					<li>成员_4</li>
				</ul>
			</li>
			<li class="friend_group">组名称_5
				<ul>
					<li>成员e1</li>
					<li>成员e2</li>
					<li>成员e3</li>
					<li>成员e4</li>
				</ul>
			</li>
		</ul>
	</div>
</div>
@js/chat/panel.js#
