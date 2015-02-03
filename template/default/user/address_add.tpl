<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user">会员中心</a><span>&gt;</span><a href="/user/address">收货地址</a><span>&gt;</span>添加收货地址
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
		
	</div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript" src="/js/region.js"></script>
<script type="text/javascript">
$(function(){
	//联动获取省市县
	$('#sebox select').change(function(){
		var id  = $(this).val();
		var iem = $(this).next('select');
		var cem = iem.nextAll('select');
		var c = {'id' : id};
		if (iem.length > 0){
			c.iem = iem[0];
			if (cem.length > 0){c.cem = cem;}
			region.children(c);
		}
	});
	
	//设置邮编的值
	$('#county').change(function(){
		var id = $(this).val();
		var zipcodeInput = $('#zipcode');
		if (id > 0){
			var zipcode = $('option:selected', this).attr('data-zipcode');
			if (zipcode > 0){
				var code = zipcodeInput.val();
				if ($.trim(code) == ''){
					zipcodeInput.val(zipcode);
				}
			}	
		}
	});
});
</script>