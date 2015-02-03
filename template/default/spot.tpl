<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/product_common.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/spot">现货商品</a>
</div>


<div class="wrap">
	<form name="SpotForm" id="SpotForm" method="get" action="/spot">
	<div class="product-option">
    	<div class="product-option-item product-option-brand">
        	<span class="mpad">商品品牌：</span>
        	<select name="brand_id" id="input-brand">
            <option value="">所有品牌</option>
            <{if $brand_items}>
            <{foreach $brand_items as $item}>
            <option value="<{$item.brand_id}>" <{if $smarty.get.brand_id eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name|capitalize}></option>
            <{/foreach}>
            <{/if}>
            </select>
        </div>
    </div>
	</form>
    
	<!--商品列表-->
	<div class="prd-items clearfix">
		<{if $product_items}>
		<{foreach $product_items as $item}>
			<{include file="product_item.tpl"}>
		<{/foreach}>
		<{/if}>
	</div>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	var form = $('#SpotForm');
	var input_brand = $('#input-brand');
	
	input_brand.change(function(){
		form.submit();	
	});
});
</script>
<{include file="common/footer.tpl"}>