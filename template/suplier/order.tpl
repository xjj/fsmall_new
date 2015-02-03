<{include file="common/header.tpl"}>
<div class="suplier-wrap">
	<form name="f" method="get" id="searchForm" action="/order">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
		<th colspan="11"><{$LANG.ORDER_QUERY}></th>
	  </tr>
	  <tr>
		<td class="ltd"><{$LANG.PRODUCT_NAME}></td>
        <td><input type="text" name="product_name" class="txt" size="12" value="<{$smarty.get.product_name}>" /></td>
        <td class="ltd"><{$LANG.PRODUCT_SN}></td>
        <td><input type="text" name="product_sn" class="txt" size="12" value="<{$smarty.get.product_sn}>" /></td>
        <td class="ltd"><{$LANG.PRODUCT_ORDER_SN}>/<{$LANG.PRODUCT_BARCODE}></td>
        <td><input type="text" name="order_sn" class="txt" size="12" value="<{$smarty.get.order_sn}>" /></td>
        <td class="ltd"><{$LANG.PRODUCT_STATUS}></td>
        <td><select name="status">
			<option value="all" <{if $smarty.get.status eq 'all'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_ALL}></option>
			<option value="undo" <{if $smarty.get.status eq 'undo' or $smarty.get.status eq ''}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_UNDO}></option>
			<option value="done" <{if $smarty.get.status eq 'done'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_DONE}></option>
			<option value="ucancle" <{if $smarty.get.status eq 'ucancle'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_CANCLE_USER}></option>
			<option value="kcancle" <{if $smarty.get.status eq 'kcancle'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_CANCLE_KR}></option>
			</select></td>
        <td class="ltd"><{$LANG.PRODUCT_ADDTIME}></td>
        <td><input type="text" name="start_time" class="txt" size="12" value="<{$smarty.get.start_time}>" />
			<span>~</span>
			<input type="text" name="end_time" class="txt" size="12" value="<{$smarty.get.end_time}>" /></td>
        <td><a href="javascript:;" class="btn" id="btn-search" onclick="$('#searchForm').submit();"><{$LANG.SUBMIT}></a></td>
	  </tr>
	</table>
	</form>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="13"><input type="checkbox" name="chk1" value="1" id="chk1" /></th>
        <th width="60"><{$LANG.PRODUCT_PICTURE}></th>
		<th><{$LANG.PRODUCT_ORDER_SN}></th>
        <th><{$LANG.PRODUCT_BARCODE}></th>
		<th><{$LANG.PRODUCT_NAME}></th>
		<th><{$LANG.PRODUCT_SN}></th>
		<th><{$LANG.PRODUCT_ATTRIBUTE}></th>
		<th><{$LANG.PRODUCT_PRICE}></th>
		<th><{$LANG.PRODUCT_PRICE_DISCOUNT}></th>
		<th><{$LANG.PRODUCT_STATUS}></th>
		<th width="164"><{$LANG.PRODUCT_TIME}></th>
		<th><{$LANG.OPERATE}></th>
	  </tr>
	  <{if $order_items}>
	  <{foreach $order_items as $item}>
	  <tr>
        <td><input type="checkbox" name="barcode[<{$item.barcode}>]" value="<{$item.barcode}>" /></td>
        <td><a href="/order/krurl/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" width="60" height="60" style=" vertical-align:top" /></a></td>
		<td><{$item.order_sn}></td>
        <td><{$item.barcode}></td>
		<td><a href="/order/krurl/<{$item.prd_id}>" target="_blank"><{$item.product_name_kr}></a></td>
		<td><{$item.product_sn}></td>
		<td><{if $item.prop_value_kr}>
        		<{foreach $item.prop_value_kr as $value}><{$value}>; <{/foreach}>
            <{/if}>
        </td>
		<td>₩<{$item.price_kr}></td>
		<td>₩<{$item.price_kr_discount}></td>
		<td><{if $item.status eq 0}>
				<{$LANG.PRODUCT_STATUS_UNDO}>
			<{elseif $item.status eq 1}>
            	<{$LANG.PRODUCT_STATUS_DONE}>
			<{elseif $item.status eq 2}>
            	<{$LANG.PRODUCT_STATUS_CANCLE_KR}>
			<{elseif $item.status eq 3}>
				<{$LANG.PRODUCT_STATUS_CANCLE_USER}>
			<{/if}>
		</td>
		<td><{$LANG.TIME_ORDER}>：<{$item.order_time|date_format:'Y-m-d H:i'}>
			<{if $item.send_time gt 0}>
			<br /><{$LANG.TIME_SEND}>：<{$item.send_time|date_format:'Y-m-d H:i'}>
			<{/if}>
			<{if $item.kcancle_time gt 0}>
			<br /><{$LANG.TIME_CANCLE_KR}>：<{$item.kcancle_time|date_format:'Y-m-d H:i'}>
			<{/if}>
            <{if $item.ucancle_time gt 0}>
			<br /><{$LANG.TIME_CANCLE_USER}>：<{$item.ucancle_time|date_format:'Y-m-d H:i'}>
			<{/if}>
			<{if $item.refund_time gt 0}>
			<br /><{$LANG.TIME_REFUND}>：<{$item.refund_time|date_format:'Y-m-d H:i'}>
			<{/if}>
		</td>
		<td width="120">
        	<{if $item.status eq 0}>
				<a href="javascript:;" data-href="/order/send/<{$item.barcode}><{if $params neq ''}>?<{$params}><{/if}>" class="btn btn3 btn-send"><{$LANG.OPERATE_SEND}></a>
				<a href="javascript:;" data-href="/order/cancle/<{$item.barcode}><{if $params neq ''}>?<{$params}><{/if}>" class="btn btn3 btn-cancle"><{$LANG.OPERATE_CANCLE}></a>
            <{elseif $item.status eq 2}>
            	<{if $item.is_refund neq 1}>
                	<{*订单金额已返还的不能撤销*}>
            		<a href="javascript:;" data-href="/order/cancleback/<{$item.barcode}><{if $params neq ''}>?<{$params}><{/if}>" class="btn btn3 btn-cancleback"><{$LANG.OPERATE_CANCLE_BACK}></a>
                <{/if}>
            <{elseif $item.status eq 1}>
            	<{if $item.is_on_tpl neq 1}>
                	<{*订单已进入物流的不能撤销*}>
            		<a href="javascript:;" data-href="/order/canclesend/<{$item.barcode}><{if $params neq ''}>?<{$params}><{/if}>" class="btn btn3 btn-canclesend"><{$LANG.OPERATE_CANCLE_SEND}></a>
                <{/if}>
			<{/if}>
		</td>
	  </tr>
	  <{/foreach}>
      <tr>
      	<td><input type="checkbox" name="chk2" value="1" id="chk2" /></td>
        <td colspan="11">
        	<div class="clearfix">
                <div class="z">
                    <span class="mpad"><a href="javascript:;" id="print_barcode" class="btn btn3"><{$LANG.OPERATE_PRINT_BARCODE}></a>
                    </span>
                    <span class="mpad">
                        <a href="javascript:;" id="send_multi" class="btn btn3"><{$LANG.OPERATE_SEND_MULTI}></a>
                    </span>
                    <span class="mpad">
                        <a href="javascript:;" data-href="/order/download?<{$params}>" id="download" class="btn btn3"><{$LANG.OPERATE_DOWNLOAD_RESULT}></a>
                    </span>
                </div>
                <div class="y">
                	<a href="javascript:;" data-href="/order/sellmate?<{$params}>" id="sellmate" class="btn btn3">Sellmate Data Download</a>
                </div>
            </div>
        </td>
      </tr>
	  <{else}>
	  <tr>
	  	<td colspan="12"><{$LANG.EMPTY_RESULT}></td>
	  </tr>
	  <{/if}>
	</table>
	
	<div class="col-box clearfix">
		<{$pagebox}>
	</div>
