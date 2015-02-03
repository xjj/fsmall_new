<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到品牌列表</a>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$brand_data.brand_id}>">
	<input type="hidden" name="brand_id" value="<{$brand_data.brand_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">品牌名称</td>
		<td><input type="text" name="brand_name" class="txt" value="<{$brand_data.brand_name}>" /></td>
	  </tr>
	  <tr>
		<td class="ltd">LOGO</td>
		<td><input type="text" name="logo" size="50" class="txt txt2" value="<{$brand_data.logo}>" readonly="1" id="form-pic" />
			<a href="javascript:;" class="btn" id="upload-btn">选择图片</a>
			<span class="lpad">300×120</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><img src="<{$brand_data.logo}>" width="300" height="120" style="border:1px solid #ddd; padding:1px" /></td>
	  </tr>
      <tr>
		<td class="ltd">品牌栏目大图</td>
		<td><input type="text" name="pic" size="50" class="txt txt2" value="<{$brand_data.pic}>" readonly="1" id="form-pic2" />
			<a href="javascript:;" class="btn" id="upload-btn2">选择图片</a>
			<span class="lpad">1100×220</span>
		</td>
	  </tr>
      <tr>
		<td class="ltd"></td>
		<td><img src="<{$brand_data.pic}>" width="550" height="110" style="border:1px solid #ddd; padding:1px" /></td>
	  </tr>
	  <tr>
		<td class="ltd">官网地址</td>
		<td><input type="text" name="web_url" class="txt" size="50" value="<{$brand_data.web_url}>" />
			<span class="lpad">链接必须以 <b>http://</b> 开头</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">品牌类型</td>
		<td><select name="type">
			<option value="0">请选择</option>
			<{foreach $brand_types as $key => $val}>
			<option value="<{$key}>" <{if $brand_data.type eq $key}>selected="selected"<{/if}>><{$val}></option>
			<{/foreach}>
			</select>
		</td>
	  </tr>
      <tr>
		<td class="ltd">品牌介绍</td>
		<td><textarea name="content" cols="100" rows="5" class="txt"><{$brand_data.content}></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">关键词</td>
		<td><input type="text" name="keywords" class="txt" size="80" value="<{$brand_data.keywords}>" />
			<span class="lpad">页面优化，词与词之间用逗号(,)或空格分隔</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="description" class="txt" cols="80" rows="3"><{$brand_data.description}></textarea>
			<span class="lpad">页面优化，品牌的简要介绍</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">排序</td>
		<td><input type="text" name="displayorder" value="<{$brand_data.displayorder}>" class="txt" size="6" /></td>
	  </tr>
	  
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/upload.picture.js"></script>
<script type="text/javascript">
$(function(){
	
	var btn = $('#upload-btn')[0];
	upload.picture(btn, 'brand', function(d){
		if (d.error == 0){
			$('#form-pic').val(d.url);
		} else {
			popup.alert(d.message);
		}
	});
	
	var btn2 = $('#upload-btn2')[0];
	upload.picture(btn2, 'brand', function(d){
		if (d.error == 0){
			$('#form-pic2').val(d.url);
		} else {
			popup.alert(d.message);
		}
	});
});
</script>
<{include file="common/footer.tpl"}>
