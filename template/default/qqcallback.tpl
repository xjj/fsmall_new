<{include file="common/header.tpl"}>
<style>
.border2{width:100px;height:30px;border:2px solid #ccc;line-height:30px;cursor:pointer;}
.now{border:2px solid #0CF;}
</style>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/login.css" />

<div class="wrap login-wrap clearfix">

<div style="float:left;margin-right:8px;border:0px solid red;">
 <img src="<{$uinfo.figureurl_qq_1}>" /><br>
 昵称:<{$uinfo.nickname}><br>
性别:<{$uinfo.gender}><br>
省:<{$uinfo.province}><br>
市:<{$uinfo.city}><br>
出生年:<{$uinfo.year}><br> 
</div>
        <div style="float:left;margin:27px 8px 6px 0;">
            <div class="border2 now" id="haveuser" onclick="change_have()">已有非尚账号</div>
            <div class="border2" id="nouser" onclick="change_no()">无非尚账号</div>
        </div>
        
	<div class="login-box">
    	
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
        
        
		</form>
        
        <div class="login-third">
        	<div class="login-third-text">使用以下账号登录</div>
            <div class="login-third-icon">
            	<a href="/login/qq" class="icon-qq" title="qq">qq</a>
                <a href="/login/alipay" class="icon-alipay" title="支付宝">alipay</a>
            </div>
        </div>
	</div>
    
    
		<div class="reg-box" style="display:none;float:left;width:414px;heihgt:487px;">
        <form name="register" id="register-form" method="post" action="/register" id="register">
			<div class="reg-box-title">
				电子邮箱
			</div>
			<div class="reg-box-content">
				<input type="text" name="email" class="txt" />
			</div>
			<div class="reg-box-title">
				用户名
			</div>
			<div class="reg-box-content">
				<input type="text" name="uname" class="txt" value="<{$uinfo.nickname}>" />
			</div>
			<div class="reg-box-title">
				登录密码
			</div>
			<div class="reg-box-content">
				<input type="password" name="upass" class="txt" />
			</div>
			<div class="reg-box-title">
				确认密码
			</div>
			<div class="reg-box-content">
				<input type="password" name="upass2" class="txt" />
			</div>
			<div class="reg-box-title">
				
			</div>
			<div class="reg-box-content">
				<input type="submit" name="submit" value="同意服务协议并注册" id="reg_btn" class="btn2 reg-btn" />
			</div>
			<div class="reg-box-title">
				<a href="#">《非尚网客户服务协议》</a>
			</div>
            </form>
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
var form2 = $('#register-form');
$('#reg_btn').click(function(){
	form2.submit();
});

function change_no(){
	$('.reg-box').show();
	$('#nouser').addClass('now');
	$('#haveuser').removeClass('now');
	$('.login-box').hide();
}
function change_have(){
	$('.login-box').show();
	$('#haveuser').addClass('now');
	$('#nouser').removeClass('now');
	$('.reg-box').hide();
}

</script>