<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回</a>
	</div>
	
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/update">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">用户名<input type="hidden" name="id" size="40" class="txt" value="<{$suplier_item.sp_uid}>"/></td>
		<td>
         <{$suplier_item.sp_uname}>

	  </tr>
      	  <tr>
		<td class="ltd">修改密码</td>
		<td><input type="password" name="upwd" class="txt" size="20" />
			<span class="lpad">6 ~ 18位，如不修改密码请留空</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">确认密码</td>
		<td><input type="password" name="upwd2" class="txt" size="20" />
		</td>
	  </tr>





	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.min.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.init.js"></script>
<script type="text/javascript">
$(function(){
	editor.init('editor', {'folder' : 'article'});
});
</script>
<{include file="common/footer.tpl"}>