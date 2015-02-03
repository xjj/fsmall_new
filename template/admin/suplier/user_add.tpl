<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回</a>
	</div>
	
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">品牌</td>
		<td>
        <select name="brand_id">
            <option value="0">选择品牌</option>
            <{if $brands}>
        	<{foreach $brands as $item}>
			<option value="<{$item.brand_id}>"<{if $suplier_item.brand_id eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
            <{/foreach}>
            <{/if}>
			</select>
	  </tr>
      <tr>
		<td class="ltd" width="80">用户名</td>
		<td><input type="text" name="sp_uname" size="20" class="txt" value=""/></td>
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