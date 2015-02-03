<html>
<head>
<title>商品图片上传工具</title>
<!--用的是这个-->
</head>
<style type="text/css">
body{ font-size:16px; font-family:'microsoft yahei', Arial, Helvetica; line-height:1.6; background:#FFF; color:#000; padding:30px;}
.wrap{ border:1px solid #ddd; padding:20px; margin-bottom:20px}
.msgbox p{ margin:0; padding:0; padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed #ddd;}
.msgbox span{ padding-right:12px; display:inline-block;}
.txt{border:1px solid #ccc;
border-top:1px solid #aaa;
padding:5px;
font-size:16px;
line-height:normal;
vertical-align:middle;
background:#FFF;}
.btn{ height:31px; width:60px; font-size:15px; vertical-align:middle}
</style>
<body>
<div class="msgbox wrap">
	<p>支持的品牌：</p>
	<div class="brandsbox">
	<{foreach $mapping as $item}>
		<span><{$item['brand_name']}></span>
	
	<{/foreach}>
	</div>
</div>

<div class="msgbox wrap">
	<p>操作流程：</p>
	<div class="options">
		1.确定并保证商品信息已经在后台添加完成。<br />
		2.保证韩国官网商品地址，在后台和下面输入框中一致。<br />
		3.不必再将生成的图片保存到商品内容里去。<br />
		4.如有问题联系：13004590915 -- [QQ]187222734<br />
		5.商品抓取不到，请移动到这里<a href="hand_upload.php">手动上传</a>。
	</div>
	
</div>
<div class="formbox wrap">
<{$tboauth_status}>
<p>
<form name="f" method="post" action="/urld">
	商品地址：
	<input type="text" size="140" name="url" value="<{$url}>" class="txt" autocomplete="false" />
	<input type="submit" name="submit" value="提交" class="btn" />
</form>

</p>
<{$msg}>
</div>
</body>
</html>