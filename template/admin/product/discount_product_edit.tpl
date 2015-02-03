<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/product" class="btn btn3">返回到商品折扣列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/product/edit/<{$disc_data.id}>">
	<input type="hidden" name="id" value="<{$disc_data.id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">商品ID</td>
		<td><input type="text" name="prd_id" class="txt" value="<{$disc_data.prd_id}>"  /></td>
	  </tr>
	  <tr>
		<td class="ltd">用户ID</td>
		<td><input type="text" name="uid" class="txt" value="<{$disc_data.uid}>" />
			<span class="lpad">不填写则对所有用户有效</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">折扣值</td>
		<td><input type="text" name="discount" class="txt" size="10" value="<{$disc_data.discount}>" />
			<span class="lpad">1 ~ 99 之间，即商品价格降低 n%</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">商品价格</td>
		<td><input type="text" name="price" class="txt" size="10" value="<{if $disc_data.price gt 0}><{$disc_data.price}><{/if}>" />
			<span class="lpad">优惠销售价格（￥），折扣值设置后该值无效</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">开始时间</td>
		<td><input type="text" name="start_time" id="st" class="txt" size="20" value="<{$disc_data.start_time|date_format:'Y-m-d H:i'}>" />
			<span class="lpad">日期格式：YYYY-MM-DD HH:MM，时和分钟都要填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">结束时间</td>
		<td><input type="text" name="end_time" id="et" class="txt" size="20" value="<{$disc_data.end_time|date_format:'Y-m-d H:i'}>" />
			<span class="lpad">日期格式：YYYY-MM-DD HH:MM</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>

<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();
});
</script>
<{include file="common/footer.tpl"}>