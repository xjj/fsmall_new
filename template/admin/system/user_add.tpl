<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到管理员列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">用户名</td>
		<td><input type="text" name="uname" class="txt" size="30" />
			<span class="lpad">2个汉字（4个字母） ~ 10个汉字（20个字母）</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">邮箱</td>
		<td><input type="text" name="email" class="txt" size="30" /></td>
	  </tr>
	  <tr>
		<td class="ltd">密码</td>
		<td><input type="password" name="upwd" class="txt" size="20" />
			<span class="lpad">6 ~ 18位</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">确认密码</td>
		<td><input type="password" name="upwd2" class="txt" size="20" />
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" value="提交" class="btn2" name="submit" />
		</td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>