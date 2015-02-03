<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/cart.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/cart">我的购物车</a>
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
		<div class="user-title">我的购物车 <span style="font-family:'\5b8b\4f53'">&gt;</span> 填写订单信息</div>
        <form name="form-order" id="form-order">
        <div class="cart-wrap">
        	<div class="cart-title">
            	<span class="rpad">设置收货地址</span>
                <a href="/user/address" target="_blank" class="icon-setting">设置</a>
            </div>
        	<div class="cart-content">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="cart-tbl">
                  <{if $addr_items}>
                  <{$is_check = 0}>
                  <{foreach $addr_items as $item}>
                  <tr>
                    <td class="ltd"><input type="radio" name="addr_id" value="<{$item.addr_id}>" <{if $item.is_check eq 1}>checked="checked"<{$is_check = 1}><{/if}> /></td>
                    <td><{$item.province_name}> <{$item.city_name}> <{$item.county_name}> <{$item.address}> (<{$item.consignee}> 收) <{$item.mobile}></td>
                  </tr>
                  <{/foreach}>
                  <{/if}>
                  <tr>
                    <td class="ltd" valign="top"><input type="radio" name="addr_id" value="0" <{if $is_check eq 0}>checked="checked"<{/if}> id="inputAddr" /></td>
                    <td><span href="javascript:;" class="new_address">使用新收货地址</span>
                    	<div class="newaddress" id="newaddress" <{if $is_check eq 0}>style=" display:block"<{/if}>>
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="cart-tbl-inner">
                              <tr>
                                <td class="ttd">收件人姓名</td>
                                <td width="353"><input type="text" class="txt" name="consignee" /></td>
                                <td class="ttd">手机或电话</td>
                                <td><input type="text" class="txt" name="mobile" /></td>
                              </tr>
                              <tr>
                                <td class="ttd">收件人地址</td>
                                <td>
                                <div id="sebox">
                                	<select name="province_id">
                                    <option value="0">省/直辖市</option>
                                    <{if $province_items}>
                                    <{foreach $province_items as $item}>
                                    <option value="<{$item.region_id}>"><{$item.region_name}></option>
                                    <{/foreach}>
                                    <{/if}>
                                    </select>
                                    <select name="city_id">
                                    <option value="0">城市</option>
                                    </select>
                                    <select name="county_id" id="county">
                                    <option value="0">县区</option>
                                    </select>
                                </div>
                                </td>
                                <td class="ttd">邮政编码</td>
                                <td><input type="text" class="txt" name="zipcode" id="zipcode" /></td>
                              </tr>
                              <tr>
                                <td class="ttd">详细地址</td>
                                <td colspan="3"><input name="address" type="text" class="txt" size="85" /></td>
                              </tr>
                            </table>
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
        
        <div class="cart-wrap">
       		<div class="cart-title">选择配送方式</div>
        	<div class="cart-content">
            	<select name="shipping_id" id="input-shipping">
                <option value="0">选择配送方式</option>
                <{if $shipping_items}>
                <{foreach $shipping_items as $item}>
                <option value="<{$item.shipping_id}>"><{$item.shipping_name}></option>
                <{/foreach}>
                <{/if}>
                </select>
            </div>
        </div>
        <div class="cart-wrap">
       		<div class="cart-title">选择支付方式</div>
        	<div class="cart-content">
            	<select name="pay_id" id="input-payment">
                <option value="0">选择支付方式</option>
                <{if $payment_items}>
                <{foreach $payment_items as $item}>
                <option value="<{$item.pay_id}>" data-value="<{$item.pay_code}>"><{$item.pay_name}></option>
                <{/foreach}>
                <{/if}>
                </select>
            </div>
        </div>
        <div class="cart-wrap">
       		<div class="cart-title">订单留言</div>
        	<div class="cart-content">
            	<textarea name="message" cols="105" rows="2" class="txt"></textarea>
            </div>
        </div>
        </form>
        
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-cart">
          <tr>
            <th width="60">商品图片</th>
            <th width="30%">商品信息</th>
            <th>商品编号</th>
            <th class="center">单价</th>
            <th class="center">数量</th>
            <th class="center">总金额</th>
          </tr>
          <{if $cart_items}>
          <{foreach $cart_items as $item}>
          <tr data-value="<{$item.sku_id}>">
            <td><a href="/product/<{$item.prd_id}>"><img src="<{$item.pic_small}>" width="100" height="100" class="sku-prdimg" /></a></td>
            <td><a href="/product/<{$item.prd_id}>"><{$item.product_name}></a><br />
                <span class="sku-props">
                    <{if $item.prop_value}>
                    <{foreach $item.prop_value as $item2}><{$item2}>; <{/foreach}>
                    <{/if}>
                </span>
            </td>
            <td><{$item.product_sn}></td>
            <td class="center">￥<{$item.price}></td>
            <td class="center"><{$item.number}></td>
            <td class="center"><span class="orange">￥<label class="sku-price"><{$item.price*$item.number}></label>.00</span></td>
          </tr>
          <{/foreach}>
          <{/if}>
        </table>
        <div class="cart-counter clearfix">
            <div class="z">
                共商品 <span class="orange"><{$total_number}></span> 件，
                商品总金额：<span class="orange">￥<{$total_price}>.00</span>，
                国内快递费：<span class="orange">￥<label id="input-shipping-fee">0</label>.00</span>，
                合计总金额：<span class="orange">￥<label id="input-total-price"><{$total_price}></label>.00</span>
            </div>
            <div class="y cart-button">
                <a href="javascript:;" class="btn2" id="btn-add-order">提交订单信息</a>
            </div>
        </div>
	</div>
