<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到配送方式列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$shipping_data.shipping_id}>">
	<input type="hidden" name="shipping_id" value="<{$shipping_data.shipping_id}>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">配送名称</td>
		<td><input type="text" name="shipping_name" class="txt" size="30" value="<{$shipping_data.shipping_name}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd">配送代码</td>
		<td><input type="text" name="shipping_code" class="txt" size="10" value="<{$shipping_data.shipping_code}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd">配送描述</td>
		<td><textarea rows="3" cols="100" name="content" class="txt"><{$shipping_data.content}></textarea>
			<span class="lpad">200个汉字以内</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否支持到付</td>
		<td><input type="checkbox" name="is_pay" value="1" disabled="disabled" <{if $shipping_data.is_pay eq 1}>checked="checked"<{/if}> />
			<span class="lpad">设置后该值不可修改</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" class="txt" size="10" value="<{$shipping_data.displayorder}>" /></td>
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