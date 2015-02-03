<{include file="common/header.tpl"}>
<style type="text/css">
.ibox{ border:1px solid #eee; margin-bottom:20px;}
.ibox-title{ padding:12px 20px; background:#f9f9f9; border-bottom:1px solid #eee; border-top:1px solid #fff}
.ibox-content{ padding:20px;}
.ibox-content table{ font-size:12px;}
.ibox-content table td{ line-height:2}
.itbl td{ padding:10px;}
.xpad{ padding-left:2em; vertical-align:middle}
</style>
<div class="suplier-wrap">
	<div class="col-box">
		<a href="/order<{if $params neq ''}>?<{$params}><{/if}>" class="btn btn3"><{$LANG.BACKTO}><{$LANG.NAV_ORDER}></a>
	</div>
	<div class="ibox">
		<div class="ibox-title"><{$LANG.SEND_STEP_1}> [<{$order_data.order_sn}>]</div>
		<div class="ibox-content">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><a href="<{$order_data.sku_data.kr_url}>" target="_blank"><{$order_data.sku_data.product_name_kr}></a>
				<span class="xpad"><{$order_data.sku_data.product_sn}></span>
				<span class="orange xpad"><{$order_data.sku_data.props_kr}></span>
				<span class="xpad">₩<{$order_data.sku_data.price_kr}> × <span class="orange"><{$order_data.sku_data.number}></span>（<{$LANG.PRODUCT_UNIT}>）</span></td>
			  </tr>
			</table>
		</div>
	</div>
	
	<div class="ibox">
		<div class="ibox-title"><{$LANG.SEND_STEP_2}></div>
		<div class="ibox-content">
			<{$LANG.ORDER_CONSIGNEE}>：
			<{$order_data.consignee}><BR />
			<{$LANG.ORDER_MOBILE}>：
			<{$order_data.mobile}><BR />
			<{$LANG.ORDER_ADDRESS}>：
			<{$order_data.province_name}>
			<{$order_data.city_name}>
			<{$order_data.county_name}>
			<{$order_data.address}>
			（<{$LANG.ORDER_ZIPCODE}>：<{$order_data.zipcode}>）
		</div>
	</div>
	
	<div class="ibox">
		<div class="ibox-title"><{$LANG.SEND_STEP_3}></div>
		<div class="ibox-content">
			<form name="form" id="form11" action="/order/send2.php">
			<input type="hidden" name="order_id" value="<{$order_data.order_id}>" />
			<input type="hidden" name="sku_id" value="<{$order_data.sku_id}>" />
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2" style="margin-bottom:0">
			  <tr>
				<td class="ltd"><{$LANG.KR_BARCODE}></td>
				<td><textarea class="txt" rows="3" cols="80" name="bc"><{$order_data.sku_data.barcodes}></textarea>
					<span class="lpad"><{$LANG.KR_BARCODE_NOTICE}></span>
				</td>
			  </tr>
			  <!--
			  <tr>
				<td width="60" class="ltd"><{$LANG.KR_SHIPPING_NAME}></td>
				<td><select name="kr_shipping_code">
					<option value="">--<{$LANG.KR_SHIPPING_NAME}>--</option>
					</select></td>
			  </tr>
			  <tr>
				<td class="ltd"><{$LANG.KR_SHIPPING_NUMBER}></td>
				<td><input type="text" class="txt" size="50" name="kr_shipping_number" /></td>
			  </tr>
			  -->
			  <tr>
				<td class="ltd"></td>
				<td><input type="button" name="submit" id="submit" class="btn2" value="<{$LANG.SUBMIT_SEND}>" /></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>