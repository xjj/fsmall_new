<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z" id="sebox">
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
		<div class="y">
			<a href="/<{$mod}>/<{$col}>/add<{if $parent_id gt 0}>/<{$parent_id}><{/if}>" class="btn btn3">添加地区信息</a>
		</div>
	</div>
	
	<div class="alert-box">
		大字：为方便分拣，快递单上以较大字体填写的地区名称，千万不要填错，否则会被配送错地方。
	</div>
	
	<{if $region_items}>
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/bat_edit/<{$parent_id}>">
		<input type="hidden" name="parent_id" value="<{$parent_id}>" />
		<table border="0" cellspacing="0" cellpadding="0" class="tbl">
		  <tr>
			<th width="50">ID</th>
			<th width="15%">名称</th>
			<th width="15%">大字</th>
			<th width="15%">邮编</th>
			<th>排序</th>
			<th width="20%">操作</th>
		  </tr>
		 
		  <{foreach $region_items as $item}>
		  <tr>
			<td><{$item.region_id}></td>
			<td><input type="text" name="region_name[<{$item.region_id}>]" class="txt" value="<{$item.region_name}>"></td>
			<td><input type="text" name="bigname[<{$item.region_id}>]" class="txt" size="15" value="<{$item.bigname}>"></td>
			<td><input type="text" name="zipcode[<{$item.region_id}>]" class="txt" value="<{$item.zipcode}>"></td>
			<td><input type="text" name="displayorder[<{$item.region_id}>]" class="txt" size="10" value="<{$item.displayorder}>"></td>
			
			<td>
				<a href="/<{$mod}>/<{$col}>/edit/<{$item.region_id}>">编辑</a>
				<span class="dl">|</span>
				<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.region_id}>" class="btn-del">删除</a>
			</td>
		  </tr>
		  <{/foreach}>
		</table>
		<input type="submit" name="submit" value="提 交" class="btn2" />
	</form>
	 <{/if}>
</div>
<script type="text/javascript">
$(function(){
	$('#sebox select').change(function(){
		var parent_id = $(this).val();
		if (parent_id > 0){
			location.href = "/<{$mod}>/<{$col}>/"+ parent_id;
		}
	});
	
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该地区信息吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>