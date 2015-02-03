<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/brand.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/brand">所有品牌</a>
</div>

<div class="wrap">
	<{if $brand_items}>
    <{foreach $brand_items as $key => $itemBox}>
	<div class="box-wrap">
    	<div class="box-title">
        	<{if $key eq 1}>
            	女装
        	<{elseif $key eq 2}>
            	男装
            <{elseif $key eq 3}>
        		童装
            <{elseif $key eq 4}>
            	情侣装
            <{elseif $key eq 5}>
            	孕妇装
            <{elseif $key eq 6}>
            	综合
            <{elseif $key eq 7}>
            	其他 
        	<{/if}>
        </div>
    	<div class="box-content">
        	<div class="brand-thumb-wrap clearfix">
                <{foreach $itemBox as $item}>
                <div class="brand-thumb">
                    <a href="/brand/<{$item.brand_id}>"><img src="<{$item.logo}>" /></a>
                    <div class="brand-thumb-title clearfix">
                    	<div class="z"><a href="/brand/<{$item.brand_id}>"><{$item.brand_name|capitalize}></a></div>
                        <div class="y brand-thumb-source"><a href="<{$item.web_url}>" target="_blank">官网</a></div>
                    </div>
                </div>
                <{/foreach}>
            </div>
        </div>
    </div>
    <{/foreach}>
    <{/if}>
</div>
<{include file="common/footer.tpl"}>