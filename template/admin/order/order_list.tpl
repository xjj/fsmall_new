<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<form name="f" id="order-form" method="get" action="/<{$mod}>/<{$col}>">
		<select name="order_status">
		<option value="all">所有订单</option>
		<{if $order_status_items}>
		<{foreach $order_status_items as $key => $val}>
		<option value="<{$key}>" <{if $key|cat:'' eq $smarty.get.order_status|cat:''}>selected="selected"<{/if}>><{$val}></option>
		<{/foreach}>
		<{/if}>
		</select>
		<span class="lpad">订单号：</span>
		<input type="text" name="order_sn" class="txt" value="<{$smarty.get.order_sn}>" />
		<span class="lpad">关键词：</span>
		<input type="text" name="k" class="txt" value="<{$smarty.get.k}>" />
		<span class="lpad">下单时间：</span>
		<input type="text" name="start_time" id="st" class="txt" size="17" value="<{$smarty.get.start_time}>" />
		<span class="mpad">~</span>
		<input type="text" name="end_time" id="et" class="txt" size="17" value="<{$smarty.get.end_time}>" />
        <a href="javascript:;" onclick="$('#order-form').submit();" class="btn">查询</a>
		</form>
	</div>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>订单编号</th>
		<th>下单时间</th>	
		<th>收件人与收货地址</th>
		<th>最后留言时间</th>	
		<th>订单金额</th>
		<th>快递费用</th>
		<th>支付金额</th>
		<th>返还金额</th>
		<th>订单状态</th>
		<th>操作</th>
	  </tr>
	  <{if $order_items}>
	  <{foreach $order_items as $item}>
	  <tr>
		<td width="110"><a href="/<{$mod}>/<{$col}>/detail/<{$item.order_id}><{if $params neq ''}>?<{$params}><{/if}>"><{$item.order_sn}></a></td>
		<td><{$item.uname}><br /><{$item.add_time|date_format:'m-d H:i'}></td>
		<td><{$item.consignee}> - <{$item.mobile}><br /><{$item.province_name}> <{$item.city_name}> <{$item.county_name}> <{$item.address}></td>
		<td><{if $item.message_time gt 0}>
				<{$item.message_time|date_format:'m-d H:i'}>
			<{/if}>
		</td>
		<td><span class="orange">￥<{$item.order_amount}></span></td>
		<td>￥<{$item.shipping_fee}></td>
		<td><{if $item.pay_amount gt 0}><span class="orange">￥<{$item.pay_amount}></span><{/if}></td>
		<td><{if $item.refund_amount gt 0}>￥<{$item.refund_amount}><{/if}></td>
		<td><{$item.order_status_text}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/detail/<{$item.order_id}><{if $params neq ''}>?<{$params}><{/if}>">查看</a>
			<{if $item.order_status eq 0}>
				<span class="dl">|</span>
				<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/cancle/<{$item.order_id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-cancle">取消</a>
			<{/if}>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr>
	  	<td colspan="10">查询结果为空！</td>
	  </tr>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();
	
	$('.btn-cancle').click(function(){
		var href = $(this).attr('data-href');
		popup.confirm('确定取消该订单吗？', this, function(){
			location.href = href;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>

