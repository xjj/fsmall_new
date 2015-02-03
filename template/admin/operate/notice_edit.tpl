<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到公告列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$notice_data.id}>">
	<input type="hidden" name="id" value="<{$notice_data.id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">公告标题</td>
		<td><input type="text" name="title" size="80" class="txt" value="<{$notice_data.title}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd">内容</td>
		<td height="340"><script type="text/plain" name="content" id="editor"><{$notice_data.content}></script></td>
	  </tr>
	  <tr>
		<td class="ltd">可见权限</td>
		<td><input type="radio" name="auth" value="0" <{if $notice_data.auth eq 0}>checked="checked"<{/if}> /><span class="lpad rpad">所有人可见</span>
			<input type="radio" name="auth" value="1" <{if $notice_data.auth eq 1}>checked="checked"<{/if}> /><span class="lpad rpad">注册会员及以上可见</span>
			<input type="radio" name="auth" value="2" <{if $notice_data.auth eq 2}>checked="checked"<{/if}> /><span class="lpad rpad">批发会员及以上可见</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否置顶</td>
		<td><input type="checkbox" name="is_top" value="1"  <{if $notice_data.is_top eq 1}>checked="checked"<{/if}> /></td>
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
	editor.init('editor', {'folder' : 'notice'});
});
</script>
<{include file="common/footer.tpl"}>