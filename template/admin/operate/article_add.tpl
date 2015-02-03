<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到文章列表</a>
	</div>
	
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">文章标题</td>
		<td><input type="text" name="title" size="80" class="txt" /></td>
	  </tr>
      <tr>
		<td class="ltd">文章分类</td>
		<td height="">			
        	<select name="cat_id">
            <option value="0">选择分类</option>
            <{if $cat_items}>
        	<{foreach $cat_items as $item}>
			<option value="<{$item.cat_id}>"><{$item.cat_name}></option>
            <{/foreach}>
            <{/if}>
			</select>
        </td>
	  </tr>
	  <tr>
		<td class="ltd">文章内容</td>
		<td height="340"><script type="text/plain" name="content" id="editor"></script></td>
	  </tr>
       <tr>
		<td class="ltd" width="109">短路径</td>
		<td><input type="text" name="short_url" class="txt" />
        	<span class="lpad">如：help-aboutus</span>
        </td>
	  </tr>
	  <tr>
		<td class="ltd" width="109">排序</td>
		<td><input type="text" name="displayorder" size="5" class="txt" /></td>
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