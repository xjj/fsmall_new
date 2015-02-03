<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/product" class="btn btn3">返回到商品折扣列表</a>
	</div>
    <hr class="line" />
    <form name="f" method="post" action="/<{$mod}>/<{$col}>/product/add">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
      <tr>
        <td class="ltd" width="80">商品ID</td>
        <td><input type="text" name="prd_id" class="txt" size="10" />
        </td>
      </tr>
      <tr>
      	<td class="ltd">折扣值</td>
        <td><input type="text" name="discount" class="txt" size="10" />
        	<span class="rpad">%</span>
        	<span class="lpad">取值范围 1 ~ 99</span>
        </td>
      </tr>
      <tr>
      	<td class="ltd"></td>
        <td><input type="submit" name="submit" class="btn" value="提交" /></td>
      </tr>
    </table>
	</form>
</div>
<{include file="common/footer.tpl"}>