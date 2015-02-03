<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/product_common.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/favo.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>我的收藏夹
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">我的收藏夹</div>
        <div class="prd-favos clearfix">
            <{if $product_items}>
            <{$is_favorite = 1}>
            <{foreach $product_items as $item}>
            	<{include file="product_item.tpl"}>
            <{/foreach}>
            <{/if}>
            <{$pagebox}>
        </div>
        
	</div>
</div>
<script type="text/javascript">
$(function(){
		
});
</script>
<{include file="common/footer.tpl"}>