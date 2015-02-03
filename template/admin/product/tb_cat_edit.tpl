<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到淘宝类目列表</a>
	</div>
	<hr class="line" />
	<form name="f" method="post" action="/<{$mod}>/<{$col}>/edit/<{$tb_cat_data.tb_cat_id}>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
	  	<td class="ltd" width="80">淘宝类目ID</td>
		<td><input type="text" name="tb_cat_id" class="txt txt2" readonly="1" size="15" value="<{$tb_cat_data.tb_cat_id}>" /></td>
	  </tr>
	  <tr>
	  	<td class="ltd">淘宝类目名称</td>
		<td><input type="text" name="tb_cat_name" class="txt" size="60" value="<{$tb_cat_data.tb_cat_name}>" /></td>
	  </tr>
	  <tr>
	  	<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" class="txt" size="8" value="<{$tb_cat_data.displayorder}>" /></td>
	  </tr>
	  <tr>
	  	<td class="ltd"></td>
		<td><input type="submit" name="submit" class="btn2" value="提交" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>