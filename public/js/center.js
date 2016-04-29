$(document).ready(function(){
	//前台动画
	var userGroupsCls = $('.user-group');
	var toUser = $('.chat-with');
	var flag =false;
	var userOption = $(".user_option");
	var userHelp = $("#userHelp");
	var userGroups = $('#userGroups');
	var resultFriends = $('#resultFriends');
	var userSearch = $('#userSearch');
	var userNews = $('#userNews');

	panel.findFriend('');
	//点击添加组
	userHelp.find('#addGroup').bind('click',function(event) {
		$('.dialog-group').show(function(){
			$(this).find('button').click(function(){
				var groupName = $(this).prev().val();
				if(!groupName){
					alert('请输组名');
				}else{
					panel.appendAddGroup(groupName);
				}
			});
		});
		$(this).unbind("click");
	});
	
	//点击删除组 
	userGroups.find('.delGroup').bind('click',function(event) {
		var group = $(this).parents('.user-group');
		panel.delGroup(group,group.attr('group'));
	});
	//退出
	$('#logout').click(function(){
		$.get('/User_logout',function(url){
			window.location.href = url;
		})
	})
	//点击请求添加好友的消息
	userNews.bind('click',function(event){
		panel.listRequest();
	});

	//点击组 下拉显示好友列表
	userGroupsCls.find('span').bind('click',function(){
		if($(this).hasClass('open')){
			$(this).removeClass('open').next().hide();
			$(this).find('.delGroup').hide();
		}else{
			$(this).find('.delGroup').show();
			$(this).addClass('open').next().show();
		}
	});

	toUser.dblclick(function(){
		// panel.getUser($(this).attr('uid'));
		window.location.href = "/Chat_dialog/with=" + $(this).attr('uid');
	});





	//点击查找好友
	userOption.bind('click',function(event) {
		panel.toggle_option($(this)).findFriend(resultFriends);
	});
	//form 搜索组
	userSearch.find('button').bind('click', function(event) {
		event.preventDefault();
		var search = $(this).prev('input').val();
		panel.toggle_option($('.user_option[data-toggle=newsFriends]')).findFriend(resultFriends,search);
	});
	//点击移动好友
	userGroups.find(".movFriend").bind('click',function(event) {
		event.preventDefault();
		var friendId= $(this).attr('href');
		var friendName = $(this).attr('name');
		var oldGroupId = $(this).parents('ul').prev('span[group]').attr('group');
		var oldGroupName = $(this).parents('ul').prev('span[group]').attr('name');
		resultFriends.empty();
		panel.toggle_option($('.user_option[data-toggle=newsFriends]'));
		resultFriends.append('<li><a>'+friendName+'</a><a class="mov" herf="#"><img src="../static/img/right.png" /></a></li>');
		resultFriends.find('.mov').bind('click',function(event) {
			event.preventDefault();
			var newGroup = $("#groupsName option:selected");
			if(confirm('确定要将 '+friendName+' 从 '+oldGroupName+ ' 移动到 ' +newGroup.html())){
				panel.movFriend(friendId,oldGroupId,newGroup.val());
			}
		});
	});
	//点击删除好友
	userGroups.find(".delFriend").bind('click',function(event){
		event.preventDefault();
		var friendId = $(this).attr('href');
		var groupId = $(this).parents('ul').prev('span[group]').attr('group');
		panel.delFriend(friendId,groupId);
	});
});
var panel = {
	addGrouped : false,
	userGroups : '#userGroups',
	userList   : '#userList',
	getGroupTpl : function(groupName){
		return '<div class="user-group">'+
				'<span>&nbsp;&nbsp;'+groupName+
	 				'<div class="del"></div></span></div>';
	},
	getUserTpl : function(userListData){
		var tpl = '';
		for(i in userListData){
			tpl += '<div class="user" uid="'+userListData[i].id+'"><h4>' + userListData[i].nickname +
						'<span class="add"></span></h4></div>';
		}
		return tpl;
	},
	appendAddGroup : function(groupName){
		var that = this;
		$.post('/User_Group', {'groupName':groupName}, function(data, textStatus, xhr) {
			if(data == true){
				$(that.userGroups).append(that.getGroupTpl(groupName));
				alert('添加成功');
				window.location.reload();
			}
		});
		return this;
	},
	delGroup : function (group, groupId)
	{
		$.post('/User_delGroup',
				{'groupId':groupId}, 
			function(data, textStatus, xhr) {
				if(data == true){
					alert('删除成功');
					group.remove();
				}else{
					alert('该组还有成员,请删除以后再删除组');
				}
		});
	},
	findFriend : function (text)
	{	
		var _this = this;
		$.get('/User_search/search='+text, function(data){
			var tpl = _this.getUserTpl(data);
			//绑定标签进行添加好友
			$(_this.userList).append(tpl).find('.add').bind('click',function(event){
				var uid =$(this).parents('.user').attr('uid');
				alert('请选择组');
				$('.group-list').show(function(){
					$(this).find('li').bind('click',function(){
						var gid = $(this).attr('gid');
						_this.requestFriend({friendId:uid,groupId:gid,status:1});
					});
				});
			});
		});
	},
	requestFriend : function (params)
	{
		$.post('/User_friend', params, function(data, textStatus, xhr){
			if(data == true){
				alert("添加成功");
			}else{
				alert('添加失败');
			}
			window.location.reload();
		});
	},
	listRequest : function (){
		var _this = this;
		$.get('/User_friend', function(data){
			var tpl = _this.getUserTpl(data);
			//绑定标签进行添加好友
			$(_this.userList).empty().append(tpl).find('.add').bind('click',function(event){
				var uid =$(this).parents('.user').attr('uid');
				alert('请选择组');
				$('.group-list').show(function(){
					$(this).find('li').bind('click',function(){
						var gid = $(this).attr('gid');
						_this.requestFriend({friendId:uid,groupId:gid,status:3});
					});
				});
			});
		});
	},

	//获取聊天用户信息
	getUser : function(uid){
		_this = this;
		$.get('/User_getOne/uid=' + uid, function(data){

		});
	},







	delFriend :function(friendId,groupId)
	{
			$.post('/User_delFriend',
					{'friendId':friendId,'groupId':groupId}, 
				function(data, textStatus, xhr) {
					if(data = true)
					{
						alert('删除成功');window.location.reload();
					}
			});
	},

	movFriend : function(friendId,oldGroupId,newGroupId){
		$.post('/User_movFriend',
			{'friendId':friendId,'groupId':newGroupId,'oldGroupId':oldGroupId}, 
			function(data, textStatus, xhr) {
				if(data == true) {
					alert('移动成功');window.location.reload();
				}
		});
	},

	requestVisitor: function(resultFriends){
		var _this = this;
		$.get('/User_visitors',function(data)
		{
			if(data != 'false')
			{	
				$.each(data, function(index, visitor){
					resultFriends.append('<li class="group_friend">'+
						'<a class="chat_with" href="/chat_dialog/chatwithId='+visitor.id+'&role=friend&fromRole=visitor">'+
							visitor.nickname+'<i>('+visitor.cnt+')</i></a>');
				});
				resultFriends.find(".chat_with").bind('click',function(event){
					event.preventDefault();
					var sFeatures = "height=600, width=600, scrollbars=yes, resizable=yes";
					$(this).target = "_blank"; 
					 window.open($(this).attr('href'), '3km', sFeatures );
				});
			}else{
				alert('获取数据失败');
			}
		});
	}
}
