<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>web聊天_用户登陆</title>
</head>

<body>
	用户登陆
	<form method="post" action="index.php?user_login">
		<p>昵称：<input type="text" value="" name="nickname" /></p>
		<p>密码：<input type="password" value="" name="password" /></p>
		<p>
			<input type="submit" value="登陆 "/>
			<a href='index.php?User_signUp' target='_blank'>注册</a>
		</p>
	</form>
</body>
</html>
