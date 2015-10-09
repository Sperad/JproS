@base/common/header.tpl#
@css/user/panel.css#
<div id="chat_self">
	<div class="self_top">
		<span>你好:#$name@</span>
	</div>
	<div class="self_container">
		<div class="self_search">
			<span><input type="text" name="like" placeholder="查找"></span><span class="doFind">+查找</span>
			<div class="search_groups">
				添加到
				<select name="" id="search_groups">
					#foreach $groups $group@
						<option value=#$group['id']@>#$group['group_name']@</option>
					#foreach/@
				</select>
			</div>
			<div class="friend_result" id="friend_result">
				<ul>
					<!-- <li><span>结果1</span><a href="#">	添加</a></li>
					<li><span>结果2</span><a href="#">	添加</a></li>
					<li><span>结果3</span><a href="#">	添加</a></li>
					<li><span>结果4</span><a href="#">	添加</a></li> -->
				</ul>
			</div>
		</div>
		<ul class="self_friend" id="self_contact">
		#foreach $list $k $group@
			#if !is_int($k)@
				<li class="chat_contact">
					<a href="#">#$group@</a>
					<span class="do_addGroup">+加组</span>
					<span class="do_recordFriend">你有<a href="#">#$requestRecord@</a>条消息</span>
					<div class="recordFriend">
						<div class="recordFriend_groups">
							添加到
							<select name="" id="recordFriend_groups">
								#foreach $groups $group@
									<option value=#$group['id']@>#$group['group_name']@</option>
								#foreach/@
							</select>
						</div>
						<ul class="recordFriend_list" id="recordFriend_list">
						</ul>
					</div>
				</li>
			#else@
				<li class="friend_group">#$group['group_name']@
					<ul>
					#foreach $group['users'] $user@
						<li><a href="index.php?chat_panel/chatwith=#$user['id']@">#$user['nickname']@</a></li>
					#foreach/@
					</ul>
				</li>
			#if/@
		#foreach/@
				<!-- <li class="friend_group add_group">
					<input type="text" name="groupName" value="随意" maxlength="16" />
					<span class='sure'>确定</span><span class='cancel'>取消</span>
				</li> -->
		</ul>
	</div>
</div>
@js/user/panel.js#
