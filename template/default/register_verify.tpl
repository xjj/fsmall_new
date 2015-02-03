<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/register.css" />

<div class="wrap reg-wrap">
	<div class="reg-title">邮箱验证</div>
	<div class="reg-verify">
		<div class="reg-verify-title">
			第一步：发送验证码到邮箱 <span class="bold orange"><{$email}></span>，有效期为一小时，请注意查收
		</div>
		<div class="reg-verify-content">
			<a href="javascript:;" id="btn-mail" class="btn2" data-email="<{$email}>" data-uid="<{$uid}>">发送验证邮件</a>
			<span class="lpad" id="msg-mail"></span>
		</div>
	</div>
	
	<div class="reg-verify">
		<div class="reg-verify-title">
			第二步：输入邮箱验证码
		</div>
		<div class="reg-verify-content">
			<form name="v" method="get" action="/register/verify">
			<input type="hidden" name="e" value="<{$smarty.get.e}>" />
			<input type="text" name="c" class="txt" size="14" />
			<input type="submit" name="submit" class="btn2" value="提 交" />
			</form>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
var state_mail = 0;
$('#btn-mail').click(function(){
	if (state_mail == 1){return false;}
	
	var email = $(this).attr('data-email');
	var uid = $(this).attr('data-uid');
	var msgbox = $('#msg-mail');
	
	$.post('/register/sendmail', {'uid':uid, 'email':email}, function(dt){alert(dt);
		state_mail = 0;
		var d = json(dt);
		if (d.error == 0){
			msgbox.html('验证邮件已发送。');
		} else {
			msgbox.html(d.message);
		}
	});
});
</script>