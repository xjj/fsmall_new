<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加配送方式</a>
		</div>
		<div class="y"></div>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th>ID</th>
		<th>配送名称</th>
		<th>配送代码</th>
		<th width="40%">配送描述</th>
		<th width="10%">是否支持货到付款</th>
		<th>有效</th>
		<th>排序</th>
		<th>操作</th>
	  </tr>
	  <{if $shipping_items}>
	  <{foreach $shipping_items as $item}>
	  <tr>
		<td><{$item.shipping_id}></td>
		<td><{$item.shipping_name}></td>
		<td><{$item.shipping_code}></td>
		<td><{$item.content}></td>
		<td><{if $item.is_pay eq 1}><span class="green">是</span><{else}><span class="red">否</span><{/if}></td>
		<td><{if $item.status eq 1}><a href="/<{$mod}>/<{$col}>/hide/<{$item.shipping_id}>" class="green">是</a><{else}><a href="/<{$mod}>/<{$col}>/show/<{$item.shipping_id}>" class="red">否</a><{/if}></td>
		<td><{$item.displayorder}></td>
		<td><a href="/<{$mod}>/<{$col}>/fee/<{$item.shipping_id}>">费用设置</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.shipping_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.shipping_id}>" class="btn-del">删除</a>
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
		popup.confirm('确定删除该配送方式吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>