<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到淘宝类目列表</a>
		</div>
	</div>
	
	<form name="f" method="post" action="/<{$mod}>/<{$col}>/prop/<{$tb_cat_data.tb_cat_id}>">
	<input type="hidden" name="tb_cat_id" value="<{$tb_cat_data.tb_cat_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th>淘宝属性ID</th>
		<th>淘宝属性名</th>
		<th>淘宝属性名（韩）</th>
		<th>排序</th>
		<th>有效</th>
		<th width="25%">操作</th>
	  </tr>
	  <{if $tb_prop_items}>
	  <{foreach $tb_prop_items as $item}>
	  <tr>
		<td><input type="text" name="tb_prop_id[<{$item.id}>]" value="<{$item.tb_prop_id}>" class="txt" size="15" /></td>
		<td><input type="text" name="tb_prop_value[<{$item.id}>]" value="<{$item.tb_prop_value}>" class="txt" size="30" /></td>
		<td><input type="text" name="tb_prop_value_kr[<{$item.id}>]" value="<{$item.tb_prop_value_kr}>" class="txt" size="30" /></td>
		<td><input type="text" name="displayorder[<{$item.id}>]" value="<{$item.displayorder}>" class="txt" size="6" /></td>
		<td><{if $item.status eq 1}>
				<a href="/<{$mod}>/<{$col}>/prop/<{$item.tb_cat_id}>/hide/<{$item.id}>" class="green">是</a>
			<{else}>
				<a href="/<{$mod}>/<{$col}>/prop/<{$item.tb_cat_id}>/show/<{$item.id}>" class="red">否</a>
			<{/if}>	
		</td>
		<td><a href="/<{$mod}>/<{$col}>/prop_value/<{$item.id}>">选项值</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/prop/<{$item.tb_cat_id}>/del/<{$item.id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	  <tr>
	  	<td><input type="text" name="tb_prop_id[0]" class="txt" size="15" /></td>
		<td><input type="text" name="tb_prop_value[0]" class="txt" size="30" /></td>
		<td><input type="text" name="tb_prop_value_kr[0]" class="txt" size="30" /></td>
		<td><input type="text" name="displayorder[0]" value="0" class="txt" size="6" /></td>
	  	<td></td>
		<td></td>
	  </tr>
	</table>
	<input type="submit" name="submit" value="提交" class="btn2" />
	</form>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该属性吗？', this, function(){
			location.href = url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>