<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/prd.css" />
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/favo.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>我的收藏夹
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox clearfix">
		<{if $prd_items}>
		<{foreach $prd_items as $item}>
		<div class="prd-item">
			<a href="/product/<{$item.prd_id}>"><img src="<{$item.pic_thumb}>" width="260" height="260" class="prd-item-pic" /></a>
			<div class="prd-item-title">
				<a href="/product/<{$item.prd_id}>"><{$item.prd_name}></a>
			</div>
			<div class="prd-item-price">￥<{$item.sale_price|string_format:'%d'}></div>
		</div>
		<{/foreach}>
		<{/if}>
	</div>
	
	<{$pagebox}>
</div>
<{include file="common/footer.tpl"}>