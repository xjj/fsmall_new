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
						<div class="login-txt"><{$LANG.USERNAME}></div>
						<input type="text" name="sp_uname" autocomplete="off" class="txt" />
					</div>
				</td>
			  </tr>
			  <tr>
				<td colspan="2">
					<div class="login-input">
						<div class="login-txt"><{$LANG.PASSWORD}></div>
						<input type="password" name="sp_upass" autocomplete="off" class="txt" />
					</div>
				</td>
			  </tr>
			  <tr>
			  	<td>
					<input type="checkbox" name="language" value="1" checked="checked" class="chkbox" />
					<span class="lpad"><{$LANG.KOREA}></span>
				</td>
				<td align="right"><input type="submit" name="submit" class="btn2" value="<{$LANG.LOGIN}>" /></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>