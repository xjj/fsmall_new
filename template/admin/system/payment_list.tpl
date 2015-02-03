<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加支付信息</a>
		</div>
		<div class="y"></div>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th>ID</th>
		<th>名称</th>
		<th>代码</th>
		<th width="30%">备注</th>
		<th>添加时间</th>
		<th>更新时间</th>
		<th>有效</th>
		<th width="20%">操作</th>
	  </tr>
	  <{if $payment_items}>
	  <{foreach $payment_items as $item}>
	  <tr>
		<td><{$item.pay_id}></td>
		<td><{$item.pay_name}></td>
		<td><{$item.pay_code}></td>
		<td><{$item.content}></td>
		<td><{$item.add_time|date_format:"Y-m-d H:i"}></td>
		<td><{$item.update_time|date_format:"Y-m-d H:i"}></td>
		<td><{if $item.status eq 1}><a href="/<{$mod}>/<{$col}>/hide/<{$item.pay_id}>" class="green">是</a><{else}><a href="/<{$mod}>/<{$col}>/show/<{$item.pay_id}>" class="red">否</a><{/if}></td>
		<td><a href="/<{$mod}>/<{$col}>/edit/<{$item.pay_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.pay_id}>" class="btn-del">删除</a>
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
		popup.confirm('确定要删除这条记录吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>