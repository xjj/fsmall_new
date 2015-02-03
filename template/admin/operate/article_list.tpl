<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
        <a href="/<{$mod}>/<{$col}>/cat" class="btn btn3">文章分类</a>
        <a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加文章</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>ID</th>
		<th>标题</th>
        <th>文章分类</th>
		<th>短路径</th>
		<th>排序</th>
		<th>显示</th>
		<th>发布时间</th>
		<th>操作</th>
	  </tr>
	  <{if $article_items}>
	  <{foreach $article_items as $item}>
	  <tr>
		<td><{$item.art_id}></td>
		<td><{$item.title}></td>
        <td><a href="/<{$mod}>/<{$col}>?cat_id=<{$item.cat_id}>"><{$item.cat_name}></a></td>
		<td><{$item.short_url}></td>
		<td><{$item.displayorder}></td>
		<td>
		<{if $item.status eq 1}>
			<a href="/<{$mod}>/<{$col}>/hide/<{$item.art_id}><{if $params neq ''}>?<{$params}><{/if}>" class="green">是</a>
		<{else}>
			<a href="/<{$mod}>/<{$col}>/show/<{$item.art_id}><{if $params neq ''}>?<{$params}><{/if}>" class="red">否</a>
		<{/if}>
		</td>
		<td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.art_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.art_id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-del">删除</a>
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
		popup.confirm('确定要删除该公告吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>