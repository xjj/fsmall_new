<{include file="common/header.tpl"}>
	<div class="admin-wrap">
		<div class="col-box">
		</div>

	<table width="50%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>编号</th>
		<th>用户名</th>
        <th>有效时间</th>
        <th>授权类型</th>
        <th>最近登录时间</th>
         <th>操作</th>
	  </tr>
	  <{if $items}>
	  <{foreach $items as $item}>
	  <tr>
		<td><{$item.uid}></td>
		<td><{$item.uname}></td>
		<td><{$item.expires_in}></td>
		<td><{$item.type}></td>
		<td><{$item.login_time|date_format:'Y-m-d H:i'}></td>
        <td>
        <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.uid}>" class="btn-del">删除</a>
        </td>
        
	  </tr>
	  <{/foreach}>
      <{else}>
      <tr>
		<td colspan="6">暂无记录</td> </tr>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	//删除
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		var em = this;
		popup.confirm('确定删除吗？', em, function(){
			popup.confirm.close();
			popup.confirm('真的确定要删除？', em, function(){
				location.href = url;
			});
		});
	});
});

</script>
<{include file="common/footer.tpl"}>