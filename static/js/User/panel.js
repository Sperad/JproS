$(document).ready(function(){
	var userOption = $(".user_option");
	var userHelp = $("#userHelp");
	var userGroups = $('#userGroups');
	var resultFriends = $('#resultFriends');
	var userSearch = $('#userSearch');
	var userNews = $('#userNews');
	//点击添加组
	userHelp.find('span').bind('click',function(event) {
		str = $(this).html();
		str = str=='加组' ? '取消':'加组';
		$(this).html(str).next('form').toggle(function(){
			$(this).find('input[type=submit]').click(
				function(event) {
					event.preventDefault();
					var groupName = $(this).prev('input').val();
					$(this).prev('input').val('');
					if(groupName==''){
						alert('请输组名');
					}else{
						panel.appendAddGroup(userGroups,groupName);
					}
			});
		});
	});
	
	//点击删除组 
	userGroups.find('.delGroup').bind('click',function(event) {
		var group = $(this).parent('span[group]');
		panel.delGroup(group,group.attr('group'));
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
		resultFriends.append('<li><a>'+friendName+'</a><a class="mov" herf="#"><img src="../static/img/del.png" /></a></li>');
		resultFriends.find('.mov').bind('click',function(event) {
			event.preventDefault();
			var newGroup = $("#groupsName option:selected");
			if(confirm('确定要将 '+friendName+' 从 '+oldGroupName+ ' 移动到 ' +newGroup.html())){
				panel.movFriend(friendId,oldGroupId,newGroup.val());
			}
		});
	});

	//点击消息
	userNews.bind('click',function(event){
		event.preventDefault();
		resultFriends.empty();
		panel.toggle_option($('.user_option[data-toggle=newsFriends]')).requestFriend(resultFriends);
	});
	
	//点击删除好友
	userGroups.find(".delFriend").bind('click',function(event){
		event.preventDefault();
		var friendId = $(this).attr('href');
		var groupId = $(this).parents('ul').prev('span[group]').attr('group');
		panel.delFriend(friendId,groupId);
	});
	
	//点击聊天
	userGroups.find(".chat_with").bind('click',function(event){
		event.preventDefault();
		var sFeatures = "height=600, width=600, scrollbars=yes, resizable=yes";
		$(this).target = "_blank"; 
		 window.open($(this).attr('href'), '3km', sFeatures );
	});
});
var panel = {

	toggle_option: function(options){
		toggle = options.attr('data-toggle');
		var otherToggle = options.siblings('div');
		$.each(otherToggle, function(index, toggleObj) {
			$(toggleObj).removeClass('option_active');
			var toggle_ = $(toggleObj).attr('data-toggle');
			$('#'+toggle_).hide();
		});
		options.addClass('option_active');
		$('#'+toggle).show();
		return this;
	},
	appendAddGroup : function(userGroups,groupName){
		//先添加组
		$.post('index.php?User_Group', {'groupName':groupName}, function(data, textStatus, xhr) {
			if(data != 'false'){
				alert('添加成功');
				//移除input 标签，改为li标签
				var str = '<div class="user_group"><span><h1>'+groupName+'</h1><img src="../static/img/del.png"/></span></div>';
				userGroups.append(str);
			}else{
				alert('添加失败,查看是否重名');
			}
		});
	},
	delGroup : function (group,groupId)
	{
		$.post('index.php?User_delGroup',
				{'groupId':groupId}, 
			function(data, textStatus, xhr) {
				if(data == true)
				{
					alert('删除成功');
					group.parent().remove();
				}else{
					alert('该组还有成员,请删除以后再删除组');
				}
		});
	},

	findFriend : function (resultFriends,text)
	{	
		var _this = this;
		text = text == undefined ? '': text;
		$.get('index.php?User_search/search='+text, function(data){
			if(data != 'false') {
				resultFriends.empty();
				$.each(data, function(index, friend){
					resultFriends.append('<li><a>'+friend.nickname+'</a>'+
						'<a class="add_friend" href="index.php?User_friend/friendId='+friend.id+'&status=1">'+
							'<img src="../static/img/jiahao__hongse.png" />'+'</a></li>');
				});
				//绑定标签进行添加好友
				resultFriends.find('a[class=add_friend]').bind('click',function(event){
					event.preventDefault();
					var name = $(this).prev('a').html();
					var url = $(this).attr('href');
					var group = $("#groupsName option:selected");
					if(confirm('你确定要将 '+name+' 加入到 '+group.html()+ ' 吗？')){
						_this.addFriend(url+'&groupId='+group.val());
					}
				});
			}else{
				alert('搜索失败');
			}
		});
	},

	addFriend : function (url)
	{
		$.post(url, '', function(data, textStatus, xhr){
			if(data == true){//添加成功
				alert("添加成功");//刷新当前页面
				window.location.reload();
				//隐藏
			}else{
				alert('添加失败');
			}
		});
	},

	requestFriend : function(resultFriends)
	{
		var _this = this;
		$.get('index.php?User_friend',function(data)
		{
			if(data != 'false')
			{	
				$.each(data, function(index, friend){
					resultFriends.append('<li><a>'+friend.nickname+'</a>'+
						'<a class="add_friend" href="index.php?User_friend/friendId='+friend.id+'&status=3">'+
							'<img src="../static/img/jiahao__hongse.png" />'+'</a>'+
						'<a href="index.php?User_friend/friendId='+friend.id+'&status=5">'+
							'<img src="../static/img/del.png" />'+'</a>'+ '</li>')
				});
				//绑定标签进行添加好友
				resultFriends.find('li a').bind('click',function(event){
					event.preventDefault();
					var url = $(this).attr('href');
					var groupId = '&groupId='+$("#groupsName option:selected").val();
					_this.addFriend(url+groupId);
				});
			}else{
				alert('获取数据失败');
			}
		});
	},

	delFriend :function(friendId,groupId)
	{
			$.post('index.php?User_delFriend',
					{'friendId':friendId,'groupId':groupId}, 
				function(data, textStatus, xhr) {
					if(data = true)
					{
						alert('删除成功');window.location.reload();
					}
			});
	},

	movFriend : function(friendId,oldGroupId,newGroupId){
		$.post('index.php?User_movFriend',
			{'friendId':friendId,'groupId':newGroupId,'oldGroupId':oldGroupId}, 
			function(data, textStatus, xhr) {
				if(data == true) {
					alert('移动成功');window.location.reload();
				}
		});
	}
}
