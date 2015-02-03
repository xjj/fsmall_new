<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/order.css" />
<style type="text/css">
.btn-order-search{ border-radius:0; position:relative; margin-left:-1px; font-size:13px; font-weight:normal}
.btn-status-wrap{ padding-left:1px; }
.btn-status-wrap .btn{ border-radius:0; position:relative; margin-left:-1px; font-weight:normal; z-index:1}
.btn-status-wrap .btn2{ z-index:2}
</style>
<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>我的订单
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
		<div class="order-search clearfix">
			<div class="y">
			<FORM name="order-form" id="orderForm" method="get" action="/order">
			<input type="text" name="k" class="txt" value="<{$smarty.get.k}>" size="25" /><a href="javascript:;" class="btn btn-order-search" onclick="$('#orderForm').submit();">搜 索</a>
			</FORM>
			</div>
			<div class="z btn-status-wrap">
				<a href="/order/items/all" class="btn <{if $status eq 'all'}>btn2<{/if}>">所有订单</a><a href="/order/items/nopay" class="btn <{if $status eq 'nopay'}>btn2<{/if}>">待付款</a><a href="/order/items/nosend" class="btn <{if $status eq 'nosend'}>btn2<{/if}>">待发货</a><a href="/order/items/send" class="btn <{if $status eq 'send'}>btn2<{/if}>">待收货</a><a href="/order/items/receive" class="btn <{if $status eq 'receive'}>btn2<{/if}>">已收货</a>
			</div>
		</div>
		<{if $order_items}>
		<{foreach $order_items as $order_id => $row}>
		<div class="order-box">
			<div class="order-title clearfix">
				<div class="z">订单号：<a href="/order/<{$row.info.order_sn}>" target="_blank"><{$row.info.order_sn}></a>
					<span class="order-label">下单时间：<{$row.info.add_time|date_format:'Y-m-d H:i'}></span>
				</div>
				<div class="y">
					<span class="order-label">订单状态：<{$row.info.order_status_text}></span>
				</div>
			</div>
			<div class="order-detail clearfix">
				<div class="order-detail-prds">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-tbl">
					<{foreach $row.items as $item}>
					  <tr>
						<td width="60"><a href="/product/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" class="sku-img" width="60" height="60" /></a></td>
						<td width="240">
							<a href="/product/<{$item.prd_id}>" target="_blank"><{$item.product_name}></a><br />
							<{$item.product_sn}><br />
							<span class="props">
								<{if $item.prop_value}><{foreach $item.prop_value as $item2}><{$item2}>; <{/foreach}><{/if}>
							</span>
						</td>
						<td><{$item.consignee}></td>
						<td>￥<{$item.price}></td>
						<td><{$item.number}> 件</td>
						<td><span class="gray"><{$item.order_status_text}></span></td>
						<td width="70">
							<{if $item.product_status gt 0}>
								<a href="javascript:;" data-orderid="<{$item.order_id}>" data-opid="<{$item.op_id}>" class="btn-shipping">查看物流</a><br />
							<{/if}>
							<{if $item.product_status eq 6}>
								<a href="javascript:;" data-orderid="<{$item.order_id}>" data-opid="<{$item.op_id}>" class="btn-receive">确认收货</a><br />
							<{/if}>
							
							<{if $item.refund_status gt 0}>
								<a href="/user/refund/<{$item.op_id}>">退货详情</a>
							<{else}>
								<{if $item.refund_abled eq 1}>
									<a href="/user/refund/<{$item.op_id}>" class="btn-refund">申请退货</a><br />
								<{/if}>
							<{/if}>
						</td>
					  </tr>
					  <{/foreach}>
					</table>
				</div>
				<div class="order-detail-info">
					<p>订单总金额</p>
					<p class="orange">￥<{$row.info.order_amount}></p>
					<{if $row.info.shipping_fee gt 0}>
					<p>（含运费￥<{$row.info.shipping_fee}>）</p>
					<{/if}>
				</div>
				<div class="order-detail-info">
					<{if $row.info.order_status eq 0}>
					<p><a href="/pay/<{$row.info.order_sn}>" target="_blank" class="btn btn3">订单支付</a></p>
					<{/if}>
					
					<p><a href="/order/<{$row.info.order_sn}>" target="_blank">订单详情</a></p>
					<{if $row.info.order_status eq 0}>
						<p><a href="javascript:;" data-orderid="<{$row.info.order_id}>" class="btn-cancle">取消订单</a></p>
					<{/if}>
					<{if $row.info.order_status eq -1}>
						<p><a href="javascript:;" data-orderid="<{$row.info.order_id}>" class="btn-del">删除订单</a></p>
					<{/if}>
				</div>
			</div>
		</div>
		<{/foreach}>
		<{$pagebox}>
		<{else}>
			<div class="order-box2">
				<div class="order-title">提示</div>
				<div class="order-empty">订单查询结果为空！</div>
			</div>
		<{/if}>
	</div>
</div>

<script type="text/javascript" src="/js/order.js"></script>
<script type="text/javascript">
$(function(){
	$('.btn-cancle').click(function(){
		order.cancle(this);
	});
	$('.btn-del').click(function(){
		order.del(this);
	});
	$('.btn-receive').click(function(){
		order.receive(this);
	});
	$('.btn-shipping').click(function(){
		order.shipping(this);
	});
});
</script>
<{include file="common/footer.tpl"}>