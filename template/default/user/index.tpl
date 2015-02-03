<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>个人中心
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="uc-box clearfix">
        	<div class="uc-head"><img src="<{$user_data.head_thumb}>" width="100" height="100" /></div>
            <div class="uc-info">
            	<div class="uc-uname"><{$user_data.uname}></div>
                <div class="uc-counter">
                	<span class="rpad">账户余额：<{$user_data.balance}></span>
                    <span class="lpad rpad">积分：<{$user_data.score}></span>
                    <span class="lpad">邮箱：<{$user_data.email}></span>
                    <span class="lpad"><a href="/user/email" class="icon-edit" title="更换邮箱">更换邮箱</a></span>
                </div>
            </div>
        </div>
        
        <!--最近5次登录-->
        <div class="uc-login">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
              <tr>
                <th width="200">时间</th>
                <th>IP</th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
        </div>
    </div>
</div>

<{include file="common/footer.tpl"}>