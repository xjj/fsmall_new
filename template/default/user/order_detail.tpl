<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/order.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/order">我的订单</a><span>&gt;</span>订单详情
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">订单详情：<{$order_data.order_sn}></div>
		<div class="order-box2">
			<div class="order-title clearfix">
				<div class="z">订单信息</div>
				<div class="y">订单状态：<{$order_data.order_status_text}></div>
			</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-tbl">
			  <tr>
				<td>
					<ul class="ord-box">
						<li>配送方式：<{$order_data.shipping_name}></li>
						<li>收件人姓名：<{$order_data.consignee}></li>
						<li>联系方式：<{$order_data.mobile}></li>
						<li>收货地址：<{$order_data.province_name}> <{$order_data.city_name}> <{$order_data.county_name}> <{$order_data.address}></li>
					</ul>
					<ul class="ord-box ord-shipping-pay">
						<li>支付方式：<{$order_data.pay_name}></li>
						<li>商品金额：￥<{$order_data.product_amount}></li>
						<li>快递运费：￥<{$order_data.shipping_fee}></li>
						<li>订单金额：<span class="orange">￥<{$order_data.order_amount}></span></li>
					</ul>
					<{if $order_data.message neq ''}>
					<div class="ord-box ord-shipping-pay"><{$order_data.message}></div>
					<{/if}>
				</td>
			  </tr>
			</table>
		</div>
		
		<div class="order-box2">
			<div class="order-title">商品清单</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-tbl">
			  <{if $order_items}>
			  <{foreach $order_items as $item}>
			  <tr>
				<td width="60" class="pad25"><a href="/product/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" class="sku-img" width="60" height="60" /></a></td>
				<td><a href="/product/<{$item.prd_id}>" target="_blank"><{$item.product_name}></a><br />
					<span class="props">
						<{if $item.prop_value}>
						<{foreach $item.prop_value as $val}><{$val}>; <{/foreach}>
						<{/if}>
					</span>
				</td>
				<td><{$item.product_sn}></td>
				<td>￥<{$item.price}></td>
				<td><{$item.number}> 件</td>
				<td>
					<{if $item.product_status gt 0}>
						<a href="javascript:;" data-orderid="<{$item.order_id}>" data-opid="<{$item.op_id}>">查看物流</a>
					<{/if}>
				</td>
			  </tr>
			  <{/foreach}>
			  <{/if}>
			</table>
		</div>
		
		<div class="ord-btn">
			<{if $order_data.order_status eq 0}>
				<a href="/pay/<{$order_data.order_sn}>" class="btn2">去支付</a>
			<{/if}>
		</div>
	</div>
</div>

<script type="text/javascript" src="/js/order.js"></script>
<script type="text/javascript">
$(function(){
	$('.order-tbl').each(function(){
		$('tr:odd', this).addClass('odd');
	});
});
</script>
<{include file="common/footer.tpl"}>