<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到广告位列表</a>
		<a href="/<{$mod}>/<{$col}>/ads/<{$adp_data.adp_id}>/add" class="btn btn3">添加新广告图片</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th colspan="7">广告位 (<{$adp_data.adp_code}>) 的图片列表：</th>
	  </tr>
	  <tr>
		<th width="320">图片</th>
		<th>标题</th>
		<th>描述</th>
		<th>显示</th>
		<th>排序</th>
		<th>操作</th>
	  </tr>
	  <{if $ad_items}>
	  <{foreach $ad_items as $item}>
	  	<{if $adp_data.width gt 300}><{$pw = 300}><{else}><{$pw = $adp_data.width}><{/if}>
		<{if $adp_data.height eq 0}><{$ph = 'auto'}><{else}><{$ph = $adp_data.height*$pw/$adp_data.width}><{/if}>
	  <tr>
	  	<td><img src="<{$item.pic_url}>" style="width:<{$pw}>px;height:<{if $ph eq 'auto'}>auto<{else}><{$ph}>px<{/if}>;" />
		</td>
		<td><span style="vertical-align:middle"><{$item.title}></span>
			<{if $item.url neq ''}><a href="<{$item.url}>" target="_blank" class="link"></a><{/if}>
		</td>
		<td><{$item.content}></td>
		<td><{if $item.status eq 1}>
				<a href="/<{$mod}>/<{$col}>/ads/<{$item.adp_id}>/hide/<{$item.ad_id}>" class="green">是</a>
			<{else}>
				<a href="/<{$mod}>/<{$col}>/ads/<{$item.adp_id}>/show/<{$item.ad_id}>" class="red">否</a>
			<{/if}>	
		</td>
		<td><{$item.displayorder}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/ads/<{$item.adp_id}>/edit/<{$item.ad_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/ads/<{$item.adp_id}>/del/<{$item.ad_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr>
	  	<td colspan="6">广告图片为空！</td>
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