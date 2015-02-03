<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加新广告位</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="20">ID</th>
		<th>广告位代码</th>
		<th width="30%">广告位描述</th>
		<th>状态</th>
		<th>宽度</th>
		<th>高度</th>
		<th>操作</th>
	  </tr>
	  <{if $adp_items}>
	  <{foreach $adp_items as $item}>
	  <tr>
	  	<td><{$item.adp_id}></td>
		<td><{$item.adp_code}></td>
		<td><{$item.content}></td>
		<td><{if $item.time_status eq 0}>
				<span class="gray">未开始</span>
			<{elseif $item.time_status eq 2}>
				<span class="red">已结束</span>
			<{else}>
				<span class="green">进行中</span>
			<{/if}>
		</td>
		<td><{$item.width}></td>
		<td><{if $item.height eq 0}>auto<{else}><{$item.height}><{/if}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/ads/<{$item.adp_id}>">设置</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.adp_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.adp_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr>
	  	<td colspan="8">广告位为空！</td>
	  </tr>
	  <{/if}>
	 </table>
</div>

<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定要删除该广告位吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>