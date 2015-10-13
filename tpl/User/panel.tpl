@common/header.tpl#
@css/user/panel.css#

<div id="user_panel" class="user_panel">
	<h1>你好：<span class="user_name">#$name@</span></h1>
	<div class="user_news btn_recordFriend" id="userNews">
    	<img src="../static/img/msg.png" /> 
    	<a href="#" target="">(<span>#$requestRecord@</span>)</a>
 	</div>
 	<form class="user_search" id="userSearch" >
    <input type="text" name="like" placeholder="查找"/> 
    <button type="submit" class="doFind"></button> 
 	</form>
 	<!-- 选项卡 --> 
 	<div class="user_options"> 
    	<div class="user_option option_active" data-toggle="userGroups">
     		<img src="../static/img/touxiang.png" />
    	</div> 
    	<div class="user_option " data-toggle="newsFriends">
	     	<img src="../static/img/jiahao_baise.png" />
	    </div>
 	</div> 
	<!-- user_friends start --> 
 	<div class="user_friends" id="userGroups">
 		#foreach $list $k $group@
	 		<div class="user_group">
	 			<span group="#$group['id']@" name="#$group['group_name']@">
			    <h1>#$group['group_name']@</h1> 
	 				<img src="../static/img/del.png" class="delGroup" />
	 			</span>
		  	<ul>
		  		#foreach $group['users'] $user@
		     	<li class="group_friend">
		     		<a class="chat_with" href="index.php?chat_dialog/chatwithId=#$user['id']@">#$user['nickname']@
		     			<i>#if isset($user['recordCnt']) @
										(#$user['recordCnt']@)
								  #if/@</i>
		     		</a>
		     		<a class="delFriend" href="#$user['id']@"><img src="../static/img/del.png"/></a>
		     		<a class="movFriend" href="#$user['id']@" name='#$user['nickname']@'>移动</a>
		     	</li>
		     	#foreach/@ 
		    </ul> 
	 		</div>
		#foreach/@
  </div>
  <!-- result start --> 
  <div class="friends_news" id="newsFriends">
  	<div class="group_select">选择组
	  	<select name="groupId" 
	  		id="groupsName">
	    		#foreach $groups $group@
						<option value=#$group['id']@>#$group['group_name']@</option>
					#foreach/@
	  	</select>
  	</div>
    <ul class="reslut_friends" id="resultFriends"> 
<!--      	<li>
     		<a href="">CLOVER</a>
     		<a href="#"><img src="../static/img/jiahao__hongse.png" /></a>
     		<a href="#"><img src="../static/img/del.png" /></a>
     	</li>  -->
    </ul> 
  </div>
  <!-- 底部help-->
  <div class="user_help" id="userHelp">
	  <div class="help_addgroup">
	  	<span>加组</span>
	  	<form action="">
	  		<input type="text" name="" />
	  		<input type="submit" class="form_submit" value="确定" />
	  	</form>
	  </div>
  </div>
</div>
@js/user/panel.js#
