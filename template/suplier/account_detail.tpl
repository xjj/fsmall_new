<{include file="common/header.tpl"}>
<div class="suplier-wrap">
	<div class="col-box clearfix">
    	<div class="z">
			<a href="/account" class="btn btn3"><{$LANG.BACKTO}><{$LANG.NAV_ACCOUNT}></a>
        </div>
        <div class="y">
        	<a href="/account/detail?start_time=<{$start_time}>&end_time=<{$end_time}>" class="<{if $account_type eq 'send'}>btn2<{else}>btn<{/if}> btn3"><{$LANG.ACCOUNT_SEND_ITEMS}></a>
            <a href="/account/detail/refund?start_time=<{$start_time}>&end_time=<{$end_time}>" class="<{if $account_type eq 'refund'}>btn2<{else}>btn<{/if}> btn3"><{$LANG.ACCOUNT_REFUND_ITEMS}></a>
        </div>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th colspan="11" style="background:#FFF;">
		<form name="f" id="fs" method="get" action="/account/detail">
		<input type="text" name="start_time" value="<{$start_time|date_format:'Y-m-d H:i:s'}>" size="18" class="txt" />
        ~
        <input type="text" name="end_time" value="<{$end_time|date_format:'Y-m-d H:i:s'}>" size="18" class="txt" />
		<a href="javascript:;" class="btn" onclick="$('#fs').submit();"><{$LANG.SUBMIT}></a>
		</form>
		</th>
	  </tr>
	  <tr>
      	<th><{$LANG.PRODUCT_PICTURE}></th>
		<th><{$LANG.PRODUCT_ORDER_SN}></th>
        <th><{$LANG.PRODUCT_BARCODE}></th>
		<th><{$LANG.PRODUCT_NAME}></th>
		<th><{$LANG.PRODUCT_SN}></th>
		<th><{$LANG.PRODUCT_ATTRIBUTE}></th>
		<th><{$LANG.PRODUCT_PRICE}></th>
		<th><{$LANG.PRODUCT_PRICE_DISCOUNT}></th>
		<th><{$LANG.PRODUCT_STATUS}></th>
		<th><{$LANG.PRODUCT_TIME}></th>
	  </tr>
	  <{if $order_items}>
	  <{foreach $order_items as $item}>
	  <tr>
      	<td><a href="/order/krurl/<{$item.prd_id}>" target="_blank"><img src="<{$item.pic_small}>" width="60" height="60" style=" vertical-align:top" /></a></td>
		<td><{$item.order_sn}></td>
        <td><{$item.barcode}></td>
		<td><{$item.product_name_kr}></td>
		<td><{$item.product_sn}></td>
		<td><{if $item.prop_value_kr}>
        		<{foreach $item.prop_value_kr as $value}><{$value}>; <{/foreach}>
            <{/if}></td>
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
			<{/if}></td>
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
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	var form = $('#fs');
	$('input', form).keyup(function(event){
    	if (event.keyCode == 13){
        	form.submit();
        }
    });
});
</script>
<{include file="common/footer.tpl"}>