<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到资金日志列表</a>
	</div>
    
    <hr class="line" />
    <form name="f" method="post" action="/<{$mod}>/<{$col}>/add">
    <input type="hidden" name="uid" value="<{$uid}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="80">用户名</td>
        <td><input type="text" name="uname" disabled="disabled" readonly="readonly" class="txt txt2" value="<{$uname}>" /></td>
      </tr>
      <tr>
		<td class="ltd">操作类型</td>
        <td><select name="act_type">
			<option value="add">加账户余额</option>
			<option value="minus" >减账户余额</option>
            </select>
        </td>
      </tr>
      <tr>
		<td class="ltd">金额</td>
        <td><input type="text" name="money" class="txt" /></td>
      </tr>
      <tr>
        <td class="ltd">操作原因</td>
        <td><textarea name="reason" cols="100" rows="4" class="txt"></textarea></td>
	  </tr>
	  <tr>
        <td class="ltd"></td>
		<td><input type="submit" name="submit" value="提交" class="btn" /> </td>
	  </tr>
	</table>
    </form>
</div>
<{include file="common/footer.tpl"}>