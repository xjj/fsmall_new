<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
			<select name="grade">
			<option value="">--会员等级--</option>
			<{if $grade_items}>
			<{foreach $grade_items as $item}>
			<option value="<{$item.grade_id}>" <{if $item.grade_id eq $smarty.get.grade}>selected="selected"<{/if}>><{$item.grade_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			
			<select name="grade_tb">
			<option value="0">--淘宝等级--</option>
			<option value="1" <{if $smarty.get.grade_tb eq 1}>selected="selected"<{/if}>>1心</option>
			<option value="2" <{if $smarty.get.grade_tb eq 2}>selected="selected"<{/if}>>2心</option>
			<option value="3" <{if $smarty.get.grade_tb eq 3}>selected="selected"<{/if}>>3心</option>
			<option value="4" <{if $smarty.get.grade_tb eq 4}>selected="selected"<{/if}>>4心</option>
			<option value="5" <{if $smarty.get.grade_tb eq 5}>selected="selected"<{/if}>>5心</option>
			<option value="6" <{if $smarty.get.grade_tb eq 6}>selected="selected"<{/if}>>1钻</option>
			<option value="7" <{if $smarty.get.grade_tb eq 7}>selected="selected"<{/if}>>2钻</option>
			<option value="8" <{if $smarty.get.grade_tb eq 8}>selected="selected"<{/if}>>3钻</option>
			<option value="9" <{if $smarty.get.grade_tb eq 9}>selected="selected"<{/if}>>4钻</option>
			<option value="10" <{if $smarty.get.grade_tb eq 10}>selected="selected"<{/if}>>5钻</option>
			<option value="11" <{if $smarty.get.grade_tb eq 11}>selected="selected"<{/if}>>1蓝冠</option>
			<option value="12" <{if $smarty.get.grade_tb eq 12}>selected="selected"<{/if}>>2蓝冠</option>
			<option value="13" <{if $smarty.get.grade_tb eq 13}>selected="selected"<{/if}>>3蓝冠</option>
			<option value="14" <{if $smarty.get.grade_tb eq 14}>selected="selected"<{/if}>>4蓝冠</option>
			<option value="15" <{if $smarty.get.grade_tb eq 15}>selected="selected"<{/if}>>5蓝冠</option>
			<option value="16" <{if $smarty.get.grade_tb eq 16}>selected="selected"<{/if}>>1金冠</option>
			<option value="17" <{if $smarty.get.grade_tb eq 17}>selected="selected"<{/if}>>2金冠</option>
			<option value="18" <{if $smarty.get.grade_tb eq 18}>selected="selected"<{/if}>>3金冠</option>
			<option value="19" <{if $smarty.get.grade_tb eq 19}>selected="selected"<{/if}>>4金冠</option>
			<option value="20" <{if $smarty.get.grade_tb eq 20}>selected="selected"<{/if}>>5金冠</option>
			</select>
			
			<select name="status">
			<option value="">--帐号状态--</option>
			<option value="1" <{if $smarty.get.status eq 1}>selected="selected"<{/if}>>已激活</option>
			<option value="0" <{if $smarty.get.status eq 0 && $smarty.get.status neq ''}>selected="selected"<{/if}>>未激活</option>
			</select>
			
			<span class="lpad">用户名/邮箱/手机号：</span>
			<input type="text" name="k" size="20" class="txt" value="<{$smarty.get.k}>" />
			<a href="javascript:;" id="btn-submit" class="btn">查询</a>
		</form>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th width="13"><input type="checkbox" name="chkall1" id="chkall1" value="1" /></th>
		<th>UID</th>
		<th>用户名</th>
		<th>邮箱</th>
		<th>会员等级</th>
		<th>账户余额</th>
		<th>积分</th>
		<th>注册时间</th>
		<th>最后登录</th>
		<th>最后登录IP</th>
		<th>账号状态</th>
		<th>操作</th>
	  </tr>
	  <{if $user_items}>
	  <{foreach $user_items as $item}>
	  <tr>
		<td><input type="checkbox" name="uid[<{$item.uid}>]" id="chkall1" value="<{$item.uid}>" /></td>
		<td><{$item.uid}></td>
		<td><{$item.uname}></td>
		<td><{$item.email}></td>
		<td><{$grade_items[$item.grade_id].grade_name}></td>
		<td><{$item.balance}></td>
		<td><{$item.score}></td>
		<td><{$item.reg_time|date_format:'Y-m-d H:i'}></td>
		<td><{$item.last_login_time|date_format:'Y-m-d H:i'}></td>
		<td><{$item.last_login_ip}></td>
		<td><{if $item.status eq 1}><span class="green">已激活</span><{else}><span class="gray">未激活</span><{/if}></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.uid}>">编辑</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/addr/<{$item.uid}>">地址</a>
            <span class="dl">|</span>
            <a href="/<{$mod}>/score/add/<{$item.uid}>">±积分</a>
            <span class="dl">|</span>
            <a href="/<{$mod}>/money/add/<{$item.uid}>">±账目</a>
			<span class="dl">|</span>
			<a href="javascript:;" class="btn-del" data-href="/<{$mod}>/<{$col}>/del/<{$item.uid}>">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{else}>
	  <tr class="nohover">
	  	<td colspan="14">用户信息为空！</td>
	  </tr>
	  <{/if}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall2" id="chkall2" value="1" /></td>
		<td colspan="13"><a href="javascript:;" id="btn-delall" class="btn2 btn3">删除选中的用户</a></td>
	  </tr>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	var searchForm = $('#search-form');
	var submitBtn  = $('#btn-submit');
	
	submitBtn.click(function(){
		searchForm.submit();
	});
	
	searchForm.keyup(function(event){
		if (event.keyCode == 13){
			submitBtn.trigger('click');
		}
	});
	
});
</script>
<{include file="common/footer.tpl"}>