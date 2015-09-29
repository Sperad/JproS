@base/common/header.tpl#
<body>
<form action="index.php?User_signUp" method="post">
<table id="table1">
	<tr>
		<td class="td1">昵称：</td>
		<td><input type="text"  name="nickname"  placeholder='请输入昵称'/> *</td>
	</tr>
	<tr>
		<td class="td1">真实姓名：</td>
		<td><input type="text"  name="realname" /> *</td>
	</tr>
	<tr>
		<td class="td1">密码：</td>
		<td><input type="password" name="password" /> *</td>
	</tr>
	<tr>
		<td class="td1">确认密码：</td>
		<td><input type="password" name="repassword" /> *</td>
	</tr>
	<tr>
		<td class="td1">性别：</td>
		<td>
			<input type="radio" value='男' name="sex" checked="checked" />男 
			<input type="radio" value='女' name="sex" />女</td>
	</tr>
	<tr>
		<td class="td1">出生日期：</td>
		<td>
			<input placeholder="请输入日期" class="laydate-icon" name='birthday' onClick="laydate({istime: false})">
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value=" 注册 " id="submit" /></td>
	</tr> 
</table>
</form>
<script type="text/javascript" src="../static/js/plug/laydate.js"></script>
