<{include file="common/header.tpl"}>
<style type="text/css">
.ltd{ font-weight:bold;}
.user-auths li{ width:130px; float:left;}
.user-auths li input{ position:relative; margin-top:-2px;}
.user-auths li span{ padding-left:2px;}
</style>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到管理员列表</a>
		</div>
		<div class="y"></div>
	</div>
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/auth/<{$user_data.uid}>">
	<input type="hidden" name="uid" value="<{$user_data.uid}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl tbl2">
	  <tr>
	  	<th colspan="2">权限 ->[<{$user_data.uname}>]</th>
	  </tr>
	  <{if $navArrs}>
	  <{foreach $navArrs as $key => $val}>
	  <tr>
		<td class="ltd" width="30"><{$val.text}></td>
		<td>
			<ul class="user-auths clearfix">
			<{if $val.items}>
			<{foreach $val.items as $key2 => $val2}>
				<li>
				<{$chk=0}>
				<{foreach $user_data.authsArr as $item}>
					<{if $item eq $key|cat:"@"|cat:$key2}>
						<{$chk=1}>
						<{break}>
					<{/if}>
				<{/foreach}>
				<input type="checkbox" name="auths[]" value="<{$key}>@<{$key2}>" <{if $chk eq 1}>checked="checked"<{/if}> />
				<span><{$val2}></span>
				</li>
			<{/foreach}>
			<{/if}>
			<ul>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<input type="submit" name="submit" value="提交" class="btn2" />
	</form>
</div>
<{include file="common/footer.tpl"}>