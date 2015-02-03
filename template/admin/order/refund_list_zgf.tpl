<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" method="get" action="/<{$mod}>/<{$col}>">
		<span class="rpad">会员名：</span><input type="text" name="un" value="<{$smarty.get.sn}>" class="txt" />
		<a href="javascript:;" class="btn">查 询</a>
		</form>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>订单编号</th>
		<th>商品名称</th>
		<th>属性</th>
		<th>商品价格</th>
		<th>退换数量</th>
		<th>退换原因</th>
		<th>申请时间</th>
		<th>会员</th>
		<th>处理状态</th>
		<th>操作</th>
	  </tr>
	  <{if $refund_items}>
	  <{foreach $refund_items as $item}>
	  <tr>
		<td><{$item.order_id}></td>
		<td><{$item.product_name}><br /><{$item.product_sn}></td>
		<td><{$item.props}></td>
		<td><{$item.price}></td>
		<td><{$item.number}></td>
		<td><{$item.reason}></td>
		<td><{$item.uname}></td>
		<td><{if $item.status eq 0}>
				<span class="">待处理</span>
			<{elseif $item.status eq 1}>
				<span class="green">已同意</span>
			<{elseif $item.status eq 2}>
				<span class="orange">已拒绝</span>
			<{/if}>
		</td>
		<td>
			<a href="/<{$mod}>/<{$col}>/detail/<{$item.id}>">详细</a>
			<{if $item.status eq 0}>
			<span class="dl">|</span>
			<a href="javascript:;" class="btn-deal" data-value="<{$item.id}>">处理</a>
			<{/if}>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr class="nohover">
	  	<td colspan="10">没有查询到结果！</td>
	  </tr>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-deal').click(function(){
		var id = $(this).attr('data-value');
		
	});
});
</script>
<{include file="common/footer.tpl"}>