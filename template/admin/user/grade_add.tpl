<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到会员等级列表</a>
	</div>
	<hr class="line" />
	<form name="f" method="post" action="/<{$mod}>/<{$col}>/add">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">会员等级名称</td>
		<td><input type="text" name="grade_name" size="30" class="txt">
			<span class="lpad">用于生产环境之后，请尽量不要修改该值</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">折扣值</td>
		<td><input type="text" name="discount" class="txt" size="10" value="100" />
			<span class="lpad">（%） 值的范围为 1 ~ 200，等于100时不打折。</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" class="btn2" value="提交" /></td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>