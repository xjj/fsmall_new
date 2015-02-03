<{include file="common/header.tpl"}>
<style type="text/css">
.regions li{ width:100px; float:left; margin-bottom:5px}
</style>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到配送方式列表</a>
		<a href="/<{$mod}>/<{$col}>/fee/<{$shipping_fee_data.shipping_id}>" class="btn btn3 lpad">返回到（<{$shipping_data.shipping_name}>）配送费用列表</a>
	</div>
	
	<hr class="line" />
	
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/fee/<{$shipping_fee_data.shipping_id}>/edit/<{$shipping_fee_data.id}>">
	<input type="hidden" name="shipping_id" value="<{$shipping_fee_data.shipping_id}>" />
	<input type="hidden" name="fee_id" value="<{$shipping_fee_data.fee_id}>" />
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
	  	<td width="80" class="ltd">配送费用名称</td>
		<td><input type="text" name="fee_name" class="txt" size="30" value="<{$shipping_fee_data.fee_name}>" /></td>
	  </tr>
	  <tr>
	  	<td class="ltd">首重费用</td>
		<td><input type="text" name="fee_base" class="txt" size="10" value="<{$shipping_fee_data.fee_base}>" />
			<span class="lpad">一千克内费用</span>
		</td>
	  </tr>
	  <tr>
	  	<td class="ltd">续重费用</td>
		<td><input type="text" name="fee_step" class="txt" size="10" value="<{$shipping_fee_data.fee_step}>" />
			<span class="lpad">超过一千克部分，每千克费用</span>
		</td>
	  </tr>
	  <tr>
	  	<td class="ltd">免费额度</td>
		<td><input type="text" name="free_amount" class="txt" size="10" value="<{$shipping_fee_data.free_amount}>" />
			<span class="lpad">订单金额超过多少，免快递费</span>
		</td>
	  </tr>
	  <tr>
	  	<td class="ltd">所辖地区</td>
		<td>
			<ul class="regions clearfix">
			<{if $province_items}>
			<{foreach $province_items as $item}>
				<li><input type="checkbox" name="region_id[]" value="<{$item.region_id}>" <{if $item.checked eq 1}>checked="checked"<{/if}> /><span class="lpad"><{$item.region_name}></span></li>
			<{/foreach}>
			<{/if}>
			</ul>
		</td>
	  </tr>
	  <tr>
	  	<td class="ltd"></td>
		<td><input type="submit" name="submit" class="btn2" value="提交" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>