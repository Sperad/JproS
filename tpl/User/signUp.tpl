@common/header.tpl#
<body>
<form action="index.php?User_signUp" method="post">
<table id="table1">
	<tr>
		<td class="td1">昵称：</td>
		<td><input type="text"  name="nickname"  placeholder='请输入昵称' value="" /> *</td>
	</tr>
	<tr>
		<td class="td1">真实姓名：</td>
		<td><input type="text"  name="realname" value="" /> *</td>
	</tr>
	<tr>
		<td class="td1">密码：</td>
		<td><input type="password" name="password"  value="" /> *</td>
	</tr>
	<tr>
		<td class="td1">确认密码：</td>
		<td><input type="password" name="repassword" value="" /> *</td>
	</tr>
	<tr>
		<td class="td1">性别：</td>
		<td>
			<input type="radio" value='1' name="sex" checked="checked" />男 
			<input type="radio" value='0' name="sex" />女</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value=" 注册 " id="submit" /></td>
	</tr> 
</table>
</form>
</body>
<script type="text/javascript" src="../static/js/plug/laydate.js"></script>
