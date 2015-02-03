<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
        <a href="/<{$mod}>/<{$col}>/add" class="btn btn3">添加供应商信息</a>
	</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl">
      <tr>
      	<th>编号</th>
        <th>品牌</th>
        <th>公司名称</th>
        <th>联系人</th>
        <th>电话</th>
        <th>添加时间</th>
        <th width="150">操作</th>
      </tr>
      <{if $profile_items}>
      <{foreach $profile_items as $item}>
      <tr>
          <td><{$item.id}></td>
          <td><{$item.brand_name}></td>
          <td><{$item.company_name}></td>
          <td><{$item.contact}></td>
          <td><{$item.telphone}></td>
          <td><{$item.update_time|date_format:'Y-m-d H:i'}></td>
          <td><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.brand_id}>" class="btn-del">删除</a>
          		<span class="dl">|</span>
          		<a href="/<{$mod}>/<{$col}>/edit/<{$item.brand_id}>">编辑</a>
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
        popup.confirm('确定删除该信息吗？', this, function(){
        	location.href = href;
        });
    });
});
</script>
<{include file="common/footer.tpl"}>
