<{if $islogin}>
	<script type="text/javascript" src="/js/suplier.js"></script>
	<script type="text/javascript">
	$(function(){
		tabLine();
		hoverLine();
		navTab();
		navHide();
	});
	</script>
	<{if $smarty.cookies.sidebar_status eq 1}>
		<style type="text/css">
		body{ padding-left:0;}
		.suplier-side{ display:none;}
		</style>
		<{$sidectlhide = 'side-ctl-hide'}>
	<{/if}>
	<div class="side-ctl <{$sidectlhide}>" id="side-ctl"></div>
<{/if}>
</body>
</html>