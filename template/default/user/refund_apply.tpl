<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user">会员中心</a><span>&gt;</span><a href="/user/change_or_return">退换货明细</a><span>&gt;</span><a href="/user/change_or_return/apply">提交退换货申请</a>
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2 tbl2-1">
		  <tr>
		  	<td class="ltd" width="60">订单号：</td>
			<td><input type="text" name="ordersn" class="txt" size="30" /></td>
		  </tr>
		  <tr>
		  	<td class="ltd">商品编号：</td>
			<td><input type="text" name="goodssn" class="txt" size="30" /></td>
		  </tr>
		  <tr>
		  	<td class="ltd">数量：</td>
			<td><input type="text" name="number" class="txt" size="8" value="1" /></td>
		  </tr>
		  <tr>
		  	<td class="ltd">退货描述：</td>
			<td><textarea name="content" class="txt" cols="80" rows="4"></textarea></td>
		  </tr>
		  <tr>
		  	<td class="ltd">实拍图片：</td>
			<td><input type="text" class="txt txt2" readonly="readonly" name="pic1" size="40" />
				<a href="#" class="btn2">上传照片</a><br />
			</td>
		  </tr>
		  <tr>
		  	<td class="ltd"></td>
			<td><input type="submit" name="submit" class="btn2" value="提 交" /></td>
		  </tr>
		</table>
	</div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
tabLine();
</script>