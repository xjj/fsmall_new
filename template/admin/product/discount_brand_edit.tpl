<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/brand" class="btn btn3">返回到品牌折扣列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/brand/edit/<{$disc_data.id}>">
	<input type="hidden" name="id" value="<{$disc_data.id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">商品品牌</td>
		<td><select name="brand_id">
			<option value="0">选择商品品牌</option>
			<{if $brand_items}>
			<{foreach $brand_items as $item}>
			<option value="<{$item.brand_id}>" <{if $item.brand_id eq $disc_data.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">商品类目</td>
		<td>
			<div id="catbox">
			<select name="cat_id1">
			<option value="0">选择一级类目</option>
			<{if $category_items}>
			<{foreach $category_items as $item}>
			<option value="<{$item.cat_id}>" <{if $cat_id1 eq $item.cat_id}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<select name="cat_id2">
			<option value="0">选择二级类目</option>
			<{if $category_items2}>
			<{foreach $category_items2 as $item}>
			<option value="<{$item.cat_id}>" <{if $cat_id2 eq $item.cat_id}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<select name="cat_id3">
			<option value="0">选择三级类目</option>
			<{if $category_items3}>
			<{foreach $category_items3 as $item}>
			<option value="<{$item.cat_id}>" <{if $cat_id3 eq $item.cat_id}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			</div>
		</td>
	  </tr>
	  
	  <tr>
		<td class="ltd">折扣值</td>
		<td><input type="text" name="discount" value="<{$disc_data.discount}>" class="txt" size="10" />
			<span class="lpad">1 ~ 99 之间，即商品价格降低 n%</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">开始时间</td>
		<td><input type="text" name="start_time" id="st" class="txt" size="20" value="<{$disc_data.start_time|date_format:'Y-m-d H:i'}>" />
			<span class="lpad">日期格式：YYYY-MM-DD HH:MM，时和分钟都要填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">结束时间</td>
		<td><input type="text" name="end_time" id="et" class="txt" size="20" value="<{$disc_data.end_time|date_format:'Y-m-d H:i'}>" />
			<span class="lpad">日期格式：YYYY-MM-DD HH:MM</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="/js/category.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();

	$('#catbox select').change(function(){
		var cat_id = $(this).val();
		var c = {'cat_id' : cat_id};
		var iem = $(this).next('select');
		if (iem.length > 0){
			c.iem = iem[0];
			var cem = iem.next('select');
			if (cem.length > 0){c.cem = cem[0];}
			category.children(c);
		}
	});
});
</script>

<{include file="common/footer.tpl"}>