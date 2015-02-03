<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加会员等级</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>ID</th>
		<th>等级名称</th>
		<th>折扣</th>
		<th>状态</th>
		<th>操作</th>
	  </tr>
	  <{if $grade_items}>
	  <{foreach $grade_items as $item}>
	  <tr>
		<td><{$item.grade_id}></td>
		<td><{$item.grade_name}></td>
		<td><{$item.discount}>%</td>
		<td><{if $item.status eq 1}><a href="/<{$mod}>/<{$col}>/hide/<{$item.grade_id}>" class="green">有效</a><{else}><a href="/<{$mod}>/<{$col}>/show/<{$item.grade_id}>" class="red">无效</a><{/if}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/brand/<{$item.grade_id}>">品牌折扣设置</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.grade_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.grade_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
</div>
<script type="text/javascript">
$(function(){
	//删除
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		var em = this;
		popup.confirm('确定删除该会员等级信息吗？', em, function(){
			popup.confirm.close();
			popup.confirm('真的确定要删除该信息？', em, function(){
				location.href = url;
			});
		});
	});
});
</script>
<{include file="common/footer.tpl"}>