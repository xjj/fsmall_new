<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
        <a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加供应商账号</a>
	</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl">
      <tr>
      	<th>UID</th>
        <th>用户名</th>
        <th>所属品牌</th>
        <th>最近登录时间</th>
        <th>最近修改时间</th>
        <th>是否有效</th>
        <th width="150">操作</th>
      </tr>
      <{if $user_items}>
      <{foreach $user_items as $item}>
      <tr>
          <td><{$item.sp_uid}></td>
          <td><{$item.sp_uname}></td>
          <td><{$item.brand_name}></td>
          <td><{$item.login_time|date_format:'Y-m-d H:i'}></td>
          <td><{$item.update_time|date_format:'Y-m-d H:i'}></td>
          <td> <{if $item.status eq 1}><a href="/<{$mod}>/<{$col}>/hide/<{$item.sp_uid}>" class="green">是</a><{else}><a href="/<{$mod}>/<{$col}>/show/<{$item.sp_uid}>" class="red">否</a><{/if}>  </td>
          <td><a href="/<{$mod}>/<{$col}>/update/<{$item.sp_uid}>" >编辑</a>
         		<span class="dl">|</span>
         		<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.sp_uid}>" class="btn-del">删除</a>
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
    	var href = $(this).attr('data-href');
        popup.confirm('确定删除吗？', this, function(){
        	location.href = href;
        });
    });
});
</script>
<{include file="common/footer.tpl"}>
