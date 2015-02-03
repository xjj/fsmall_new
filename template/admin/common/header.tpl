<!DOCTYPE html>
<html class="hasFontSmoothing-true">
<head>
<meta charset="utf-8" />
<link type="text/css" rel="stylesheet" href="/images/style.css" />
<link type="text/css" rel="stylesheet" href="/images/popup.css" />
<script type="text/javascript" src="/js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="/js/base.js"></script>
<script type="text/javascript" src="/js/popup.js"></script>
<script type="text/javascript" src="/js/admin.js"></script>
<title>非尚服装管理中心</title>
<{if $islogin}>
<script type="text/javascript">
$(function(){
	tabLine();
	hoverLine();
	navTab();
});
function toggleallmenu(){//全合住 
	$(".navs-box .navs-items").hide();

}

</script>
<{/if}>
</head>
<body>
<{if $islogin}>
<div class="admin-side">
	<div class="admin-word">
    	<div style="width:20px;height:20px;float:right;margin-bottom:0px; margin-top:20px;" onClick="toggleallmenu()"><img width="13" height="13" src="/images/<{$template}>/menu_minus.gif" alt="折叠全部菜单">
        </div>
    </div>
	<{if $navArrs}>
	<{foreach $navArrs as $key => $val}>
		<div class="navs-box">
			<div class="navs-title clearfix">
				<div class="z"><{$val.text}></div>
				<div class="y tab <{if $key eq $mod }>tab-open<{/if}>"></div>
			</div>
			<{if $val.items}>
			<ul class="navs-items" <{if $key neq $mod}>style="display:none"<{/if}> >
				<{foreach $val.items as $key2 => $val2}>
					<li <{if $key eq $mod and $key2 eq $col}>class="sel"<{/if}>><a href="/<{$key}>/<{$key2}>"><{$val2}></a></li>
				<{/foreach}>
			</ul>
			<{/if}>
		</div>
	<{/foreach}>
	<{/if}>
</div>

<div class="header clearfix">
	<div class="header-navs">
		<a href="/">首页</a>
		<{if $mod neq 'index'}>
			<span class="dl-navs">&gt;</span>
			<a href="/<{$mod}>/<{$col}>"><{$navArrs.$mod.text}></a>
			<{if $col}>
				<span class="dl-navs">&gt;</span>
				<a href="/<{$mod}>/<{$col}>"><{$navArrs.$mod.items.$col}></a>
			<{/if}>
			<{if $more_navs}>
				<{foreach $more_navs as $nav_item}>
					<span class="dl-navs">&gt;</span>
					<{$nav_item}>
				<{/foreach}>
			<{/if}>
		<{/if}>
	</div>
	<div class="header-user">
		欢迎，<a href="/"><{$smarty.session.admin.uname}></a>
		<span class="dl">|</span>
		<a href="#" target="_blank">网店前台</a>
		<span class="dl">|</span>
		<a href="/logout">退出</a>
	</div>
</div>
<{/if}>

