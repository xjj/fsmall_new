<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user">会员中心</a><span>&gt;</span><a href="/user/change_or_return">退换货明细</a>
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
		  <tr>
		  	<th>订单号</th>
            <th>商品条码</th>
			<th>商品编号</th>
			<th>退货类型</th>
			<th>审核状态</th>
			<th>申请时间</th>
            <th>审核时间</th>
			<th>操作</th>
		  </tr>
		  <tr>
		  	<td>2014052032736</td>
            <td>2548755</td>
			<td>yubsshop-25481</td>
			<td>质量问题</td>
			<td>待处理</td>
			<td>2014-05-18 18:41</td>
            <td>2014-05-18 18:41</td>
			<td><a href="#">查看</a><span class="dl">|</span><a href="#">留言</a><span class="dl">|</span><a href="#">删除</a></td>
		  </tr>
		</table>
	</div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
tabLine();
</script>