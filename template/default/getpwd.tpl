<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/getpwd.css" />

<div class="wrap getpwd-wrap">
	<div class="getpwd-title">通过邮箱找回密码</div>
	<div class="getpwd-box">
		<div class="getpwd-box-title">
			输入邮箱，发送密码重置邮件，有效期为一小时，请注意查收
		</div>
		<div class="getpwd-box-content">
			<input type="text" class="txt" name="email" size="30" id="input-email" />
			<a href="javascript:;" id="btn-mail" class="btn2">发送邮件</a><br />
			<div class="msg-mail" id="msg-mail"></div>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>
<script type="text/javascript">
var state_mail = 0;
$('#btn-mail').click(function(){
	if (state_mail == 1){return false;}
	
	var email = $('#input-email').val();
	var msgbox = $('#msg-mail');
	
	$.post('/getpwd/sendmail', {'email':email}, function(dt){
		state_mail = 0;
		var d = json(dt);
		if (d.error == 0){
			msgbox.html('密码重置邮件已发送，请到邮箱查收。');
		} else {
			msgbox.html(d.message);
		}
	});
});
</script>