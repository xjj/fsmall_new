<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到订单列表</a>
	</div>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>商品图片</th>
		<th>商品信息</th>
		<th>商品编号</th>
		<th>现货</th>
		<th>商品单价</th>
		<th>韩网价格</th>
		<th>数量</th>
		<th>快递信息</th>
		<th>商品状态</th>
		<th>操作</th>
	  </tr>
	  <{if $product_items}>
	  <{foreach $product_items as $item}>
	  <tr>
		<td width="60"><a href="/product/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" class="sku-img" width="60" height="60" style=" display:block" /></a></td>
		<td><a href="/product/<{$item.prd_id}>" target="_blank"><{$item.product_name}></a><br />
			<span class="props">
				<{if $item.prop_value}><{foreach $item.prop_value as $val}><{$val}>; <{/foreach}><{/if}>
			</span>
		</td>
		<td><{$item.product_sn}></td>
		<td><{if $item.is_spot eq 1}><span class="red">现货</span><{/if}></td>
		<td>￥<{$item.price}></td>
		<td>₩<{$item.price_kr}></td>
		<td><{$item.number}></td>
		<td><{if $item.cn_shipping_number neq ''}>
				<span class="mpad"><{$item.cn_shipping_name}> <{$item.cn_send_time|date_format:'m/d H:i'}> #<{$item.cn_shipping_number}></span>
				<span class="lpad"><a href="javascript:;">编辑</a></span>
			<{/if}>
		</td>
		<td><{$item.product_status_text}></td>
		<td>
			<{if $order_data.order_status eq 0}>
				<!--未付款的商品可以取消-->
				<{if $item.order_status eq 0}>
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_cancle/<{$item.order_id}>/<{$item.op_id}>" class="btn-cancle">取消</a>
					<span class="dl">|</span>
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_soldout/<{$item.order_id}>/<{$item.op_id}>" class="btn-soldout">断货</a>
				<{/if}>
			<{elseif $order_data.order_status eq 1 OR $order_data.order_status eq 2}>
				<!--已付款和已确认的订单商品可以取消和断货-->
				<{if $item.order_status eq 0 OR $item.order_status eq 1}>
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_cancle/<{$item.order_id}>/<{$item.op_id}>" class="btn-cancle">取消</a>
					<span class="dl">|</span>
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_soldout/<{$item.order_id}>/<{$item.op_id}>" class="btn-soldout">断货</a>
				<{/if}>
			<{elseif $order_data.order_status eq 3 OR $order_data.order_status eq 4}>
				<!--已订货的订单商品-->
				<{if $item.order_status eq 0 OR $item.order_status eq 1}>
					<!--未发货的商品可以断货-->
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_cancle/<{$item.order_id}>/<{$item.op_id}>" class="btn-cancle">取消</a>
					<span class="dl">|</span>
					<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/op_soldout/<{$item.order_id}>/<{$item.op_id}>" class="btn-soldout">断货</a>
				<{/if}>
			<{/if}>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	
	<div class="col-box clearfix">
		<div class="z">
			<span class="rpad">订单状态：<{$order_data.order_status_text}></span>
		</div>
		<div class="y">
			商品总金额：￥<{$order_data.product_amount}> ，
			快递费：￥<{$order_data.shipping_fee}> ，
			订单总金额：<span class="orange">￥<{$order_data.order_amount}></span>
			<{if $order_data.pay_status eq 1}>，已支付金额：<span class="orange">￥<{$order_data.pay_amount}></span><{/if}>
			<{if $order_data.refund_amount gt 0}>，已返还金额：<span class="orange">￥<{$order_data.refund_amount}></span><{/if}>
		</div>
	</div>
	
	<hr class="line" />
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	 
	  <tr>
		<td width="80" class="ltd">收件人姓名</td>
		<td><{$order_data.consignee}></td>
	  </tr>
	  <tr>
		<td class="ltd">收件人地址</td>
		<td><{$order_data.province_name}>
			<{$order_data.city_name}>
			<{$order_data.county_name}>
			<{$order_data.address}>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">邮政编码</td>
		<td><{$order_data.zipcode}></td>
	  </tr>
	  <tr>
		<td class="ltd">收件人电话</td>
		<td><{$order_data.mobile}></td>
	  </tr>
	  <tr>
		<td class="ltd">配送方式</td>
		<td><span class="rpad" style="padding-right:1em"><{$order_data.shipping_name}></span>
			<{if $order_data.order_status eq 0}><a href="javascript:;" data-value="<{$order_data.shipping_code}>" class="btn btn3" id="btn-shipping">编辑</a><{/if}>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">支付方式</td>
		<td><span class="rpad" style="padding-right:1em"><{$order_data.pay_name}></span>
			<{if $order_data.order_status eq 0}><a href="javascript:;" data-value="<{$order_data.pay_code}>" class="btn btn3" id="btn-payment">编辑</a><{/if}>
		</td>
	  </tr>
	   <tr>
	  	<td class="ltd">下单时间</td>
		<td><{$order_data.add_time|date_format:'Y-m-d'}> <{$order_data.add_time|date_format:'H:i'}></td>
	  </tr>
	  <tr>
		<td class="ltd">客户留言</td>
		<td><{$order_data.message}></td>
	  </tr>
	</table>
	
	<hr class="line" />
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
	  	<th colspan="2">订单留言</th>
	  </tr>
	  <tr>
	  	<td width="320" style="border-right:1px solid #eee; padding-right:10px" valign="top">
			<form name="mb" method="post" action="/<{$mod}>/<{$col}>/message/<{$order_data.order_id}>">
			<input type="hidden" name="order_id" value="<{$order_data.order_id}>" />
			<textarea name="content" cols="50" rows="2" class="txt" style="margin-bottom:10px;"></textarea>
			<input type="submit" value="发布留言" name="submit" class="btn" />
			</form>
		</td>
	  	<td valign="top">
			<{if $order_message_items}>
				<table class="tbl-nohover">
					<{foreach $order_message_items as $item}>
					<tr>
						<td style=" padding-right:1em">
							<{if $item.adm_uname neq ''}>
								管理员（<{$item.adm_uname}>）
							<{else}>
								<{$item.uname}>
							<{/if}>
						</td>
						<td style=" padding-right:1em"><{$item.content}><span style="padding-left:1em">[<{$item.add_time|date_format:'m-d H:i'}>]</span>
						</td>
					</tr>
					<{/foreach}>
				</table>
			<{/if}>
		</td>
	  </tr>
	</table>
	<{if $order_data.order_status eq 1}>
	<span class="rpad"><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/confirm/<{$order_data.order_id}>" class="btn2" id="btn-confirm">订单确认</a></span>
	<{/if}>
	<{if $order_data.order_status eq 2}>
	<span class="rpad"><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/ordered/<{$order_data.order_id}>" class="btn2" id="btn-ordered">向韩方订货</a></span>
	<{/if}>
