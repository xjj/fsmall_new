<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<{include file="product/discount_navs.tpl"}>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>ID</th>
		<th>品牌</th>
		<th>品牌下的类目</th>
		<th>折扣（%）</th>
		<th>开始时间</th>
		<th>结束时间</th>
		<th>状态</th>
		<th>有效</th>
		<th>操作</th>
	  </tr>
	  <{if $discount_items}>
	  <{foreach $discount_items as $item}>
	  <tr>
		<td><{$item.id}></td>
		<td><{$item.brand_name}></td>
		<td><{if $item.cat_id gt 0}><{$item.cat_name_path}><{else}>all<{/if}></td>
		<td><{if $item.discount gt 0}><{$item.discount}>%<{/if}></td>
		<td><{if $item.start_time gt 0}><{$item.start_time|date_format:"Y-m-d H:i"}><{else}>-<{/if}></td>
		<td><{if $item.end_time gt 0}><{$item.end_time|date_format:"Y-m-d H:i"}><{else}>-<{/if}></td>
		<td><{if $item.is_over eq 1}><span class="gray">已结束</span><{else}><span class="green">进行中</span><{/if}></td>
		<td><{if $item.status eq 1}>
				<a href="/<{$mod}>/<{$col}>/brand/hide/<{$item.id}>?page=<{$smarty.get.page}>" class="green">是</a>
			<{else}>
				<a href="/<{$mod}>/<{$col}>/brand/show/<{$item.id}>?page=<{$smarty.get.page}>" class="red">否</a>
			<{/if}>
		</td>
		<td><{if $item.is_over eq 0}>
				<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/brand/over/<{$item.id}>?page=<{$smarty.get.page}>" class="btn-over">结束</a>
				<span class="dl">|</span>
				<a href="/<{$mod}>/<{$col}>/brand/edit/<{$item.id}>">编辑</a>
				<span class="dl">|</span>
			<{/if}>
			<a href="javascript:;" class="btn-del" data-href="/<{$mod}>/<{$col}>/brand/del/<{$item.id}>?page=<{$smarty.get.page}>">删除</a>
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
		popup.confirm('确定删除该促销信息吗？', this, function(){
			location.href = url;
		});
	});
	
	$('.btn-over').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定结束该折扣信息吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>