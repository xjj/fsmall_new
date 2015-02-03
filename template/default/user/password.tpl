<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>修改密码
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">修改登录密码</div>
        <div class="user-main">
            <form name="f" method="post" action="/user/password">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="user-tbl">
              <tr>
                <td class="ltd" width="80">原密码：</td>
                <td><input type="password" name="upass" class="txt" size="30" /></td>
              </tr>
              <tr>
                <td class="ltd">新密码：</td>
                <td><input type="password" name="upass_new" class="txt" size="30" />
                    <span class="lpad">密码位数 6 ~ 18</span>
                </td>
              </tr>
              <tr>
                <td class="ltd">确认密码：</td>
                <td><input type="password" name="upass_new_confirm" class="txt" size="30" /></td>
              </tr>
              <tr>
                <td class="ltd"></td>
                <td><input type="submit" name="submit" class="btn2" value="提 交" /></td>
              </tr>
            </table>
            </form>
        </div>
	</div>
</div>
<{include file="common/footer.tpl"}>