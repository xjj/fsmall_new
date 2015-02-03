<!DOCTYPE html>
<html class="hasFontSmoothing-true">
<head>
<meta charset="utf-8" />
<link type="text/css" rel="stylesheet" href="/images/style.css" />
<link type="text/css" rel="stylesheet" href="/images/popup.css" />
<script type="text/javascript" src="/js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="/js/base.js"></script>
<script type="text/javascript" src="/js/popup.js"></script>
<title><{$LANG.TITLE}></title>
</head>
<body>
<{if $islogin}>
<div class="suplier-side clearfix">
	<{if $navArrs}>
	<{foreach $navArrs as $key => $val}>
		<div class="navs-box">
			<div class="navs-title">
				<a href="/<{$key}>" <{if $mod eq $key}>class="navs-sel"<{/if}>><{$val}></a>
			</div>
		</div>
	<{/foreach}>
	<{/if}>
	
	<div class="side-user">
		<div class="side-user-box">
		<{$LANG.WELCOME}>，<{$smarty.session.suplier.sp_uname}>，
		<a href="/logout"><{$LANG.LOGOUT}></a>
		</div>
	</div>
</div>
<{/if}>
