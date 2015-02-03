<{include file="common/header.tpl"}>

<div class="wrap msg-wrap">
	<div class="msg-box">
		<div class="msg-left-top"></div>
		<div class="msg-left-bottom"></div>
		<div class="msg-right-top"></div>
		<div class="msg-right-bottom"></div>
		
		<div class="cpmsg <{if $msgdata.status}>cpmsg-success<{/if}>">
			<div class="cpmsg-content"><{$msgdata.content}></div>
			<div class="cpmsg-url">
				<a href="javascript:;" onclick="gourl('<{$msgdata.url}>');">如果您的浏览器没有自动跳转，请点击这里</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
setTimeout(function(){gourl('<{$msgdata.url}>');}, 5000);
function gourl(url){
	if (url == '-1'){
		window.history.go(-1);
	} else {
		location.href = url;
	}
}
</script>
<{include file="common/footer.tpl"}>