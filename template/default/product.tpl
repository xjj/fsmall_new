<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/product.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><{if $cat_path_data}><{foreach $cat_path_data as $item}><span>&gt;</span><a href="/category/<{$item.cat_id}>"><{$item.cat_name}></a><{/foreach}><{/if}>
</div>

<div class="wrap">
	<div class="prd-info clearfix">
		<div class="prd-info-pic">
			<img src="<{$product_data.pic_large}>" />
		</div>
		<div class="prd-info-detail">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="prd-tbl">
			  <tr>
				<td class="ltd">中文名称：</td>
				<td><{$product_data.product_name}></td>
			  </tr>
			  <tr>
				<td class="ltd">韩网名称：</td>
				<td><{$product_data.product_name_kr}></td>
			  </tr>
			  <tr>
				<td class="ltd">韩网编号：</td>
				<td><{$product_data.product_sn}></td>
			  </tr>
			  <tr>
				<td class="ltd">上架时间：</td>
				<td><{$product_data.add_time|date_format:'Y-m-d'}></td>
			  </tr>
			  <tr>
				<td class="ltd">零售价格：</td>
				<td><span class="price">￥<span><{$product_data.price_retail}></span></span></td>
			  </tr>
			  <tr>
				<td class="ltd">销售价格：</td>
				<td><span class="price">￥<span id="sku-price"><{$product_data.price_sale}></span></span></td>
			  </tr>
			 </table>
			 <form name="sku-form" id="sku-form">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="prd-tbl prd-tbl2" id="sku-prop">
			 <{if $group_items}>
			 <{foreach $group_items as $item}>
			  <tr>
				<td class="ltd"><{$item.value}>：</td>
				<td><div class="prop-items">
					<{foreach $item.items as $item2}>
					<a href="javascript:;" class="prop-item" data-value="<{$item2.prop_value_id1}>:<{$item2.prop_value_id2}>"><{$item2.value}><i></i></a>
					<{/foreach}>
					</div>
				</td>
			  </tr>
			  <{/foreach}>
			  <{/if}>
			  <tr>
				<td class="ltd">数量：</td>
				<td><div class="clearfix">
						<input type="text" class="txt" name="number" value="1" size="8" id="sku-number" />
						<span class="lpad" id="sku-stock"></span>
					</div>
				</td>
			  </tr>
			</table>
			<div class="prd-button">
				<a href="javascript:;" class="btn2 btn-sku" id="add-to-cart">加入购物车</a>
				<a href="javascript:;" class="btn  btn-sku" id="add-to-favo">加入收藏夹</a>
			</div>
			</form>
		</div>
	</div>
	
	<div class="prd-ads"></div>
	
	<div class="prd-main clearfix">
		<div class="prd-side">
			<div class="prd-side-box">
				
			</div>
		</div>
		<div class="prd-detail">
			<div class="prd-detail-title">商品详细信息 / Details</div>
			<div class="prd-detail-info">
				<{$prd_data.content}>
			</div>
			<div class="prd-detail-pics" id="prd-pics">
				<{$prd_data.detail}>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="/js/product.js"></script>
<script type="text/javascript">
var PRDs = {'prd_id':'<{$product_data.prd_id}>', 'freight':'<{$product_data.freight}>'};

var SKUs = [<{foreach $product_sku_items as $item}><{if $item@index gt 0}>,<{/if}>{
	'sku_id':'<{$item.sku_id}>',
	'props':[<{foreach $item.prop_value_ids as $item2}><{if $item2@index gt 0}>,<{/if}>'<{$item2}>'<{/foreach}>],
	'price':'<{$item.price}>',
	'stock':'<{$item.stock}>',
	'number':'<{$item.number}>',
	'is_soldout':'<{$item.is_soldout}>'
}<{/foreach}>];

var form = $('#sku-form');
var propItems = $('.prop-item', form);
var propCount = $('.prop-items', form).length;

$(function(){
	product.init({
		'form' 			: form,
		'inputPrice' 	: $('#sku-price'),
		'inputNumber'	: $('#sku-number'),
		'inputStock' 	: $('#sku-stock'),
		'inputProp' 	: $('#sku-prop'),
		'inputCart' 	: $('#head-cart'),
		
		'PRDs' 			: PRDs, //商品信息
		'SKUs' 			: SKUs,	//SKU信息
		
		'propItems' 	: propItems,
		'propCount' 	: propCount,
		
		'className'		: 'prop-item-sel',
	
		'btnCart' 		: $('#add-to-cart'),
		'btnFavo' 		: $('#add-to-favo')
	});
});
</script>
<{include file="common/footer.tpl"}>