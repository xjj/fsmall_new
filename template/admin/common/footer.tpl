<{if $islogin}>
	
	<script type="text/javascript">
	$(function(){
		navHide();
	});
	</script>
	<{if $smarty.cookies.AdminSideBar eq 1}>
		<style type="text/css">
		body{ padding-left:0;}
		.admin-side{ display:none;}
		</style>
		<{$sidectlhide = 'side-ctl-hide'}>
	<{/if}>
	<div class="side-ctl <{$sidectlhide}>" id="side-ctl"></div>
<{/if}>
</body>
</html>