$(document).ready(function(){
	var contactPanel = $("#self_contact");
	contactPanel.find('.chat_contact span').click(function(event) {
		contactPanel.append('<li class="friend_group"><input type="text" name="1" value="2"/><span>确定</span><span>取消</span></li>')
	});
});
