<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/login.css" />
<div class="login-wrap">
	<div class="login-box">
		<div class="login-logo"></div>
		<div class="login-form">
			<form name="login" method="post" action="/login/verify">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="login-tbl">
			  <tr>
				<td colspan="2">
					<div class="login-input">
						<div class="login-txt">帐号</div>
						<input type="text" name="uname" autocomplete="off" class="txt" />
					</div>
				</td>
			  </tr>
			  <tr>
				<td colspan="2">
					<div class="login-input">
						<div class="login-txt">密码</div>
						<input type="password" name="upass" autocomplete="off" class="txt" />
					</div>
				</td>
			  </tr>
			  <tr>
			  	<td>
					<input type="checkbox" name="remember" value="1" checked="checked" class="chkbox" />
					<span class="lpad">记住密码</span>
				</td>
				<td align="right"><input type="submit" name="submit" class="btn2" value="提交" /></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>