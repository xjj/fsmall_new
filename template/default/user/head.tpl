<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user">会员中心</a><span>&gt;</span><a href="/user/head">设置头像</a>
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox clearfix">
		<div class="head-main">
			<!--图片预览框-->
			<table border="0" cellspacing="0" cellpadding="0" class="cropbox">
			  <tr>
				<td valign="middle" align="center" id="cropBox"></td>
			  </tr>
			</table>
			<!--图片信息表单-->
			<form name="headform" method="post" id="headform" action="/user/head">
				<input type="hidden" name="pic" />
				<input type="hidden" name="w" />
				<input type="hidden" name="h" />
				<input type="hidden" name="x" />
				<input type="hidden" name="y" />
				<input type="hidden" name="xw" />
				<input type="hidden" name="xh" />
				<input type="submit" name="submit" class="btn2" id="btn-submit" value="裁切头像" />
			</form>
		</div>
		
		<div class="head-thumb">
			<!--图片上传按钮-->
			<div class="head-upload">
				<input type="button" class="btn" id="btn-upload" value="选择头像图片" />
			</div>
			<div class="head-tips" id="head-tips">支持JPG、JPEG、PNG格式（3M以下，300×300 以上）</div>
			
			<!--预览缩略图-->
			<table border="0" cellspacing="0" cellpadding="0" id="preview" class="tbl-preview">
			  <tr>
				<td>
					<div class="preview preview200">
						<img src="<{$user_data.head_large}>" id="thumb200" />
					</div>
				</td>
				<td>
					<div class="preview preview50">
						<img src="<{$user_data.head_thumb}>" id="thumb50" />
					</div>
				</td>
			  </tr>
			</table>
		</div>
		
	</div>
</div>
<{include file="common/footer.tpl"}>
<link type="text/css" rel="stylesheet" href="/js/jcrop/jquery.jcrop.css" />
<script type="text/javascript" src="/js/jcrop/jquery.jcrop.js"></script>
<script type="text/javascript" src="/js/upload.js"></script>
<script type="text/javascript" src="/js/crop.js"></script>
<script type="text/javascript">
$(function(){
	var Tips = $('#msg');
	crop.init({
		'cropBox' : $('#cropBox')[0],
		'formBox' : $('#headform')[0],
		'preview' : $('#preview')[0],
		'z'		  : 300,
		'w'		  : 200,
		'h'		  : 200
	});
	$('#crop-submit').click(function(){
		crop.send('/user/head', function(d){
			if (d.error == 0){
				location.reload();
			} else {
				Tips.html(d.message).slideDown(200);
				setTimeout(function(){Tips.slideUp(200);}, 5000);
			}
		});
	}); 
	
	var btn = $('#btn-upload')[0];
	upload(btn, 'head', function(d){
		crop.initBox(d);
	});
});
</script>