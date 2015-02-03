<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到供应商信息列表</a>
	</div>
	
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">品牌名称</td>
		<td>
        <select name="brand_id">
            <option value="0">选择品牌</option>
            <{if $brand_items}>
        	<{foreach $brand_items as $item}>
			<option value="<{$item.brand_id}>"><{$item.brand_name}></option>
            <{/foreach}>
            <{/if}>
			</select>
	  </tr>
      <tr>
		<td class="ltd" width="80">公司名称</td>
		<td><input type="text" name="company_name" size="40" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">公司地址</td>
		<td><input type="text" name="company_address" size="50" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">联系人</td>
		<td><input type="text" name="contact" size="20" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">电话</td>
		<td><input type="text" name="telphone" size="20" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">银行开户名</td>
		<td><input type="text" name="bank_username" size="20" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">开户银行</td>
		<td><input type="text" name="bank_name" size="20" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd" width="80">银行账号</td>
		<td><input type="text" name="bank_account" size="40" class="txt" /></td>
	  </tr>

	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>