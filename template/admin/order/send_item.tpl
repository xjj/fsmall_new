<{if $order_data}>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl" id="order-<{$order_data.order_id}>">
	  <tr>
		<th colspan="10">
			<div class="clearfix">
				<div class="z">
					<span class="rpad">订单号：<{$order_data.order_sn}></span>
					<a href="javascript:;" class="icon_message btn-message" title="查看留言" data-value="<{$order_data.order_id}>">订单留言</a>
					
				</div>
				<div class="y">
					<{if $order_data.cn_recv_number + $order_data.cn_send_number eq $order_data.total_number}>
						<{$class='green'}>
					<{else}>
						<{$class='red'}>
					<{/if}>
					<span class="<{$class}>">
						国内已到货：<{$order_data.cn_recv_number}>，
						国内已发货：<{$order_data.cn_send_number}>，
						订单商品数：<{$order_data.total_number}>
					</span>
				</div>
			</div>
		</th>
	  </tr>
	  <tr>
	  	<th colspan="10">
			<div class="clearfix">
				<div class="z">
					<span class="mpad"><{$order_data.shipping_name}>：</span>
					<span class="mpad"><{$order_data.consignee}>，</span>
					<span class="mpad"><{$order_data.mobile}>，</span>
					<span class="mpad">
						<{$order_data.province_name}>
						<{$order_data.city_name}>
						<{$order_data.county_name}>
						<{$order_data.address}>
					</span>
					<span class="lpad"><a href="/<{$mod}>/<{$col}>/print_waybill/<{$order_data.order_id}>" target="_blank" class="icon_printer btn-print" title="打印快递单">打印快递单</a></span>
                    <span class="lpad"><a href="/<{$mod}>/<{$col}>/print_electwaybill/<{$order_data.order_id}>" target="_blank" class="icon_printer_elect btn-print" title="打印电子面单">打印电子面单</a></span>
				</div>
				<div class="y">
					
				</div>
			</div>
		</th>
	  </tr>
	  <{foreach $order_data.items as $item}>
	  <tr data-value="<{$item.barcode}>">
	  	<td width="16"><input type="checkbox" name="barcode[]" value="<{$item.barcode}>" /></td>
		<td width="80" style=" text-align:center"><a href="/product/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" width="60" height="60" style=" vertical-align:top" /></a></td>
		<td><a href="/product/<{$item.prd_id}>" target="_blank"><{$item.product_name}></a> <{$item.product_sn}> <{if $item.is_spot eq 1}><b class="orange">(现货)</b><{/if}>
		</td>
		<td width="18%">
        	<{if $item.prop_value}><{foreach $item.prop_value as $item2}><{$item2}>; <{/foreach}><{/if}>
        </td>
		<td width="100">￥<{$item.price}></td>
		<td width="100"><{$item.barcode}></td>
		<td width="160">
			<{if $item.recv_time gt 0}>
				<div><{$item.recv_time|date_format:'Y/m/d'}> <{$item.recv_time|date_format:'H:i'}></div>
			<{/if}>
		</td>
		<td width="22%">
        	<div class="send-item">
                <select name="spcode" class="normal">
                <{if $shipping_items}>
                <{foreach $shipping_items as $item3}>
                <option value="<{$item3.shipping_code}>" <{if $item3.shipping_code eq $order_data.shipping_code}>selected="selected"<{/if}>><{$item3.shipping_name}></option>
                <{/foreach}>
                <{/if}>
                </select>
                <div style="height:5px; line-height:5px; overflow:hidden"></div>
                <input type="text" class="txt" name="spno" size="20" />
                <a href="javascript:;" class="btn btn-send-single" data-value="<{$item.barcode}>">发货</a>
			</div>
		</td>
	  </tr>
	  <{/foreach}>
	</table>
<{/if}>