<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
			
			
			<span class="lpad">用户名：</span>
			<input type="text" name="k" size="20" class="txt" value="<{$smarty.get.k}>" />
			
			<a href="javascript:;" id="btn-submit" class="btn2">查询</a>
		</form>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="13"><input type="checkbox" name="chkall1" id="chkall1" value="1" /></th>
		<th>UID</th>
		<th>用户名</th>
		<th>邮箱</th>
		<th>会员等级</th>
		<th>余额</th>
		<th>积分</th>
		<th>注册时间</th>
		<th>最后登录</th>
<!--		<th>IP</th>
-->		<th>状态</th>
		<th>操作</th>
	  </tr>
	  <{if $user_items}>
	  <{foreach $user_items as $item}>
	  <tr>
		<td><input type="checkbox" name="uid[<{$item.uid}>]" id="chkall1" value="<{$item.uid}>" /></td>
		<td><{$item.uid}></td>
		<td><{$item.uname}></td>
		<td><{$item.email}></td>
		<td><{$grade_items[$item.grade_id].grade_name}></td>
		<td><{$item.balance}></td>
		<td><{$item.score}></td>
		<td><{$item.reg_time|date_format:'Y-m-d H:i'}></td>
		<td><{$item.last_login_time|date_format:'Y-m-d H:i'}></td>
<!--		<td><{$item.last_login_ip}></td>
-->		<td><{if $item.status eq 1}><span class="green">已激活</span><{else}><span class="gray">未激活</span><{/if}></td>
		<td>
			

			<a href="/<{$mod}>/<{$col}>/scorelog/<{$item.uid}>">查看积分操作日志</a>

		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr class="nohover">
	  	<td colspan="14">用户信息为空！</td>
	  </tr>
	  <{/if}>
	  <!--<tr class="nohover">
	  	<td></td>
		<td colspan="13"><a href="javascript:;" id="btn-scorelog" class="btn2 btn3">查看积分操作日志</a></td>
	  </tr>-->
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	var searchForm = $('#search-form');
	var submitBtn  = $('#btn-submit');
	
	submitBtn.click(function(){
		searchForm.submit();
	});
	
	searchForm.keyup(function(event){
		if (event.keyCode == 13){
			submitBtn.trigger('click');
		}
	});
	
});
</script>
<{include file="common/footer.tpl"}>