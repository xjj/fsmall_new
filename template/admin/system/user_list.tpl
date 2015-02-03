<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加管理员</a>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="5%">UID</th>
		<th width="15%">用户名</th>
		<th width="20%">邮箱</th>
		<th>创建时间</th>
		<th>最后登录时间</th>
		<th>有效</th>
		<th>操作</th>
	  </tr>
	  <{if $user_items}>
	  <{foreach $user_items as $item}>
	  <tr>
		<td><{$item.uid}></td>
		<td><{$item.uname}></td>
		<td><{$item.email}></td>
		<td><{$item.add_time|date_format:"Y-m-d H:i"}></td>
		<td><{$item.login_time|date_format:"Y-m-d H:i"}></td>
		<td><{if $item.status eq 1}>
				<a class="green" href="/<{$mod}>/<{$col}>/hide/<{$item.uid}><{if $params neq ''}>?<{$params}><{/if}>">是</a>
			<{else}>
				<a class="red" href="/<{$mod}>/<{$col}>/show/<{$item.uid}><{if $params neq ''}>?<{$params}><{/if}>">否</a>
			<{/if}>
		</td>
		<td>
			<a href="/<{$mod}>/<{$col}>/auth/<{$item.uid}>">权限</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.uid}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.uid}>" class="btn-del">删除</a>
			
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该管理员吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>