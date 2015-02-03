<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加新品牌</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="20">ID</th>
		<th width="160">LOGO</th>
		<th>品牌名</th>
		<th>网站</th>
		<th>类型</th>
		<th>上架时间</th>
		<th>显示</th>
		<th>操作</th>
	  </tr>
	  <{if $brand_items}>
	  <{foreach $brand_items as $item}>
	  <tr>
		<td><{$item.brand_id}></td>
		<td><img src="<{$item.logo}>" width="150" height="60" style=" border:1px solid #eee; padding:1px;" /></td>
		<td><{$item.brand_name}></td>
		<td><{$item.web_url}></td>
		<td><{$brand_types[$item.type]}></td>
		<td><{$item.add_time|date_format:'Y-m-d H:i:s'}></td>
		<td><{if $item.status eq 1}>
			<a href="/<{$mod}>/<{$col}>/hide/<{$item.brand_id}>" class="green">是</a>
		<{else}>
			<a href="/<{$mod}>/<{$col}>/show/<{$item.brand_id}>" class="red">否</a>
		<{/if}>
		</td>
		<td>
        	<a href="/<{$mod}>/<{$col}>/cat/<{$item.brand_id}>">品牌类目</a>
            <span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.brand_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.brand_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr>
	  	<td colspan="8">没有查询到品牌信息！</td>
	  </tr>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>

<{include file="common/footer.tpl"}>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定要删除该品牌吗？', this, function(){
			location.href = url;
		});
	});
});
</script>