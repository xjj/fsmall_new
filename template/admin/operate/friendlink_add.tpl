<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到友情链接列表</a>	
	</div>
		
	<hr class="line" />
	
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">友链标题</td>
		<td><input type="text" name="title" class="txt" size="50" /></td>
	  </tr>
	  <tr>
		<td class="ltd">LOGO</td>
		<td><input type="text" name="logo" class="txt txt2" readonly="readonly" size="50" id="form-pic" />
			<a href="javascript:;" class="btn" id="upload-btn">选择</a>
			<span class="lpad">240×120 像素</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">链接地址</td>
		<td><input type="text" name="url" class="txt" size="50" />
			<span class="lpad">链接必须以 <b>http://</b> 开头</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="content" rows="3" cols="100" class="txt"></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" class="txt" size="10" value="0" /></td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/upload.js"></script>
<script type="text/javascript">
$(function(){
	var btn = $('#upload-btn')[0];
	upload(btn, 'friendlink', function(d){
		if (d.error == 0){
			$('#form-pic').val(d.path);
		} else {
			popup.alert(d.message);
		}
		
	});
});
</script>
<{include file="common/footer.tpl"}>