<{include file="common/header.tpl"}>
<div class="admin-wrap clearfix">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}><{if $parent_id gt 0}>/<{$parent_id}><{/if}>" class="btn btn3">返回到地区列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add/<{$parent_id}>">
	<input type="hidden" name="parent_id" value="<{$parent_id}>" />
	<table border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">地区名称</td>
		<td><input type="text" name="region_name" size="30" class="txt" /></td>
	  </tr>
	  <tr>
		<td class="ltd">上级地区</td>
		<td><div id="sebox">
			<select name="country_id" id="country">
				<option value="0">-国家-</option>
				<{if $country_items}>
				<{foreach $country_items as $item}>
				<option value="<{$item.region_id}>" <{if $country_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
				<{/foreach}>
				<{/if}>
			</select>
			<select name="province_id" id="province">
				<option value="0">-省份-</option>
				<{if $province_items}>
				<{foreach $province_items as $item}>
				<option value="<{$item.region_id}>" <{if $province_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
				<{/foreach}>
				<{/if}>
			</select>
			<select name="city_id" id="city">
				<option value="0">-城市-</option>
				<{if $city_items}>
				<{foreach $city_items as $item}>
				<option value="<{$item.region_id}>" <{if $city_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
				<{/foreach}>
				<{/if}>
			</select>
			</div>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">快递大字</td>
		<td><input type="text" name="bigname" class="txt" size="10" />
			<span class="lpad">市县级此项必填，用于快递分辨配送地址</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">邮编</td>
		<td><input type="text" name="zipcode" class="txt" size="10" />
		</td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" size="10" class="txt" value="0" />
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" class="btn2" value="提交" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/region.js"></script>
<script type="text/javascript">
$(function(){
	$('#sebox select').change(function(){
		var id  = $(this).val();
		var iem = $(this).next('select');
		var cem = iem.nextAll('select');
		var c = {'id' : id};
		if (iem.length > 0){
			c.iem = iem[0];
			if (cem.length > 0){c.cem = cem;}
			region.children(c);
		}
	});
});
</script>
<{include file="common/footer.tpl"}>