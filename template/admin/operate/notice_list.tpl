<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加公告</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="20">ID</th>
		<th width="40%">标题</th>
		<th>权限</th>
		<th>置顶</th>
		<th>显示</th>
		<th>发布时间</th>
		<th>操作</th>
	  </tr>
	  <{if $notice_items}>
	  <{foreach $notice_items as $item}>
	  <tr>
		<td><{$item.id}></td>
		<td><a href="#" target="_blank"><{$item.title}></a></td>
		<td><{if $item.auth eq 0}>
				所有人可见
			<{elseif $item.auth eq 1}>
				注册会员及以上可见
			<{elseif $item.auth eq 2}>
				批发会员及以上可见
			<{/if}>
		</td>
		<td><{if $item.is_top eq 1}><span class="green">是</span><{else}><span>否</span><{/if}></td>
		<td>
		<{if $item.status eq 1}>
			<a href="/<{$mod}>/<{$col}>/hide/<{$item.id}><{if $smarty.get.page and $smarty.get.page gt 0}>?page=<{$smarty.get.page}><{/if}>" class="green">是</a>
		<{else}>
			<a href="/<{$mod}>/<{$col}>/show/<{$item.id}><{if $smarty.get.page and $smarty.get.page gt 0}>?page=<{$smarty.get.page}><{/if}>" class="red">否</a>
		<{/if}>
		</td>
		<td><{$item.add_time|date_format:'Y-m-d H:i:s'}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.id}><{if $smarty.get.page and $smarty.get.page gt 0}>?page=<{$smarty.get.page}><{/if}>" class="btn-del">删除</a>
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