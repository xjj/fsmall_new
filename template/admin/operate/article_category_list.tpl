<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
    	<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到文章列表</a>
		<a href="/<{$mod}>/<{$col}>/cat/add" class="btn btn3">添加文章分类</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>分类名称</th>
		<th>排序</th>
        <th>有效</th>
		<th>操作</th>
	  </tr>
	  <{if $cat_items}>
	  <{foreach $cat_items as $item}>
	  <tr>
		<td><{$item.cat_name}></td>
		<td><{$item.displayorder}></td>
        <td><{if $item.status eq 1}>
        		<a href="/<{$mod}>/<{$col}>/cat/hide/<{$item.cat_id}>" class="green">是</a>
        	<{else}>
            	<a href="/<{$mod}>/<{$col}>/cat/show/<{$item.cat_id}>" class="red">否</a>
            <{/if}>
        </td>
		<td>
			<a href="/<{$mod}>/<{$col}>/cat/edit/<{$item.cat_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/cat/del/<{$item.cat_id}>" class="btn-del">删除</a>
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
		popup.confirm('确定要删除吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>