<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/product_common.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/brand.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/brand">所有品牌</a><span>&gt;</span><a href="/brand/<{$brand_data.brand_id}>"><{$brand_data.brand_name}></a>
</div>

<div class="wrap clearfix">
    <div class="brand-info-wrap">
        <img src="<{$brand_data.pic}>" width="1100" height="220" />
        <div class="brand-info clearfix">
            <div class="z">品牌介绍</div>
            <div class="y">
                <{$brand_data.content}>
            </div>
        </div>
    </div>
    
    <form name="brandForm" id="brandForm" method="get" action="/brand/<{$brand_data.brand_id}>">
    <input type="hidden" name="cat_id" id="input-catid" value="<{$smarty.get.cat_id}>" />
    <div class="product-option">
        <div class="product-option-item product-option-cat">
            <span>商品分类：</span>
            <a href="javascript:;" data-catid="" <{if $smarty.get.cat_id eq ''}>class="sel"<{/if}>>全部</a>
            <{if $brand_cat_items}>
            <{foreach $brand_cat_items as $item}>
            <a href="javascript:;" data-catid="<{$item.cat_id}>" <{if $smarty.get.cat_id eq $item.cat_id or $cat_data.parent_id eq $item.cat_id}>class="sel"<{/if}>><{$item.cat_name}></a>
            <{/foreach}>
            <{/if}>
        </div>
        <{if $cat_items}>
        <div class="product-option-item product-option-cat product-option-cat2">
            <span>-></span> 
            <{foreach $cat_items as $item}>
            <a href="javascript:;" data-catid="<{$item.cat_id}>" <{if $smarty.get.cat_id eq $item.cat_id}>class="sel"<{/if}>><{$item.cat_name}></a>
            <{/foreach}>
        </div>
        <{/if}>
        <div class="product-option-item whiteBG product-option-price" id="priceBox">
            <span>商品价格：</span>
            <a href="javascript:;" data-sp="" data-ep="200">￥200以下</a>
            <a href="javascript:;" data-sp="200" data-ep="500">￥200-500</a>
            <a href="javascript:;" data-sp="500" data-ep="1000">￥500-1000</a>
            <a href="javascript:;" data-sp="1000" data-ep="2000">￥1000-2000</a>
            <a href="javascript:;" data-sp="2000" data-ep="">￥2000以上</a>
            <input type="text" name="sp" class="price-txt" id="input-sp" value="<{$smarty.get.sp}>" />
            -
            <input type="text" name="ep" class="price-txt" id="input-ep" value="<{$smarty.get.ep}>" />
            <input type="button" name="sb" id="btn-price" class="btn btn3 price-btn" value="确定" />
        </div>
    </div>
    <input type="hidden" name="type" value="<{$smarty.get.type}>" id="input-type" />
    <input type="hidden" name="order" value="<{if $smarty.get.order eq 'asc'}>asc<{else}>desc<{/if}>" id="input-order" />
    </form>
    
    <!--商品选择项-->
    <div class="product-option clearfix">
         <div class="product-option-item clearfix">
            <div class="z">
                <span>商品排序：</span>
                <a href="javascript:;" data-type="" class="orderType <{if $smarty.get.type eq ''}>sel<{/if}>">默认</a>
                <a href="javascript:;" data-type="price" class="orderType <{if $smarty.get.type eq 'price'}>sel<{/if}>">价格<span class="<{if $smarty.get.type eq 'price' and $smarty.get.order eq 'desc'}>icon-desc<{elseif $smarty.get.type eq 'price' and $smarty.get.order eq 'asc'}>icon-asc<{else}>icon-normal<{/if}>"></span></a>
                <a href="javascript:;" data-type="sale" class="orderType <{if $smarty.get.type eq 'sale'}>sel<{/if}>">销量<span class="<{if $smarty.get.type eq 'sale' and $smarty.get.order eq 'desc'}>icon-desc<{elseif $smarty.get.type eq 'sale' and $smarty.get.order eq 'asc'}>icon-asc<{else}>icon-normal<{/if}>"></span></a>
                <a href="javascript:;" data-type="visit" class="orderType <{if $smarty.get.type eq 'visit'}>sel<{/if}>">人气<span class="<{if $smarty.get.type eq 'visit' and $smarty.get.order eq 'desc'}>icon-desc<{elseif $smarty.get.type eq 'visit' and $smarty.get.order eq 'asc'}>icon-asc<{else}>icon-normal<{/if}>"></span></a>
            </div>
            <div class="y">
                <{if $page gt 1}>
                <a href="/brands/<{$brand_data.brand_id}>?<{if $params neq ''}><{$params}>&<{/if}>page=<{$page-1}>">上一页</a>
                <{/if}>
                <{if $pagecount gt $page}>
                <a href="/brands/<{$brand_data.brand_id}>?<{if $params neq ''}><{$params}><{/if}>page=<{$page+1}>">下一页</a>
                <{/if}>
            </div>
        </div>
    </div>
    
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
	var form = $('#brandForm');
	var input_order = $('#input-order');
	var input_type  = $('#input-type');
	var input_sp = $('#input-sp');
	var input_ep = $('#input-ep');
	var input_catid = $('#input-catid');
	
	$('.product-option-cat a').click(function(){
		var cat_id = $(this).attr('data-catid');
		input_catid.val(cat_id);
		form.submit();	
	});
	
	//价格
	$('#priceBox a').click(function(){
		var sp = $(this).attr('data-sp');
		var ep = $(this).attr('data-ep');
		
		input_sp.val(sp);
		input_ep.val(ep);
		
		form.submit();
	});
	
	//排序
	$('.orderType').click(function(){
		var f = $(this).hasClass('sel');
		if (f){
			var orderValue = input_order.val();
			if (orderValue == 'asc'){
				input_order.val('desc');
			} else {
				input_order.val('asc');
				if (input_type.val() == ''){
					input_order.val('desc');	
				}
			}
		} else {
			var orderType = $(this).attr('data-type');
			input_order.val('desc');
			input_type.val(orderType);
		}
		form.submit();
	});
	
	$('#btn-price', form).click(function(){
		form.submit();
	});
	
	
	$('input', form).keyup(function(event){
		if (event.keyCode == 13){
			form.submit();
		}
	});	
});
</script>
<{include file="common/footer.tpl"}>