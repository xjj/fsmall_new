<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>/grade" class="btn btn3">返回到会员等级折扣列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/grade/edit/<{$disc_data.id}>">
	<input type="hidden" name="id" value="<{$disc_data.id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  
	  <tr>
		<td class="ltd">会员等级</td>
		<td><select name="grade_id">
			<option value="0">-会员等级-</option>
			<{if $grade_items}>
			<{foreach $grade_items as $item}>
			<option value="<{$item.grade_id}>" <{if $disc_data.grade_id eq $item.grade_id}>selected="selected"<{/if}>><{$item.grade_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">品牌</td>
		<td><select name="brand_id">
			<option value="0">-商品品牌-</option>
			<{if $brand_items}>
			<{foreach $brand_items as $item}>
			<option value="<{$item.brand_id}>" <{if $disc_data.brand_id eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<span class="lpad">可不选，不选则对所有品牌都有效</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd" width="100">折扣值</td>
		<td><input type="text" name="discount2" class="txt" size="10" value="<{$disc_data.discount2}>" />
			<span class="lpad">1 ~ 200 之间，即商品价格的n%， 如98%， 99%， 125%</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>