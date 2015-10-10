$(document).ready(function(){
	var contactPanel = $("#self_contact");
	//点击添加组
	contactPanel.find('.btn_addGroup').bind('click',function(event) {
		if(contactPanel.find('.add_group')[0] == undefined)
		{
			panel.appendAddGroup(contactPanel);
		}else{
			alert("请保存");
		}
	});
	
	//点击删除组
	contactPanel.find('.delGroup').bind('click',function(event) {
		event.preventDefault();
		var groupId = $(this).next('ul[group]').attr('group');
		panel.delGroup(groupId);
	});

	//点击查找好友
	$('.doFind').bind('click',function(){
		var resultPanel = $(".search_news");
		o_this = $(this);
		resultPanel.find('.btn_movFriend').hide();
		resultPanel.show(function(){
			panel.findFriend($(this),o_this.prev().val());
			$(".srch_ns_close").bind('click',function(event) {
				resultPanel.hide();
			});
		});
	});

	//点击移动好友
	$(".movFriend").bind('click', function(event) {
		event.preventDefault();
		var resultPanel = $(".search_news");
		var friendId = $(this).attr('href');
		var oldGroupId = $(this).parents('ul[group]').attr('group');
		resultPanel.find('.srch_ns_result').empty().next('.btn_movFriend').show();
		resultPanel.show(function(){
			panel.movFriend($(this),friendId,oldGroupId);
			$(".srch_ns_close").bind('click',function(event) {
				resultPanel.hide();
			});
		});

	});

	//点击请求好友消息
	$(".btn_recordFriend").bind('click',function(event){
		event.preventDefault();
		var resultPanel = $(".search_news");
		resultPanel.find('.srch_ns_result').empty().next('.btn_movFriend').hide();
		resultPanel.show(function(){
			panel.requestFriend($(this));
			$(".srch_ns_close").bind('click',function(event) {
				resultPanel.hide();
			});
		});
	});
	
	//点击删除好友
	$(".delFriend").bind('click',function(event){
		event.preventDefault();
		var friendId = $(this).attr('href');
		var groupId = $(this).parents('ul[group]').attr('group');
		panel.delFriend(friendId,groupId);
	});

	
	//点击聊天
	contactPanel.find(".chat_with").bind('click',function(event){
		event.preventDefault();
		var sFeatures = "height=600, width=600, scrollbars=yes, resizable=yes";
		$(this).target = "_blank"; 
		 window.open($(this).attr('href'), '3km', sFeatures );
	});
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
					alert('添加成功');window.location.reload();
					//移除input 标签，改为li标签
					// dest.parents(".friend_group").removeClass('add_group').empty().append(group.val());
				}else{
					alert('添加失败,查看是否重名');
				}
			});
		},

		findFriend : function (panelShow,text)
		{	
			var _this = this;
			$.get('index.php?User_search/search='+text, function(data){
				if(data != 'false')
				{	
					var friends = panelShow.find('.srch_ns_result');
					friends.empty();
					$.each(data, function(index, value){
						friends.append('<li><span>'+value.nickname+'</span>'+
											'<a href="index.php?User_friend/friendId='+value.id+'&status=1">添加</a></li>')
					});
					//绑定标签进行添加好友
					friends.find('a').bind('click',function(event){
						event.preventDefault();
						var url = $(this).attr('href');
						var groupId = '&groupId='+$("#srch_ns_groups option:selected").val();
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
					alert("添加成功");//刷新当前页面
					window.location.reload();
					//隐藏
				}else{
					alert('添加失败');
				}
			});
		},

		requestFriend : function(panelShow)
		{
			var _this = this;
			$.get('index.php?User_friend',function(data)
			{
				if(data != 'false')
				{	
					var recordFriend = panelShow.find('.srch_ns_result');
					$.each(data, function(index, value){
						recordFriend.append('<li><span>'+value.nickname+'</span>'+
											'<a href="index.php?User_friend/friendId='+value.id+'&status=3">添加</a>'+
											'<a href="index.php?User_friend/friendId='+value.id+'&status=5">拒绝</a>'+
											'</li>')
					});
					//绑定标签进行添加好友
					recordFriend.find('li a').bind('click',function(event){
						event.preventDefault();
						var url = $(this).attr('href');
						var groupId = '&groupId='+$("#srch_ns_groups option:selected").val();
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
		delGroup : function (groupId)
		{
			$.post('index.php?User_delGroup',
					{'groupId':groupId}, 
				function(data, textStatus, xhr) {
					if(data == true)
					{
						alert('删除成功');window.location.reload();
					}else{
						alert('该组还有成员,请删除以后再删除组');
					}
			});
		},
		movFriend : function(panelShow,friendId,oldGroupId){
			panelShow.find('.btn_movFriend').bind('click',function(){
				var groupId = $("#srch_ns_groups option:selected").val();
				$.post('index.php?User_movFriend',
					{'friendId':friendId,'groupId':groupId,'oldGroupId':oldGroupId}, 
					function(data, textStatus, xhr) {
						if(data == true) {
							alert('移动成功');window.location.reload();
						}
				});
			});
		}
	}