</div>
<script type="text/javascript">
$(function(){
	var order_id = '<{$order_data.order_id}>';
	
	//取消
	$('.btn-cancle').click(function(){
		var href = $(this).attr('data-href');
		popup.confirm('确定取消该商品吗？', this, function(){
			location.href = href;
		});
	});
	
	//断货
	$('.btn-soldout').click(function(){
		var href = $(this).attr('data-href');
		popup.confirm('确定该商品断货吗？', this, function(){
			location.href = href;
		});
	});
	
	//确认
	$('#btn-confirm').click(function(){
		var href = $(this).attr('data-href');
		
		var conf = {
			'title' : '确认提示',
			'content' : '确认操作流程：<br />1、检查支付金额，商品价格等是否正确<br />2、检查订单商品是否已断货<br />3、是否有需要取消的商品<br />4、是否有客户留言，等特殊要求，并已处理<br /><br />确认该订单吗？',
			'mask' : true
		};
		popup.box(conf, function(){
			this.close();
			location.href=href;
		});
	});
	
	//订货
	$('#btn-ordered').click(function(){
		var href = $(this).attr('data-href');
		
		var conf = {
			'title' : '订货提示',
			'content' : '该操作的影响：<br />1、商品状态待订货更新为已订货<br />2、生成唯一的商品条码<br />3、只有订货后的商品韩方才能看到！<br /><br />确定要向韩方订货吗？',
			'mask' : true
		};
		
		popup.box(conf, function(){
			location.href=href;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>
