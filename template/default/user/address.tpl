<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>收货地址
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">收货地址管理</div>
        <div class="user-main">
            <{if $address_data}>
                <form name="f" method="post" action="/user/address/edit/<{$address_data.addr_id}>">
                <input type="hidden" name="addr_id" value="<{$address_data.addr_id}>" />
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="user-tbl">
                  <tr>
                    <td class="ltd" width="100">收货人姓名：</td>
                    <td><input type="text" name="consignee" size="30" class="txt" value="<{$address_data.consignee}>" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">所在地：</td>
                    <td>
                        <div id="sebox">
                        <select name="province_id">
                        <option value="0">-省/直辖市-</option>
                        <{if $province_items}>
                        <{foreach $province_items as $item}>
                        <option value="<{$item.region_id}>" <{if $address_data.province_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                        <{/foreach}>
                        <{/if}>
                        </select>
                        <select name="city_id">
                        <option value="0">-城市-</option>
                        <{if $city_items}>
                        <{foreach $city_items as $item}>
                        <option value="<{$item.region_id}>" <{if $address_data.city_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                        <{/foreach}>
                        <{/if}>
                        </select>
                        <select name="county_id" id="county">
                        <option value="0">-县区-</option>
                        <{if $county_items}>
                        <{foreach $county_items as $item}>
                        <option value="<{$item.region_id}>" <{if $address_data.county_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                        <{/foreach}>
                        <{/if}>
                        </select>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="ltd">收货地址：</td>
                    <td><input type="text" name="address" class="txt" size="60" value="<{$address_data.address}>" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">邮编：</td>
                    <td><input type="text" name="zipcode" class="txt" size="15" id="zipcode" value="<{$address_data.zipcode}>" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">手机或电话：</td>
                    <td><input type="text" name="mobile" class="txt" size="15" value="<{$address_data.mobile}>" /></td>
                  </tr>
                  <tr>
                    <td class="ltd"></td>
                    <td><input type="submit" name="submit" class="btn2" value="编 辑" />
                        <a href="/user/address" class="btn">取消编辑</a>
                    </td>
                  </tr>
                </table>
                </form>
            <{else}>
                <form name="f" method="post" action="/user/address/add">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="user-tbl">
                  <tr>
                    <td class="ltd" width="100">收货人姓名：</td>
                    <td><input type="text" name="consignee" size="30" class="txt" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">所在地：</td>
                    <td>
                        <div id="sebox">
                        <select name="province_id">
                        <option value="0">-省/直辖市-</option>
                        <{if $province_items}>
                        <{foreach $province_items as $item}>
                        <option value="<{$item.region_id}>"><{$item.region_name}></option>
                        <{/foreach}>
                        <{/if}>
                        </select>
                        <select name="city_id">
                        <option value="0">-城市-</option>
                        </select>
                        <select name="county_id" id="county">
                        <option value="0">-县区-</option>
                        </select>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="ltd">收货地址：</td>
                    <td><input type="text" name="address" class="txt" size="60" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">邮编：</td>
                    <td><input type="text" name="zipcode" class="txt" size="15" id="zipcode" /></td>
                  </tr>
                  <tr>
                    <td class="ltd">手机或电话：</td>
                    <td><input type="text" name="mobile" class="txt" size="15" /></td>
                  </tr>
                  <tr>
                    <td class="ltd"></td>
                    <td><input type="submit" name="submit" class="btn2" value="添 加" /></td>
                  </tr>
                </table>
                </form>
            <{/if}>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
              <tr>
                <th>收货人姓名</th>
                <th>收货地址</th>
                <th>邮编</th>
                <th>联系方式</th>
                <th>操作</th>
                <th></th>
              </tr>
              <{if $address_items}>
              <{foreach $address_items as $item}>
              <tr>
                <td><{$item.consignee}></td>
                <td><{$item.province_name}>
                    <{$item.city_name}>
                    <{$item.county_name}>
                    <{$item.address}>
                </td>
                <td><{$item.zipcode}></td>
                <td><{$item.mobile}></td>
                <td><{if $item.is_check eq 1}><a href=""></a><{/if}><a href="/user/address/<{$item.addr_id}>">编辑</a><span class="dl">|</span><a href="javascript:;" data-href="/user/address/del/<{$item.addr_id}>" class="btn-del">删除</a></td>
                <td><{if $item.is_check eq 1}>
                        <span class="defaultAddress">默认地址</span>
                    <{else}>
                        <a href="/user/address/setdefault/<{$item.addr_id}>" class="setDefaultAddress">设为默认</a>
                    <{/if}>
                </td>
              </tr>
              <{/foreach}>
              <{else}>
              <tr>
                <td colspan="6">收货地址信息为空！</td>
              </tr>
              <{/if}>
            </table>
        </div>
	</div>
</div>
<script type="text/javascript" src="/js/region.js"></script>
<script type="text/javascript">
$(function(){
	tabLine();

	$('.btn-del').click(function(){
		var href = $(this).attr('data-href');
		popup.confirm('确定要删除该地址信息？', this, function(){
			location.href = href;
		});
		
	});
	//联动获取省市县
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
	
	//设置邮编的值
	$('#county').change(function(){
		var id = $(this).val();
		var zipcodeInput = $('#zipcode');
		if (id > 0){
			var zipcode = $('option:selected', this).attr('data-zipcode');
			if (zipcode > 0){
				var code = zipcodeInput.val();
				if ($.trim(code) == ''){
					zipcodeInput.val(zipcode);
				}
			}	
		}
	});
});
</script>
<{include file="common/footer.tpl"}>