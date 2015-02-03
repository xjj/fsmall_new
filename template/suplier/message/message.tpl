<{include file="common/header.tpl"}>
<div class="suplier-wrap clearfix">
	<div class="cpmsg">
		<div class="cpmsg-title">消息提示</div>
		<div class="cpmsg-content <{if $message.status}>cpmsg-success<{/if}>"><{$message.content}></div>
		<div class="cpmsg-url">
			<a href="javascript:;" onclick="gourl('<{$message.url}>');">如果您的浏览器没有自动跳转，请点击这里</a>
		</div>
	</div>
</div>
<script type="text/javascript">
var seconds = <{$message.seconds}> * 1000;
setTimeout(function(){gourl('<{$message.url}>');}, seconds);
function gourl(url){
	if (url == '-1'){
		history.go(-1);
	} else {
		location.href = url;
	}
}
</script>
<{include file="common/footer.tpl"}>