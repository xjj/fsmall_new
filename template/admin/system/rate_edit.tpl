<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
			<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到汇率列表</a>
		</div>
		<div class="y"></div>
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit/<{$rate_data.date_line}>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">日期</td>
		<td><input type="text" name="date_line" class="txt txt2" readonly="readonly" value="<{$rate_data.date_line}>" />
			<span class="lpad">日期格式如：YYYYMMDD</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩元汇率(KRW)</td>
		<td><input type="text" name="krw" class="txt" value="<{$rate_data.krw}>" />
			<span class="lpad">1KRW = ?RMB 精确到小数点后6位</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">美元汇率(USD)</td>
		<td><input type="text" name="usd" class="txt" value="<{$rate_data.usd}>" />
			<span class="lpad">1USD = ?RMB 精确到小数点后6位</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">备注</td>
		<td><textarea cols="100" rows="3" name="content" class="txt"><{$rate_data.content}></textarea>
			<span class="lpad">200个汉字以内</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" value="提交" class="btn2" name="submit" />
		</td>
	  </tr>
	</table>
	</form>
</div>
<{include file="common/footer.tpl"}>