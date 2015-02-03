<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加汇率信息</a>
		</div>
		<div class="y"></div>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>日期</th>
		<th>韩元(KRW)</th>
		<th>美元(USD)</th>
		<th width="30%">备注</th>
		<th>添加时间</th>
		<th>操作</th>
	  </tr>
	  <{if $rate_items}>
	  <{foreach $rate_items as $item}>
	  <tr>
		<td><{$item.date_line}></td>
		<td><{$item.krw}></td>
		<td><{$item.usd}></td>
		<td><{$item.content}></td>
		<td><{$item.add_time|date_format:"Y-m-d H:i:s"}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.date_line}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.date_line}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定要删除这条记录吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>