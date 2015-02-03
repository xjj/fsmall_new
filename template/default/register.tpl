<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/register.css" />

<div class="wrap reg-wrap clearfix">
	<div class="reg-main">
		<div class="reg-title">会员注册</div>
      	<form name="register" method="post" action="/register" id="RegForm">
      	<div class="reg-box">
            <div class="reg-box-wrap">
                <input type="text" name="email" class="txt reg-txt" autocomplete="off" title="邮箱" />
                <div class="reg-icon-email">邮箱</div>
                <div class="reg-box-tips" id="MsgBox0">电子邮箱</div>
            </div>
            <div class="reg-box-wrap">
                <input type="text" name="uname" class="txt reg-txt" autocomplete="off" title="用户名" />
                <div class="reg-icon-user">用户名</div>
                <div class="reg-box-tips" id="MsgBox1">用户名</div>
            </div>
            <div class="reg-box-wrap">
                <input type="password" name="upass" class="txt reg-txt" title="登录密码" />
                <div class="reg-icon-key">密码</div>
                <div class="reg-box-tips" id="MsgBox2">登录密码</div>
            </div>
            <div class="reg-box-wrap">
                <input type="password" name="upass2" class="txt reg-txt" title="确认密码" />
                <div class="reg-icon-key">确认密码</div>
                <div class="reg-box-tips" id="MsgBox3">确认登录密码</div>
            </div>
            
            <div class="reg-box-wrap reg-box-service">
                <input type="checkbox" name="service" value="1" checked="checked" /><span class="lpad">我已阅读并同意<a href="#" class="orange">《非尚网客户服务协议》</a></span>
            </div>
            <div class="reg-box-wrap reg-box-btn">
                <input type="submit" name="submit" value="注册" class="btn reg-btn" />
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
    <div class="reg-side">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" valign="middle"><a href="#" class="shopping-folw">购物流程</a></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="/brand" class="brand-center">品牌中心</a></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="#" class="sale-join">批发加盟</a></td>
          </tr>
        </table>
    </div>
</div>
<script type="text/javascript">
$(function(){
	var form = $('#RegForm');
	var input_email = $('input[name=email]', form);
	var input_uname = $('input[name=uname]', form);
	var input_upass = $('input[name=upass]', form);
	var input_upass2 = $('input[name=upass2]', form);
	var input_service = $('input[name=service]', form);
	var msgBox0 = $('#MsgBox0');
	var msgBox1 = $('#MsgBox1');
	var msgBox2 = $('#MsgBox2');
	var msgBox3 = $('#MsgBox3');
	
	MsgBox(input_email, msgBox0);
	MsgBox(input_uname, msgBox1);
	MsgBox(input_upass, msgBox2);
	MsgBox(input_upass2, msgBox3);
	
	function MsgBox(input, box){
		input.focus(function(){
			box.hide();		
		}).blur(function(){
			var value = $.trim($(this).val());
			if (value == ''){
				box.show();
			}	
		});
	}
});
</script>
<{include file="common/footer.tpl"}>