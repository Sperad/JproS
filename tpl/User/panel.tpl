@base/common/header.tpl#
@css/user/panel.css#
<div id="chat_self" class="chat_self">
	<span class="self_top">你好:  #$name@</span>
	<div class="self_search">
		<input type="text" name="like" placeholder="查找">
		<p class="doFind">查找</p>
	</div>
	<!--好友搜索 / 查看好友添加消息 -->
	<div class="search_news">
		<div class="srch_ns_groups">
			<b>选择组</b>
			<select name="groupId" id="srch_ns_groups">
				#foreach $groups $group@
					<option value=#$group['id']@>#$group['group_name']@</option>
				#foreach/@
			</select>
			<span class='srch_ns_close'>关闭</span>
		</div>
		<ul class="srch_ns_result" id="srch_ns_result">
				<li>
					<span>结果1</span>
					<a href="#">删除</a>
					<a href="#">添加</a>
				</li>
				<li>
					<span>结果1</span>
					<a href="#">添加</a>
				</li>
		</ul>
		<span class="btn_movFriend">确定</span>
	</div>
	<div class="self_group">
		<ul id="self_contact">
		#foreach $list $k $group@
			#if !is_int($k)@
				<li class="chat_news">
					<a href="#">#$group@</a>
					<span class="btn_addGroup">加组</span>
					<span class="btn_recordFriend">消息(<a href="#">#$requestRecord@</a>)</span>
				</li>
			#else@
				<li class="friend_group">#$group['group_name']@
					<a class="delGroup" href="">删除</a>
					<ul group=#$group['id']@>
					#foreach $group['users'] $user@
						<li class="chat_friend">
							<a href="index.php?chat_panel/chatwith=#$user['id']@"  class='chat_with'> #$user['nickname']@ 
								<span> 
									#if isset($user['recordCnt']) @
										(#$user['recordCnt']@)
								  #if/@
								</span>
							</a>
							<a class="movFriend" href="#$user['id']@">移动</a>
							<a class="delFriend" href="#$user['id']@">删除</a>
						</li>
					#foreach/@
					</ul>
				</li>
			#if/@
		#foreach/@
				<!-- <li class="friend_group add_group">
					<input type="text" name="groupName" value="随意" maxlength="16" />
					<span class='sure'>确定</span>
					<span class='cancel'>取消</span>
				</li> -->
		</ul>
	</div>
</div>
@js/user/panel.js#
