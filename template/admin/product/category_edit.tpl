<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到商品类目列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$category_data.cat_id}>">
	<input type="hidden" name="cat_id" value="<{$category_data.cat_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">类目名称</td>
		<td><input type="text" name="cat_name" size="30" class="txt" value="<{$category_data.cat_name}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd">所属类目</td>
		<td><select name="cat_id1" id="cat_id1">
			<option value="0">选择一级类目</option>
			<{if $category_items}>
			<{foreach $category_items as $item}>
			<option value="<{$item.cat_id}>" <{if $item.cat_id eq $cat_id1}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<select name="cat_id2" id="cat_id2">
			<option value="0">选择二级类目</option>
			<{if $category_items2}>
			<{foreach $category_items2 as $item}>
			<option value="<{$item.cat_id}>" <{if $item.cat_id eq $cat_id2}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">淘宝类目ID</td>
		<td><input type="text" name="tb_cat_id" class="txt" size="15" value="<{$category_data.tb_cat_id}>" />
			<span class="lpad">该类目对应的淘宝类目ID，制作淘宝数据包时使用，一级类目，二级类目可不填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">淘宝类目名称</td>
		<td><input type="text" name="tb_cat_name" class="txt" size="50" value="<{$category_data.tb_cat_name}>" />
			<span class="lpad">该类目对应的淘宝类目名称，制作淘宝数据包时使用，一级类目，二级类目可不填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">重量</td>
		<td><input type="text" name="weight" class="txt" size="15" value="<{$category_data.weight}>" />
			<span class="lpad">单位：克，该类目下商品的平均重量，用以计算国际运费，一级类目，二级类目可不填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">关键词</td>
		<td><input type="text" name="keywords" class="txt" size="80" value="<{$category_data.keywords}>" />
			<span class="lpad">页面优化，词与词之间用逗号(,)或空格分隔</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="description" class="txt" cols="80" rows="3"><{$category_data.description}></textarea>
			<span class="lpad">页面优化，品牌的简要介绍</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" value="<{$category_data.displayorder}>" class="txt" size="6" /></td>
	  </tr>
	  <tr>
		<td class="ltd">是否不可退换</td>
		<td><input type="checkbox" name="is_no_return" value="1" <{if $category_data.is_no_return eq 1}>checked="checked"<{/if}> /></td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript" src="/js/category.js"></script>
<script type="text/javascript">
$(function(){
	$('#cat_id1').change(function(){
		var cat_id = $(this).val();
		category.children({'cat_id':cat_id, 'iem' : $('#cat_id2')[0]});
	});
});
</script>