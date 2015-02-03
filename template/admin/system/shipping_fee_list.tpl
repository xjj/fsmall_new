<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到配送方式列表</a>
		<a href="/<{$mod}>/<{$col}>/fee/<{$shipping_data.shipping_id}>/add" class="btn btn3">添加配送费用信息</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th colspan="7"><{$shipping_data.shipping_name}> 配送费用设置：</th>
	  </tr>
	  <tr>
		<th>配送费用名称</th>
		<th width="40%">所辖地区</th>
		<th>首重费用</th>
		<th>续重费用</th>
		<th>免费额度</th>
		<th>操作</th>
	  </tr>
	  <{if $shipping_fee_items}>
	  <{foreach $shipping_fee_items as $item}>
	  <tr>
	  	<td><{$item.fee_name}></td>
		<td><{$item.region_name}></td>
		<td><{$item.fee_base}></td>
		<td><{$item.fee_step}></td>
		<td><{if $item.free_amount gt 0}><{$item.free_amount}><{else}><{/if}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/fee/<{$item.shipping_id}>/edit/<{$item.fee_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/fee/<{$item.shipping_id}>/del/<{$item.fee_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
</div>

<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该配送费用信息吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>