<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user">会员中心</a><span>&gt;</span>用户资料
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl tbl-border" style="width:600px;">
          <tr>
            <th colspan="2">用户名修改</th>
          </tr>
          <tr>
            <td class="ltd" width="80">新用户名</td>
            <td><input type="text" name="uname" value="<{$smarty.session.uname}>" class="txt" /></td>
          </tr>
          <tr>
            <td class="ltd">登录密码</td>
            <td><input type="password" name="upass" value="" class="txt" />
            </td>
          </tr>
          <tr>
            <td class="ltd"></td>
            <td><input type="submit" name="submit" class="btn2" value="提 交" /></td>
          </tr>
        </table>
	</div>
</div>

<{include file="common/footer.tpl"}>