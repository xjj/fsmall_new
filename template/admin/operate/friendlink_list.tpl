<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加友情链接</a>	
	</div>
	
	<table border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th>标题</th>
		<th>图片</th>
		<th>描述</th>
		<th>排序</th>
		<th>显示</th>
		<th>操作</th>
	  </tr>
	  <{if $friendlink_items}>
	  <{foreach $friendlink_items as $item}>
	  <tr>
	  	<td><{$item.title}></td>
		<td><{if $item.logo neq ''}><img src="<{$item.logo}>" width="120" height="60" /><{/if}></td>
		<td><{$item.content}></td>
		<td><{$item.displayorder}></td>
		<td><{if $item.status eq 1}><a href="/<{$mod}>/<{$col}>/hide/<{$item.id}>" class="green">是</a><{else}><a href="/<{$mod}>/<{$col}>/show/<{$item.id}>" class="red">否</a><{/if}>
		</td>
		<td>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr>
	  	<td colspan="6">友情链接为空！</td>
	  </tr>
	  <{/if}>
	</table>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该友情链接吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>