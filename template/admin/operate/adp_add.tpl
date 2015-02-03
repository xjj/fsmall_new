<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到广告位列表</a> 
	</div>
	<hr class="line" />
	<form name="form" method="post" action="/<{$mod}>/<{$col}>/add">
	<table border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">广告位代码</td>
		<td><input type="text" name="adp_code" class="txt" size="15" />
			<span class="lpad">调用广告时使用的代码，唯一</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">广告位描述</td>
		<td><textarea name="content" class="txt" cols="100" rows="3"></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">宽度</td>
		<td><input type="text" name="width" class="txt" size="15" />
			<span class="lpad">广告位的宽度值，整数</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">高度</td>
		<td><input type="text" name="height" class="txt" size="15" />
			<span class="lpad">广告位的高度值，整数，不限高度请填[0]</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">开始时间</td>
		<td><input type="text" name="start_time" id="st" class="txt" size="20"  />
			<span class="lpad">时间格式如：[2014-05-01 00:00]，没有时间限制请留空</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">结束时间</td>
		<td><input type="text" name="end_time" id="et" class="txt" size="20" />
			<span class="lpad">时间格式如：[2014-06-01 00:00]，没有时间限制请留空</span>
		</td>
	  </tr>
	  
	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();
});
</script>
<{include file="common/footer.tpl"}>