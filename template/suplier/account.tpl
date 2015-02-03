<{include file="common/header.tpl"}>
<div class="suplier-wrap">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
		<th><{$LANG.ACCOUNT_QUERY}></th>
	  </tr>
	  <tr>
		<td>
			<form name="form" method="get" action="/account" id="sf">
			<{$LANG.ACCOUNT_DATETIME}>：
			<input type="text" name="start_time" value="<{$smarty.get.start_time}>" size="18" class="txt" />
			~
			<input type="text" name="end_time" value="<{$smarty.get.end_time}>" size="18" class="txt" />
			<a href="javascript:;" class="btn" onclick="$('#sf').submit();"><{$LANG.SUBMIT}></a>
			</form>
		</td>
	  </tr>
	</table>
	
	<div class="clearfix" style="position:relative; margin-right:-15px;">
		<{if $account_items}>
			<{foreach $account_items as $month => $dateitem}>
				<div class="z" style="margin-right:15px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
				  <tr>
					<th><{$LANG.ACCOUNT_DATETIME}></th>
					<th><{$LANG.ACCOUNT_AMOUNT_SEND}></th>
                    <th><{$LANG.ACCOUNT_AMOUNT_REFUND}></th>
					<th></th>
				  </tr>
				  <{foreach $dateitem as $item}>
				  <tr>
					<td><{$item.date|date_format:'Y-m-d'}></td>
					<td><{if $item.send_number gt 0}>
                    		<{$item.send_number}>|₩<{$item.amount_send_discount|number_format}>
                        <{/if}>
                    </td>
                    <td><{if $item.refund_number gt 0}>
                    		<{$item.refund_number}>|₩<{$item.amount_refund_discount|number_format}>
                        <{/if}>
                    </td>
					<td><a href="/account/detail?start_time=<{$item.date}>&end_time=<{$item.date+3600*24}>"><{$LANG.ACCOUNT_DETAIL}></a></td>
				  </tr>
				  <{/foreach}>
				</table>
				</div>
			<{/foreach}>
		<{/if}>
	</div>
	<{$pagebox}>
</div>
<{include file="common/footer.tpl"}>