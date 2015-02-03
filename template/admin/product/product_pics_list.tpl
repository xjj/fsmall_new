<{include file="common/header.tpl"}>
<div class="admin-wrap">
	
	<form name="f2" id="listForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th width="13"><input type="checkbox" name="chkall" value="1" id="chkall1" /></th>
		<th>商品图片</th>

		<th width="14%">操作</th>
	  </tr>
	  <{if $items}>
	  <{foreach $items as $item}>
	  <tr>
	  	<td><input type="checkbox" name="pic_id[]" value="<{$item.pic_id}>" /></td>
		<td><img src="<{$item.pic_tb}>" width="200" height="200" /></td>

		<td>

			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/confirm/<{$item.pic_id}>" class="btn-confirm">确认</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall" value="1" id="chkall2" /></td>
	  	<td colspan="15">
			<a href="javascript:;" id="btn-confirmsel" class="btn2 btn3">确认全部选择项</a>
		</td>
	  </tr>
	  <{else}>
	  <tr>
	  	<td colspan="16">没有查询到商品信息！</td>
	  </tr>
	  <{/if}>
	</table>
	</form>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	//确认
	$('.btn-confirm').click(function(){
		var url = $(this).attr('data-href');
		location.href=url;
	});
	var listForm = $('#listForm');
	
	//全选按钮
	$('#chkall1').click(function(){
		chkall(this);
	});
	$('#chkall2').click(function(){
		chkall(this);
	});

	function chkall(em){
		var checkboxs = $('input[type=checkbox]', listForm);
		var f = $(em).prop('checked');
		if (f){
			checkboxs.prop('checked', true);
		} else {
			checkboxs.prop('checked', false);
		}
	}
	
	//确认被选择项
	$('#btn-confirmsel').click(function(){
		var checkboxs = $('input[name^=prd_id]:checked', listForm);
		if (checkboxs.length == 0){
			return false;
		} else {
			var data = listForm.serialize();
			var url  = '/<{$mod}>/<{$col}>/confirmsel?<{$params}>';
			popup.confirm('确定此操作吗？', this, function(){
				$.post(url, data, function(dt){
					location.reload();
				});
			});
		}
	});
});
</script>
<{include file="common/footer.tpl"}>