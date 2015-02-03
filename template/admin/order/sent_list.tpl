<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到发货页面</a>
	</div>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
		<th>已发货订单商品查询</th>
	  </tr>
	  <tr>
		<td>
		<form name="f" id="searchform" method="get" action="/<{$mod}>/<{$col}>/sent">
		<span style="mpad">订单号：</span>
		<input type="text" name="order_sn" class="txt" size="20" value="<{$smarty.get.order_sn}>" />
		<span class="lpad">条码：</span>
		<input type="text" name="barcode" class="txt" size="11" value="<{$smarty.get.barcode}>" />
		<span class="lpad">购买人/收件人：</span>
		<input type="text" name="uname" class="txt" size="10" value="<{$smarty.get.uname}>" />
		
		<select name="shipping_code">
		<option value="0">所有快递</option>
		<{if $shipping_items}>
		<{foreach $shipping_items as $item}>
		<option value="<{$item.shipping_code}>" <{if $smarty.get.shipping_code eq $item.shipping_code}>selected="selected"<{/if}>><{$item.shipping_name}></option>
		<{/foreach}>
		<{/if}>
		</select>
		<span class="lpad">发货时间：</span>
		<input type="text" name="start_time" id="st" class="txt" size="15" value="<{$smarty.get.start_time}>" /> ~
		<input type="text" name="end_time" id="et" class="txt" size="15" value="<{$smarty.get.end_time}>" />
		<a href="javascript:;" class="btn" onClick="document.getElementById('searchform').submit();">查 询</a>
		</form>
		</td>
	  </tr>
	</table>
	
	<div id="order-items">
	<{if $order_items}>
	<{foreach $order_items as $order_data}>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl" id="order-<{$order_data.order_id}>">
		  <tr>
			<th colspan="10">
				<div class="clearfix">
					<div class="z">
						<span class="rpad">订单号：<{$order_data.order_sn}></span>
						<span class="lpad"><a href="javascript:;" class="icon_message btn-message" title="查看留言" data-value="<{$order_data.order_id}>">订单留言</a></span>
						
					</div>
					<div class="y">
						<{if $order_data.cn_recv_number + $order_data.cn_send_number eq $order_data.total_number}>
							<{$class='green'}>
						<{else}>
							<{$class='red'}>
						<{/if}>
						<span class="<{$class}>">
							国内到货：<{$order_data.cn_recv_number}>，
							国内发货：<{$order_data.cn_send_number}>，
							商品总数：<{$order_data.total_number}>
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
					</div>
					<div class="y">
						
					</div>
				</div>
			</th>
		  </tr>
		  <{foreach $order_data.items as $item}>
		  <tr data-value="<{$item.barcode}>">
			<td width="80"><a href="/product/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" class="sku-img" width="60" height="60" style=" display:block" /></a></td>
			<td><a href="/product/<{$item.prd_id}>" target="_blank"><{$item.product_name}></a> <{$item.product_sn}> <{if $item.is_spot eq 1}><b class="orange">(现货)</b><{/if}>
			</td>
			<td width="18%"><{if $item.prop_value}><{foreach $item.prop_value as $item2}><{$item2}>; <{/foreach}><{/if}></td>
			<td width="100">￥<{$item.price}></td>
			<td width="100"><{$item.barcode}></td>
			<td width="160">
				<{$item.recv_time|date_format:'Y-m-d H:i'}>
				
			</td>
			<td>
				<{$order_data.shipping_name}>：<{$item.shipping_number}><br />
                <{$item.send_time|date_format:'Y-m-d H:i'}> 
			</td>
		  </tr>
		  <{/foreach}>
		</table>
	<{/foreach}>
	<{/if}>
	</div>
	<{$pagebox}>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();
	
	var wrapbox = $('#order-items');
	var search_barcode = '<{$smarty.get.barcode}>';
    
	$('.btn-message', wrapbox).click(function(){
		message_click(this);
	});
	
    if ($.trim(search_barcode) != ''){
		//标记出当前查询的商品
		var trs = $('tr', wrapbox).has('td');
		for (var i = 0; i < trs.length; i++){
			var bc = $(trs[i]).attr('data-value');
			if (bc == search_barcode){
				$(trs[i]).addClass('focus');
				break;
			}
		}
	}
    
	//获取消息
	function message_click(em){
		var order_id = $(em).attr('data-value');
		$.post('/<{$mod}>/<{$col}>/message', {'order_id' : order_id}, function(dt){
			var d = json(dt);
			
			var tpl = '<div style=" max-width:600px">';
			
			if (d.error == 0){
				if (d.remark != ''){
					tpl += '<div style="border-bottom:1px solid #eee; margin-bottom:10px; padding-bottom:10px;">备注：'+ d.remark +'</div>';
				}
				
				if (d.message){
					tpl += '<ul>';
					var data = d.message;
					for (var i in data){
						tpl += '<li>'+ data[i].content +'<span style="padding-left:1em">'+ data[i].addtime +'</span></li>';
					}
					tpl += '</ul>';
				}
			} else {
				tpl += d.message;
			}
			tpl += '</div>';
			
			
			popup.close('pop-message');
			
			var tbox = popup.box({
				'id' : 'pop-message',
				'title' : '订单留言',
				'content' : tpl,
				'follow' : em,
				'yesBtn' : false
			});
		});
	}
});
</script>
<{include file="common/footer.tpl"}>