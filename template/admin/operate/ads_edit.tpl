<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到广告位列表</a>
		<a href="/<{$mod}>/<{$col}>/ads/<{$adp_data.adp_id}>" class="btn btn3 lpad">返回到广告位(<{$adp_data.adp_code}>)的图片列表</a>
	</div>
	
	<hr class="line" />
	
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/ads/<{$adp_data.adp_id}>/edit/<{$ads_data.ad_id}>">
	<input type="hidden" name="adp_id" value="<{$ads_data.adp_id}>" />
	<input type="hidden" name="ad_id" value="<{$ads_data.ad_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td width="80" class="ltd">广告图片</td>
		<td><input type="text" name="pic" class="txt txt2" size="50" readonly="readonly" id="form-pic" value="<{$ads_data.pic}>" />
			<a href="javascript:;" class="btn" id="upload-btn">选择图片</a>
			<span class="lpad"><{$adp_data.width}>×<{if $adp_data.height gt 0}><{$adp_data.height}><{else}>auto<{/if}> 必填项</span>
		</td>
	  </tr>
	  <tr>
	  	<td class="ltd"></td>
		<td><img src="<{$ads_data.pic_url}>" style="width:<{$adp_data.width}>px; height:<{if $adp_data.height gt 0}><{$adp_data.height}>px<{else}>auto<{/if}>;" /></td>
	  </tr>
	  <tr>
		<td class="ltd">标题</td>
		<td><input type="text" name="title" class="txt" size="50" value="<{$ads_data.title}>" />
		</td>
	  </tr>
	  <tr>
		<td  class="ltd">链接地址</td>
		<td><input type="text" name="url" class="txt" size="50" value="<{$ads_data.url}>" />
			<span class="lpad">链接若不为空，必须以 <b class="red">http://</b> 开头</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="content" class="txt" cols="100" rows="3"><{$adsdata.content}></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" class="txt" size="10" value="<{$ads_data.displayorder}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/upload.js"></script>
<script type="text/javascript">
$(function(){
	var btn = $('#upload-btn')[0];
	upload(btn, 'advs', function(d){
		if (d.error == 0){
			$('#form-pic').val(d.path);
		} else {
			popup.alert(d.message);
		}
		
	});
});
</script>
<{include file="common/footer.tpl"}>