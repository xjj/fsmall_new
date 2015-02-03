<{include file="common/header.tpl"}>
<style type="text/css">
p .pad{ font-family:Georgia} 
</style>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到支付列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">支付名称</td>
		<td><input type="text" name="pay_name" class="txt" size="40" /></td>
	  </tr>
	  <tr>
		<td class="ltd" width="80">支付代码</td>
		<td><input type="text" name="pay_code" class="txt" size="40" />
			<span class="lpad">用于标记不同的支付，如：alipay,unionpay</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">支付参数</td>
		<td>
			<p>
			<input type="text" name="keys[0]" class="txt" />
			<span class="pad">=></span>
			<input type="text" name="vals[0]" class="txt" size="50" />
			</p>
			<p>
			<input type="text" name="keys[1]" class="txt" />
			<span class="pad">=></span>
			<input type="text" name="vals[1]" class="txt" size="50" />
			</p>
			<p>
			<input type="text" name="keys[2]" class="txt" />
			<span class="pad">=></span>
			<input type="text" name="vals[2]" class="txt" size="50" />
			</p>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">备注信息</td>
		<td><textarea cols="100" rows="3" name="content" class="txt"></textarea>
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