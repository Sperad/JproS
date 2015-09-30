@base/common/header.tpl#
@css/user/panel.css#
<div id="chat_self">
	<div class="self_top">
		<span>你好:#name@</span>
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
		#foreach list k v@
			#if $k =='contact'@
				<li>
					<a href="#">#v@</a>
					<span>+</span>
				</li>
			#else@
				<li class="friend_group">#k@
					<ul>
					#foreach v k1 v1@
						<li>#k1@</li>
					#foreach/@
					</ul>
				</li>
			#if/@
		#foreach/@
		</ul>
	</div>
</div>
@js/user/panel.js#
