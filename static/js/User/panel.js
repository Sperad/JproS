$(document).ready(function(){
	var contactPanel = $("#self_contact");
	//点击添加组
	contactPanel.find('.chat_contact span').bind('click',function(event) {
		if(contactPanel.find('.add_group')[0] == undefined)
		{
			panel.appendAddGroup(contactPanel);
		}else{
			alert("请保存");
		}
	});
	
	//点击聊天
	var friend_group = $(".friend_group");
	friend_group.find('a').bind('click',function(event){
		event.preventDefault();
		var sFeatures = "height=600, width=600, scrollbars=yes, resizable=yes";
		$(this).target = "_blank"; 
		 window.open($(this).attr('href'), '3km', sFeatures );
	});

	//点击查找好友
	$(".self_search").find('.doFind').bind('click',function(){
		panel.findFriend($(this).prev('span').find('input'));
	})
	
});
var panel = {
		appendAddGroup : function(contactPanel){
			contactPanel.append('<li class="friend_group add_group"><input type="text" name="groupName" value=""/><span class="sure">确定</span><span class="cancel">取消</span></li>');
			//确定添加
			var _this = this;
			contactPanel.find('.add_group .sure').bind('click',function(event) {
				groupData = $(this).prev('input');
				if(groupData.val() == ''){
					alert('请输入值');
				}else{
					_this.addGroup(groupData,$(this));
				}
			});

			contactPanel.find('.add_group .cancel').bind('click',function(event) {
				_this.cancelAddGroup(contactPanel);
			});
		},

		cancelAddGroup : function(contactPanel){
			contactPanel.find('.add_group').remove();
		},

		addGroup : function ( group,dest) {
			$.post('index.php?User_Group', group, function(data, textStatus, xhr) {
				if(data != 'false'){
					alert('添加成功')
					//移除input 标签，改为li标签
					dest.parents(".friend_group").removeClass('add_group').empty().append(group.val());
				}else{
					alert('添加失败,查看是否重名');
				}
			});
		},

		findFriend : function (option)
		{	
			var _this = this;
			$.post('index.php?User_search', option, function(data, textStatus, xhr)
			{
				if(data != 'false')
				{	
					var friends = $("#friend_result ul");
					$.each(data, function(index, value){
						friends.append('<li><span>'+value.nickname+'</span>'+
											'<a href="index.php?User_friend/friendId='+value.id+'">添加</a></li>')
					});
					//绑定标签进行添加好友
					friends.find('li a').bind('click',function(event){
						event.preventDefault();
						var url = $(this).attr('href');
						var groupId = '&groupId='+$("#search_groups option:selected").val();
						alert(url+groupId);
						_this.addFriend(url+groupId);
					});
				}else{
					alert('搜索失败');
				}
			});
		},

		addFriend : function (url)
		{
			$.post(url, '', function(data, textStatus, xhr){
				if(data == true)//添加成功
				{
					alert("添加成功");
					//隐藏
					$("#friend_result ul").hide();
				}else{
					alert('添加失败');
				}
			});
		}
	}
