<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/cart.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>我的购物车
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">我的购物车</div>
		<div class="cart-items">
			<form name="cart" id="cart-form">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-cart">
			  <tr>
               	<th width="13" class="chk"><input type="checkbox" name="chk1" value="1" id="chk1" /></th>
				<th width="60">商品图片</th>
				<th width="30%">商品信息</th>
				<th>商品编号</th>
				<th class="center">单价</th>
				<th class="center">数量</th>
				<th class="center">总金额</th>
				<th>操作</th>
			  </tr>
			  <{if $cart_items}>
			  <{foreach $cart_items as $item}>
			  <tr data-value="<{$item.sku_id}>">
				<td class="chk"><input type="checkbox" name="cart_id[]" value="<{$item.cart_id}>" class="sku-cartid" /></td>
				<td><a href="/product/<{$item.prd_id}>"><img src="<{$item.pic_small}>" width="60" height="60" class="sku-prdimg" /></a></td>
				<td><a href="/product/<{$item.prd_id}>"><{$item.product_name}></a><br />
					<span class="sku-props">
						<{if $item.prop_value}>
						<{foreach $item.prop_value as $item2}><{$item2}>; <{/foreach}>
						<{/if}>
					</span>
				</td>
				<td><{$item.product_sn}></td>
				<td class="center">￥<{$item.price}></td>
				<td class="center"><a href="javascript:;" class="icon-minus" ></a>
					<input type="text" size="6" name="number[<{$item.sku_id}>]" value="<{$item.number}>" class="sku-number" />
					<a href="javascript:;" class="icon-add"></a>
				</td>
				<td class="center"><span class="orange">￥<label class="sku-price"><{$item.price*$item.number}></label>.00</span></td>
				<td><a href="javascript:;" class="btn-favo" data-prdid="<{$item.prd_id}>">加入收藏夹</a>
					<br /><a href="javascript:;" class="btn-del">删除</a>
				</td>
			  </tr>
			  <{/foreach}>
			  <{else}>
			  <tr>
				<td colspan="8">购物车为空！</td>
			  </tr>
			  <{/if}>
			</table>
			
			<div class="cart-counter clearfix">
            	<div class="z chk">
                	<input type="checkbox" name="chk2" value="1" id="chk2" />
                </div>
				<div class="z">
					共商品 <span class="orange" id="input-number-total">0</span> 件，合计：<span class="orange">￥<label id="input-price-total">0</label>.00</span>
				</div>
				<div class="y cart-button">
					<a href="javascript:;" class="btn" id="btn-cart">去收银台结算</a>
				</div>
			</div>
            </form>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/cart.js"></script>
<script type="text/javascript">
var cartItems = [<{if $cart_items}><{foreach $cart_items as $item}><{if $item@index eq 0}><{else}>, <{/if}>{'cart_id':'<{$item.cart_id}>', 'sku_id':'<{$item.sku_id}>', 'prd_id':'<{$item.prd_id}>', 'number':'<{$item.number}>', 'price':'<{$item.price}>'}<{/foreach}><{/if}>];

$(function(){
	tabLine();
	
	cart.init({
		'cartItems' : cartItems,	

		'btnDelItems' 	: $('.btn-del'),
		'btnFavoItems' 	: $('.btn-favo'),
		'btnAddItems' 	: $('.icon-add'),
		'btnMinusItems' : $('.icon-minus'),
		'btnAddOrder'	: $('#btn-cart'),
		'inputCHK1'		: $('#chk1'),
		'inputCHK2'		: $('#chk2'),
		
		'inputNumberItems': $('.sku-number'),
		'inputPriceItems' : $('.sku-price'),
		'inputCartItems'  : $('.sku-cartid'),
		'inputNumberTotal': $('#input-number-total'),
		'inputPriceTotal' : $('#input-price-total'),
		
		
		'cartForm'		 : $('#cart-form')
	});
});
</script>
<{include file="common/footer.tpl"}>