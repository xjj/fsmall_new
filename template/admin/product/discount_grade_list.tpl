<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<{include file="product/discount_navs.tpl"}>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>ID</th>
		<th>会员等级</th>
		<th>品牌</th>
		<th>折扣比（%）</th>
		<th>折扣值（%）</th>
		<th width="60%">操作</th>
	  </tr>
	  <{if $discount_items}>
	  <{foreach $discount_items as $item}>
	  <tr>
		<td><{$item.id}></td>
		<td><{$item.grade_name}></td>
		<td><{if $item.brand_id gt 0}><{$item.brand_name}><{else}>all<{/if}></td>
		<td><{$item.discount2}>%</td>
		<td><{$item.discount}>%</td>
		<td><a href="/<{$mod}>/<{$col}>/grade/edit/<{$item.id}>?page=<{$smarty.get.page}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" class="btn-del" data-href="/<{$mod}>/<{$col}>/grade/del/<{$item.id}>?page=<{$smarty.get.page}>">删除</a></td>
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
		popup.confirm('确定删除该促销信息吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>