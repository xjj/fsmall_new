<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加商品类目</a>
	</div>
	<div class="alert-box">
		商品类目和属性，不能随便添加和更新，如果需要这里的操作，请与管理员联系。
	</div>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		
		<th>类目名称</th>
		<th>类目ID</th>
		<th>淘宝类目ID</th>
		<th>淘宝类目名称</th>
		<th>重量（克）</th>
		<th>排序</th>
		<th>显示</th>
		<th>操作</th>
	  </tr>
	  <{if $category_items}>
	  <!--三级循环输出类目-->
	  <{foreach $category_items as $item}>
	  	<{include file="product/category_item.tpl" itemdata=$item layer=1 isend=$item@last}>
	  	<{if $item.items}>
			<{foreach $item.items as $item1}>
				<{include file="product/category_item.tpl" itemdata=$item1 layer=2 isend=$item1@last}>
				<{if $item1.items}>
					<{foreach $item1.items as $item2}>
						<{include file="product/category_item.tpl" itemdata=$item2 layer=3 isend=$item2@last parent_id=$item.cat_id}>
					<{/foreach}>
				<{/if}>
			<{/foreach}>
		<{/if}>
	  <{/foreach}>
	  <{/if}>
	</table>
</div>
<script type="text/javascript">
$(function(){
	//子类目显示与隐藏
	$('.Tab').click(function(){
		var btn = $(this);
		var catid = btn.attr('data-catid');
		var children = $('.children-'+ catid);
		if (btn.hasClass('Tabclose')){
			btn.removeClass('Tabclose');
			children.show();
		} else {
			btn.addClass('Tabclose');
			children.hide();
		}
		//刷新隔行背景显示
		tabLine();
	});
	
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该类目吗？', this, function(){
			location.href= url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>
