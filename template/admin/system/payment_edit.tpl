<{include file="common/header.tpl"}>
<style type="text/css">
p .pad{ font-family:Georgia} 
</style>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到支付列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$payment_data.pay_id}>">
	<input type="hidden" name="pay_id" value="<{$payment_data.pay_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">支付名称</td>
		<td><input type="text" name="pay_name" class="txt" size="40" value="<{$payment_data.pay_name}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd" width="80">支付代码</td>
		<td><input type="text" name="pay_code" class="txt" size="40" value="<{$payment_data.pay_code}>" />
			<span class="lpad">用于标记不同的支付，如：alipay,unionpay</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">支付参数</td>
		<td>
			<{if $payment_data.conf}>
				<{foreach $payment_data.conf as $key => $val}>
				<p>
				<input type="text" name="keys[<{$val@index}>]" class="txt" value="<{$key}>" />
				<span class="pad">=</span>
				<input type="text" name="vals[<{$val@index}>]" class="txt" value="<{$val}>" size="50" />
				</p>
				<{/foreach}>
				
				<{if $val@index lt 2}>
					<{for $i = $val@index+1 to 2}>
					<p>
					<input type="text" name="keys[]" class="txt" />
					<span class="pad">=</span>
					<input type="text" name="vals[]" class="txt" size="50" />
					</p>
					<{/for}>
				<{/if}>
			<{/if}>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">备注信息</td>
		<td><textarea cols="100" rows="3" name="content" class="txt"><{$payment_data.content}></textarea>
			<span class="lpad">200个汉字以内</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" value="提交" class="btn2" name="submit" />
		</td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>