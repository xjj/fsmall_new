<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/product_common.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/top50">TOP50</a>
</div>

<div class="wrap">
	<!--商品选择项-->
    <form name="TopForm" id="TopForm" method="get" action="/top50">
    <input type="hidden" name="month" value="<{$smarty.get.month}>" id="input-month" />
    <div class="product-option">
        <div class="product-option-item product-option-cat product-option-cat3">
            <span>商品分类：</span>
            <select name="cat_id" id="input-catid">
            <option value="">所有类目</option>
            <{if $cat_items}>
            <{foreach $cat_items as $item}>
                <option value="<{$item.cat_id}>" <{if $smarty.get.cat_id eq $item.cat_id}>selected="selected"<{/if}>><{$item.cat_name}></option>
                <{if $item.items}>
                <{foreach $item.items as $item2}>
                    <option value="<{$item2.cat_id}>" <{if $smarty.get.cat_id eq $item2.cat_id}>selected="selected"<{/if}>>
                        &nbsp;&nbsp;<{if $item2@last}>└─<{else}>├─<{/if}> <{$item2.cat_name}>
                    </option>
                    <{if $item2.items}>
                    <{foreach $item2.items as $item3}>
                        <option value="<{$item3.cat_id}>" <{if $smarty.get.cat_id eq $item3.cat_id}>selected="selected"<{/if}>>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<{if $item3@last}>└─<{else}>├─<{/if}> <{$item3.cat_name}>
                        </option>
                    <{/foreach}>
                    <{/if}>
                <{/foreach}>
                <{/if}>
            <{/foreach}>
            <{/if}>
            </select>
        </div>
        <div class="product-option-item whiteBG product-option-month" id="monthBox">
            <span>查询时间：</span>
            <a href="javascript:;" data-month="1" <{if $smarty.get.month eq 1}>class="sel"<{/if}>>一个月内</a>
            <a href="javascript:;" data-month="2" <{if $smarty.get.month eq 2}>class="sel"<{/if}>>二个月内</a>
            <a href="javascript:;" data-month="3" <{if $smarty.get.month eq 3 or $smarty.get.month eq ''}>class="sel"<{/if}>>三个月内</a>
            <a href="javascript:;" data-month="6" <{if $smarty.get.month eq 6}>class="sel"<{/if}>>六个月内</a>
        </div>
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
</div>
<script type="text/javascript">
$(function(){
	var form = $('#TopForm');
	var input_brand = $('#input-brand');
	var input_month = $('#input-month');
	var input_catid = $('#input-catid');
	
	$('#monthBox a').click(function(){
		var month = $(this).attr('data-month');
		
		input_month.val(month);
		
		form.submit();	
	});
	
	input_brand.change(function(){
		form.submit();	
	});
	
	input_catid.change(function(){
		form.submit();	
	});
});
</script>
<{include file="common/footer.tpl"}>