@base/common/header.tpl#
@css/user/panel.css#
<div id="chat_self">
	<div class="self_top">
		<span>你好:#$name@</span>
	</div>
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
			<li>
				<a href="#">联系悟空</a>
				<span>+</span>
			</li>
			#for $i++
			<li class="friend_group">组名称_1
				<ul>
					<li>成员1</li>
					<li>成员2</li>
					<li>成员3</li>
					<li>成员4</li>
				</ul>
			</li>
		</ul>
	</div>
</div>
@js/user/panel.js#
