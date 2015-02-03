<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加淘宝类目</a>
	</div>
	<div class="alert-box">
		商品三级类目对应的所有的淘宝类目。如要编辑与修改请与管理员联系，切记！！
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th>淘宝类目名称</th>
		<th>淘宝类目ID</th>
		<th>排序</th>
		<th>操作</th>
	  </tr>
	  <{if $tb_cat_items}>
	  <{foreach $tb_cat_items as $item}>
	  <tr>
		<td><{$item.tb_cat_name}></td>
		<td><{$item.tb_cat_id}></td>
		<td><{$item.displayorder}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/attr/<{$item.tb_cat_id}>">普通属性</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/prop/<{$item.tb_cat_id}>">销售属性</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.tb_cat_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" class="btn-del" data-href="/<{$mod}>/<{$col}>/del/<{$item.tb_cat_id}>">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr class="nohover">
	  	<td colspan="4">没有淘宝类目！</td>
	  </tr>
	  <{/if}>
	</table>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该类目吗？', this, function(){
			location.href= url;
		});
	});
});
</script>