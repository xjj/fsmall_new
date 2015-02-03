<{include file="common/header.tpl"}>
<style type="text/css">
.sku-img{ display:block; width:60px; height:60px;}
</style>
<div class="admin-wrap">
	<div class="col-box">
		<a class="btn btn3" href="/admin/<{$mod}>/<{$col}>/show/<{$order_data.order_id}>">返回到订单信息页面</a>
	</div>
	
	<form name="f" method="post" action="/admin/<{$mod}>/<{$col}>/sku_edit/<{$order_data.order_id}>/<{$sku_data.sku_id}>">
	<input type="hidden" name="order_id" value="<{$order_data.order_id}>" />
	<input type="hidden" name="sku_id" value="<{$sku_data.sku_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl tbl-border nohover">
	  <tr>
		<th width="60">商品图片</th>
		<th>商品信息</th>
		<th>商品编号</th>
		<th>是否现货</th>
		<th>数量（件）</th>
		<th>单价（￥）</th>
		<th>韩网价格</th>
	  </tr>
	  <tr>
		<td ><a href="/product/<{$sku_data.prd_id}>" target="_blank"><img src="<{$sku_data.picurl.pic_small}>" class="sku-img" width="60" height="60" /></a></td>
		
		<td><a href="/product/<{$sku_data.prd_id}>" target="_blank"><{$sku_data.prd_name}></a><br /><span class="props"><{$sku_data.props}></span></td>
		<td><{$sku_data.prd_sn}></td>
		<td><{if $sku_data.is_spot eq 1}>是<{else}>否<{/if}></td>
		<td><input type="text" name="number" value="<{$sku_data.number}>" class="txt" size="8" /></td>
		<td><input type="text" name="price" value="<{$sku_data.price}>" class="txt" size="10" /></td>
		<td>₩<{$sku_data.price_kr}></td>
	  </tr>
	</table>
	<input type="submit" name="submit" class="btn2" value="提交更新" />
	</form>
</div>
<{include file="common/footer.tpl"}>