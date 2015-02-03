<{include file="common/header.tpl"}>
	<div class="admin-wrap">

	<table width="50%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>编号</th>
		<th>用户名</th>
		<th>淘宝店铺</th>
		<th>淘宝用户名</th>
        <th>实体店铺</th>
        <th>实体店铺执照</th>
        <th>网店</th>
        <th>网店icp</th>
        <th>申请时间</th>
        <th>操作</th>
	  </tr>
	  <{if $items}>
	  <{foreach $items as $item}>
	  <tr>
		<td><{$item.id}></td>
		<td><{$item.uname}></td>
		<td><{$item.tb_shop}></td>
		<td><{$item.tb_uname}></td>
		<td><{$item.sd_shop}></td>
        <td><{$item.sd_license}></td>
        <td><{$item.wd_shop}></td>
        <td><{$item.wd_icp}></td>
		<td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
        <td>
        <a href="/<{$mod}>/<{$col}>/agree/<{$item.id}>/<{$item.uid}>">通过</a>
		<span class="dl">|</span>
		<a href="/<{$mod}>/<{$col}>/disagree/<{$item.id}>/<{$item.uid}>">拒绝</a>
        </td>
	  </tr>
	  <{/foreach}>
      	
	  <{/if}>
      <{if $total<1}>
         	<tr>
			<td colspan="10">暂无记录</td>
            </tr>
         <{/if}>
	</table>
	<{$pagebox}>
</div>

<{include file="common/footer.tpl"}>