</div>
<script type="text/javascript">
$(function(){
	function checked(em){
		var items = $('input[type=checkbox]');
		var f = $(em).prop('checked');
		if (f){
			items.each(function(){
				$(this).prop('checked', true);
			});
		} else {
			items.each(function(){
				$(this).prop('checked', false);
			});
		}
	}
	
	$('#chk1').click(function(){
		checked(this);
	});
	$('#chk2').click(function(){
		checked(this);
	});
	
	function fetch_checked(){
		var items = [];
		$('input[name^=barcode]').each(function(){
			if ($(this).prop('checked')){
				var barcode = $(this).val();
				items.push(barcode);
			}
		});
		
		return items;
	}
	
    function fetch_barcode(){
    	var items = fetch_checked();
        if (items.length > 0){
        	return items.join(',');
        } else {
        	return '';
        }
    }
	
    //打印条码
	$('#print_barcode').click(function(){
		var bc = fetch_barcode();
		if (bc != ''){
        	
        	var box = $('<div><div style="padding-bottom:10px"><{$LANG.POPUP_PRINT_BARCODE_CONTENT}></div><div><input type="text" name="sp" class="txt" id="sp" /></div></div>');
            var sp  = $('input', box); 
            
            var conf = {
            	'id' : 'print_barcode',
                'title' : '<{$LANG.POPUP_PRINT_BARCODE_TITLE}>',
                'yesBtnText' : '<{$LANG.POPUP_YESBTN_TEXT}>',
                'noBtnText' : '<{$LANG.POPUP_NOBTN_TEXT}>',
                'content' : box
            };
            
        	popup.box(conf, function(){
            	this.close();
                var url = '/order/printBC?bc='+bc;
                var sp_number = $.trim(sp.val());
                if (isint(sp_number) && sp_number > 0){
                	url += '&sp='+sp_number;
               	}
            	window.open(url);
            });
		}
	});
	
	//发货按钮
    $('.btn-send').click(function(){
    	var content = '<{$LANG.PRODUCT_SEND_CONFIRM}>';
    	var href = $(this).attr('data-href');
        popup.confirm(content, this, function(){
        	location.href = href;
        });
   	});
    
    //批量发货
    $('#send_multi').click(function(){
    	var content = '<{$LANG.PRODUCT_SEND_CONFIRM}>';
        var params = '<{$params}>';
    	popup.confirm(content, this, function(){
        	var bc = fetch_barcode();
            var href = '/order/send_multi?code='+bc;
            if (params != ''){
            	href += '&'+params;
            }
            location.href = href;
        });
    });
    
    //取消
    $('.btn-cancle').click(function(){
    	var content = '<{$LANG.PRODUCT_CANCLE_CONFIRM}>';
    	var href = $(this).attr('data-href');
        popup.confirm(content, this, function(){
        	location.href = href;
        });
   	});
    
    //撤销取消
    $('.btn-cancleback').click(function(){
    	var content = '<{$LANG.PRODUCT_CANCLEBACK_CONFIRM}>';
    	var href = $(this).attr('data-href');
        popup.confirm(content, this, function(){
        	location.href = href;
        });
   	});
    
    //撤销发货
    $('.btn-canclesend').click(function(){
    	var content = '<{$LANG.PRODUCT_CANCLESEND_CONFIRM}>';
    	var href = $(this).attr('data-href');
        popup.confirm(content, this, function(){
        	location.href = href;
        });
    });
    
    //下载查询结果
    $('#download').click(function(){
    	download(this);
    });
    
    //
    $('#sellmate').click(function(){
    	download(this);
    });
    
    var download_state = false;
    function download(e){
    	if (download_state){
        	return false;
        } else {
        	download_state = true;
       	}
        var em = $(e);
        var html = em.html();
    	var href = em.attr('data-href');
        $(this).html('Preparing data ...');
        $.get(href, {}, function(dt){
        	download_state = false;
            em.html(html);
        	var d = json(dt);
            if (d.error == 0){
            	//跳转到下载页面
                location.href = '/order/download2?filename='+d.filename;
            }
        });
    }
});
</script>
<{include file="common/footer.tpl"}>