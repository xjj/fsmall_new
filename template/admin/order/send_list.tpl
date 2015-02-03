<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/sent" class="btn btn3">已发货订单商品</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover" style="margin-bottom:-1px">
	  <tr>
		<th>商品到货扫描</th>
	  </tr>
	  <tr>
		<td><input type="text" class="txt" name="bc" id="input-scan" size="25" />
		<a href="javascript:;" class="btn" id="btn-scan">提交</a>
			<span id="receive-text" class="lpad orange"></span>
		</td>
	  </tr>
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
		<th>到货订单查询</th>
	  </tr>
	  <tr>
		<td>
		<form name="f" id="seaform" method="get" action="/<{$mod}>/<{$col}>">
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
		<option value="<{$item.shipping_code}>" <{if $smarty.get.sp eq $item.shipping_code}>selected="selected"<{/if}>><{$item.shipping_name}></option>
		<{/foreach}>
		<{/if}>
		</select>
		<span class="lpad">到货时间：</span>
		<input type="text" name="start_time" id="st" class="txt" size="15" value="<{$smarty.get.start_time}>" /> ~
		<input type="text" name="end_time" id="et" class="txt" size="15" value="<{$smarty.get.end_time}>" />
		<a href="javascript:;" class="btn" onClick="document.getElementById('seaform').submit();">查询</a>
		</form>
		</td>
	  </tr>
	</table>
	
	<div id="order-items">
	<{if $order_items}>
	<{foreach $order_items as $item0}>
		<div class="order-item-box">
			<{include file="order/send_item.tpl" order_data=$item0}>
		</div>
	<{/foreach}>
	<{/if}>
	</div>
	
	<{if $order_items}>
		<div class="col-box clearfix">
			<div class="z">
				<span class="mpad"><input type="checkbox" name="chk" id="chkall" value="1" /></span>
				<span class="lpad"><a href="javascript:;" class="btn2" id="btn-send-multi">发货选择的商品</a></span>
			</div>
			<div class="y">
				<{$pagebox}>
			</div>
		</div>
	<{/if}>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();
	
	var wrapbox = $('#order-items');
	var server_url = '/<{$mod}>/<{$col}>';
	var btn_scan = $('#btn-scan');
	var	input_scan = $('#input-scan');
	var search_barcode = '<{$smarty.get.barcode}>';
	
	//对每个订单单元绑定函数
	$('.order-item-box', wrapbox).each(function(){
		itemBoxInit(this);
	});
	
	function itemBoxInit(em){
		//查看留言
		$('.btn-message', em).click(function(){
			message_click(this);
		});
		
		//点击发货
		$('.btn-send-single', em).click(function(){
			send_click(this);
		});
		
		return em;
	}
	
	//全选
	$('#chkall').click(function(){
		var input_items = $('input[name^=barcode]', wrapbox);
		var f = $(this).prop('checked');
		if (f){
			input_items.each(function(){$(this).prop('checked', true);});
		} else {
			input_items.each(function(){$(this).prop('checked', false);});
		}
	});
	
	//多选发货
	$('#btn-send-multi').click(function(){
		send_multi_click(this);
	});
	
	//到货扫描
	btn_scan.click(function(){
		scan_submit();
	});
	
	input_scan.change(function(){
		scan_submit();
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
	
	//单选发货
	function send_click(em){
		var box = $(em).parent();
		var barcode = $(em).attr('data-value');
		var input_spno = $(':input[name=spno]', box);
			spno = $.trim(input_spno.val());
		var input_spcode = $(':input[name=spcode]', box);
			spcode = input_spcode.val();
		
		if (spno == '' || spcode == ''){
			input_spno.select();
			return false;
		}
		
		post_send('/<{$mod}>/<{$col}>/send', barcode, spno, spcode);
	}
	
	//多选发货
	function send_multi_click(em){
	
		var input_items = $('input[name^=barcode]', wrapbox);
		
		var barcode = '';
		input_items.each(function(){
			if ($(this).prop('checked')){
				if (barcode == ''){
					barcode = $(this).val();
				} else {
					barcode += ','+ $(this).val();
				}
			}
		});
		
		if (barcode == ''){
			return false;
		}
		
		var box  = '<div class="send_multi">';
			box += 	'<div style="padding-bottom:10px;">请务必先确认：<br />订单是否有相同的 <b>配送方式</b>，<b>收件人</b>和<b>收货地址</b>。<br /><br />填写快递单号：</div>';
			box += 	'<input type="text" name="spno" class="txt" size="35" />';
			box += '</div>';
			
		var box = $(box);
		var input_spno = $('input[name=spno]', box);
		
		popup.box({
			'id' : 'send-mult',
			'title' : '多选发货',
			'content' : box,
			'mask' : true
		}, function(){
			var spno = $.trim(input_spno.val());			
			if (spno == ''){
				input_spno.select();
				return false;
			}
			
			post_send('/<{$mod}>/<{$col}>/send_multi', barcode, spno);
		});
	}
	
	//发货请求
	function post_send(url, barcode, spno, spcode){
		$.post(url, {'barcode':barcode, 'spno':spno, 'spcode' : spcode}, function(dt){
			var d = json(dt);
			if (d.error == 0){
				popup.alert('发货成功！', false, function(){
					location.reload();
				});
			} else {
				popup.alert(d.message);
			}
		});
	}
	
	//扫描提交--到货
	function scan_submit(){
		var barcode = $.trim(input_scan.val());
		if (barcode == ''){
			input_scan.val('').select();
			return false;
		}
		$.post('/<{$mod}>/<{$col}>/receive', {'barcode':barcode}, function(dt){
			input_scan.val('');
			
			var d = json(dt);
			if (d.error == 0){
				var data = d.data;
				var order_id = d.order_id;
				var op_id = d.op_id;
				var box = $(data).hide();
				
				
				var itembox = $('#order-'+order_id);
				if (itembox.length > 0){
					itembox.slideUp(200, function(){
						$(this).hide().remove();
					})
				}
				
				wrapbox.prepend(box);
				box.slideDown(200);
				
				
				//绑定函数
				itemBoxInit(box);
				tabLine();
				
				//标记出当前到货的商品
				var trs = $('tr', box).has('td');
				for (var i = 0; i < trs.length; i++){
					var bc = $(trs[i]).attr('data-value');
					if (bc == barcode){
						$(trs[i]).addClass('focus');
						break;
					}
				}
				
				$('#receive-text').html('');
			} else {
				$('#receive-text').html(d.message);
			}
		});
	}
});
</script>
<{include file="common/footer.tpl"}>
