<!--顶部栏-->
<div class="header-topline">
	<div class="wrap clearfix">
		<div class="z ilogo"></div>
		<div class="z ilogin">
			<span>你好，欢迎光临非尚！</span>
			<{if $islogin}>
				<{$smarty.session.uname}>， <a href="/logout">退出</a>
			<{else}>
				<a href="/login">[登录]</a> <a href="/register">[注册]</a>
			<{/if}>
		</div>
		<ul class="y iright">
			<li><a href="/user">会员中心</a></li>
			<li><a href="/order">我的订单</a></li>
			<li><a href="/cart" id="head-cart">购物车</a></li>
			<li><a href="/favorite">收藏夹</a></li>
			<li><a href="/newitems">新品上市</a></li>
			<li><a href="/soldout">断货列表</a></li>
		</ul>
	</div>
</div>

<!--LOGO 搜索 -->
<div class="header-main wrap clearfix">
	<!--搜索框-->
	<div class="header-search">
		<form name="header-search" id="headerSearch" method="get" action="/search">
		<div class="header-search-box">
			<input type="text" name="kw" size="50" value="<{$smarty.get.kw}>" autocomplete="off" />
			<a href="javascript:;" id="search-btn" class="header-search-btn" onclick="$('#headerSearch').submit();">搜索</a>
		</div>
		</form>
	</div>
	<!--LOGO-->
	<div class="header-logo">
		<a href="/"><img src="/images/<{$template}>/common/logo.png" /></a>
	</div>
	<div class="y">
		
	</div>
</div>
<script type="text/javascript">
var headerForm = $('#header-search');
var headerSearchBtn = $('#header-search-btn');
headerSearchBtn.click(function(){
	headerForm.submit();
});
headerForm.keyup(function(event){
	if (event.keyCode == 13){$(this).submit();}
});
</script>

<!--导航-->
<div class="header-navs">
	<div class="wrap clearfix">
		<div class="navs-cat">
			<p><a href="/categorys">全部商品类目</a></p>
			<ul class="navs-catbox">
				<li class="cat-girl">
                	<a href="/category/1">女装</a>
                	<ul class="navs-catbox2">
                    	<li><a href="">连衣裙</a></li>
                        <li><a href="">半身裙</a></li>
                        <li><a href="">休闲裤</a></li>
                        <li><a href="">打底裤</a></li>
                    </ul>
                </li>
				<li class="cat-boy"><a href="/category/2">男装</a></li>
				<li><a href="/category/3">童装</a></li>
				<li><a href="/category/4">情侣装</a></li>
				<li class="cat-shoes"><a href="/category/12">鞋子</a></li>
				<li class="cat-bag"><a href="/category/6">箱包</a></li>
				<li class="cat-acc"><a href="/category/7">配饰</a></li>
				<li class="cat-jew"><a href="/category/8">饰品</a></li>
			</ul>
		</div>
		<div class="z"><a href="/brand">品牌中心</a></div>
		
		<div class="y">
			<a href="/">首页</a>
			<a href="/top50">TOP50</a>
			<a href="/spot">现货区</a>
            <a href="/special">特卖区</a>
		</div>
	</div>
</div>