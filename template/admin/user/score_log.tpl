<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
			<span class="lpad">用户名：</span>
			<input type="text" name="uname" size="20" class="txt" value="<{$smarty.get.uname}>" />
			<a href="javascript:;" id="btn-submit" class="btn">查询</a>
		</form>
	</div>
	<table width="50%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>编号</th>
		<th>用户名</th>
		<th>操作类型</th>
		<th>积分</th>
        <th>原因</th>
        <th>操作时间</th>
	  </tr>
	  <{if $log_items}>
	  <{foreach $log_items as $k=>$item}>
	  <tr>
		<td><{$item.id}></td>
		<td><{$item.uname}></td>
		<td><{if $item.act_type eq 0}>
        		<span class="green">增加</span>
            <{else}>
            	<span class="red">减少</span>
           	<{/if}></td>
		<td><{$item.score}></td>
		<td><{$item.reason}></td>
		<td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr class="nohover">
	  	<td colspan="14">暂无记录！</td>
	  </tr>
	  <{/if}>
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