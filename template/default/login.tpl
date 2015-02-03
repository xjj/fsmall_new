<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/login.css" />

<div class="wrap login-wrap clearfix">
	<div class="login-box">
    	<div class="login-userhead"></div>
        
		<form name="login" id="login-form" method="post" action="/login">
		<div class="login-box-title">会员登录</div>
        
        <div class="login-input">
        	<div class="login-input-user"></div>
        	<input type="text" class="txt login-txt" name="uname" size="30" autocomplete="off" />
        </div>
        
        <div class="login-input">
        	<div class="login-input-pwd">密码</div>
        	<input type="password" class="txt login-txt login-txt2" name="upass" size="30" />
            <input type="submit" name="submit" class="login-button" id="btn-submit" value="登录" />
        </div>
        
        <div class="login-input-other clearfix">
            <div class="z">
                <input type="checkbox" name="remember" value="1" checked="checked" /><span class="lpad">记住密码</span>
            </div>
            <div class="y">
                <span><a href="/getpwd">忘记密码？</a></span><span class="dl">|</span><span><a href="/register" class="orange">免费注册</a></span>
            </div>
        </div>
		</form>
        
        <div class="login-third">
        	<div class="login-third-text">使用以下账号登录</div>
            <div class="login-third-icon">
            	<a href="/login/qq" class="icon-qq" title="qq">qq</a>
                <a href="/login/alipay" class="icon-alipay" title="支付宝">alipay</a>
            </div>
        </div>
	</div>
    <div class="login-focus">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" valign="middle"><img src="/images/default/login/prd.png" width="460" height="300" /></td>
          </tr>
        </table>
    </div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
var form = $('#login-form');
$('#btn-submit').click(function(){
	form.submit();
});

$('.login-txt input', form).keyup(function(event){
	if (event.keyCode = 13){
		form.submit();	
	}	
});
</script>