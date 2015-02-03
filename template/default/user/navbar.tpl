<!--会员中心导航栏-->
<div class="navbar">
	<div class="navbar-user">
		<img src="<{$user_data.head_large}>" class="navbar-uhead" />
		<div class="navbar-uname"><{$user_data.uname}></div>
	</div>
	<ul class="amount">
		<li>余额：<{$user_data.balance}> (￥)</li>
		<li>积分：<{$user_data.score}></li>
	</ul>
	<ul class="navs">
		<li><a href="/order">我的订单</a></li>
		<li><a href="/cart">我的购物车</a></li>
		<li><a href="/favorite">我的收藏夹</a></li>
        <li><a href="/user/refund">退换货明细</a></li>
	</ul>
	<ul class="navs">
		<li><a href="/user/profile">帐号资料</a></li>
		<li><a href="/user/password">修改密码</a></li>
		<li><a href="/user/head">设置头像</a></li>
		<li><a href="/user/address">收货地址</a></li>
	</ul>
	<ul class="navs">
		<li><a href="/user/upgrade">申请成为批发会员</a></li>
	</ul>
</div>