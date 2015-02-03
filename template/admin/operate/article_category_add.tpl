<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
    	<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到文章列表</a>
		<a href="/<{$mod}>/<{$col}>/cat" class="btn btn3">返回到文章分类</a>
	</div>
	
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/cat/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">分类名称</td>
		<td><input type="text" name="cat_name" size="20" class="txt"/></td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td height=""><input type="text" name="displayorder" size="5" class="txt" /></td>
	  </tr>
	  
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>