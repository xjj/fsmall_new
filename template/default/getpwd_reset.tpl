<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/getpwd.css" />

<div class="wrap getpwd-wrap">
	<div class="getpwd-title">重置密码</div>
	<div class="getpwd-box">
		<div class="getpwd-box-title">
			重置帐号 <b><{$uname}></b> 的密码： 
		</div>
		<div class="getpwd-box-content">
			<form name="ff" method="post" action="/getpwd/reset?e=<{$smarty.get.e|escape:'url'}>&c=<{$smarty.get.c|escape:'url'}>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
			  <tr>
				<td class="ltd" width="60">新密码</td>
				<td><input type="password" name="upass" class="txt" size="30" /></td>
			  </tr>
			  <tr>
				<td class="ltd">确认密码</td>
				<td><input type="password" name="upass2" class="txt" size="30" /></td>
			  </tr>
			  <tr>
				<td class="ltd"></td>
				<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>