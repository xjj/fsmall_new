<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
		<div class="z">
		</div>
		<div class="y"></div>
	</div>
    	<form name="form" method="post" action="/<{$mod}>/<{$col}>/edit">

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>配置项</th>
		<th>值</th>
	  </tr>
	  <{if $setting_items}>
	  <{foreach $setting_items as $item}>
	  <tr>
		<td><{$item.title}></td>
		<td><input type="text" value="<{$item.value}>" name="<{$item.key}>" class="txt" size="40">
        </td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
      	  <tr>
		<td class="ltd"></td>
		<td><input type="submit" value="提交" class="btn2" name="submit" />
		</td>
	  </tr>
	</table>
    </form>
	<{$pagebox}>
</div>
<{include file="common/footer.tpl"}>