</div>
<script type="text/javascript" src="/js/region.js"></script>
<script type="text/javascript" src="/js/cart_confirm.js"></script>
<script type="text/javascript">
$(function(){
	tabLine();
	
	var form = $('#form-order');
	
	var newaddress = $('#newaddress');
	$('input[name=addr_id]', form).click(function(){
		if ($('#inputAddr').prop('checked')){
			newaddress.show();	
		} else {
			newaddress.hide();	
		}
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
	var zipcodeInput = $('#zipcode');
	$('#county').change(function(){
		var id = $(this).val();
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
	
	cartConfirm.init({
		'addrItems' : [<{if $addr_items}><{foreach $addr_items as $item}><{if $item@index eq 0}><{else}>, <{/if}>{'addr_id':'<{$item.addr_id}>', 'province_id':'<{$item.province_id}>', 'province_name':'<{$item.province_name}>', 'city_id':'<{$item.city_id}>', 'city_name':'<{$item.city_name}>', 'county_id':'<{$item.county_id}>', 'county_name':'<{$item.county_name}>', 'zipcode':'<{$item.zipcode}>', 'consignee':'<{$item.consignee}>', 'mobile':'<{$item.mobile}>', 'address':'<{$item.address}>'}<{/foreach}><{/if}>],
		
		'cartItems' : [<{if $cart_items}><{foreach $cart_items as $item}><{if $item@index eq 0}><{else}>, <{/if}>{'sku_id':'<{$item.sku_id}>', 'prd_id':'<{$item.prd_id}>', 'number':'<{$item.number}>', 'price':'<{$item.price}>', 'weight':'<{$item.weight}>'}<{/foreach}><{/if}>],
		
		'form' : form,
		'inputShipping' : $('#input-shipping'),
		'inputPayment' : $('#input-payment'),
		'inputShippingFee' : $('#input-shipping-fee'),
		'inputTotalPrice' : $('#input-total-price'),
		'btnAddOrder' : $('#btn-add-order'),
		'userBalance' : '<{$balance}>'
	});
});
</script>
<{include file="common/footer.tpl"}>