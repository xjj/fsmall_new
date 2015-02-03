<{include file="common/header.tpl"}>
<style type="text/css">
.ibox li{ padding-bottom:10px;}
.ibox li span{ display:inline-block; width:70px; float:left; line-height:33px;}

</style>
<div class="suplier-wrap">
	<form method="post" name="f" action="/password/update">
	<table border="0" cellspacing="0" cellpadding="0" class="tbl nohover" style=" width:600px">
	  <tr>
		<th colspan="2"><{$LANG.PROFILE_PASSWORD_UPDATE}></th>
	  </tr>
	  <tr>
		<td class="ltd" width="60"><{$LANG.USERNAME}></td>
        <td><input type="text" class="txt" name="sp_uname" value="<{$smarty.session.suplier.sp_uname}>" />
        	<span class="lpad"><{$LANG.PROFILE_PASSWORD_SPUNAME}></span>
        </td>
	  </tr>
      <tr>
      	<td class="ltd"><{$LANG.PROFILE_PASSWORD}></td>
        <td><input type="password" class="txt" name="sp_upass" />
        	<span class="lpad"><{$LANG.PROFILE_PASSWORD_RESET}></span>
        </td>
      </tr>
      <tr>
      	<td class="ltd"><{$LANG.PROFILE_PASSWORD_CONFIRM}></td>
        <td><input type="password" class="txt" name="sp_upass2" /></td>
      </tr>
      <tr>
      	<td class="ltd"></td>
        <td><input type="submit" class="btn" value="<{$LANG.SUBMIT}>" name="submit" /></td>
      </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>