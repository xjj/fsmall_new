<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<!--xjj   未确认的显示上面 已确认的显示下面 -->
	<form name="f2" id="listForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
    
	  <tr>
	  	<th width="13"><input type="checkbox" name="chkall" value="1" id="chkall1" /></th>
		<th>未确认的商品图片</th>
		<th width="14%">操作</th>
	  </tr>
      <{if $items_unconfirm}>
	  <{foreach $items_unconfirm as $item}>
	  <tr>
	  	<td><input type="checkbox" name="pic_id[]" value="<{$item.pic_id}>" /></td>
		<td><a href="<{$item.pic_fs}>" title="点击放大" target="_blank"><img src="<{$item.pic_fs}>" style="width:1000px;height:auto;" /></a></td>
		<td>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/pic_confirm/<{$item.pic_id}>" class="btn-act" style="color:#F63">确认</a>
            <span class="dl">|</span>
            <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/pic_del/<{$item.pic_id}>" class="btn-act">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall" value="1" id="chkall2" /></td>
	  	<td colspan="15">
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/pic_confirm_multi?<{$params}>" id="btn-act_sel" class="btn2 btn3">确认全部选择项</a>
            <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/pic_del_multi?<{$params}>" id="btn-act_sel" class="btn2 btn3">删除全部选择项</a>
		</td>
	  </tr>
	  <{else}>
	  <tr>
	  	<td colspan="16">暂无未确认的图片！</td>
	  </tr>
	  <{/if}>
      </table>
     </form>
      <!--已确认 的 ----------------- -->
      <form name="f3" id="listForm2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
      <tr>
	  	<th width="13"><input type="checkbox" name="chkall" value="1" id="chkall3" /></th>
		<th>已确认商品图片</th>
		<th width="14%">操作</th>
	  </tr>
	  <{if $items}>
	  <{foreach $items as $item}>
	  <tr>
	  	<td><input type="checkbox" name="pic_id[]" value="<{$item.pic_id}>" /></td>
		<td><img src="<{$item.pic_fs}>" width="1000" style="height:auto" /></td>
		<td>
			<a href="/<{$mod}>/<{$col}>/pic_local_del/<{$item.pic_id}>" class="btn-act">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall" value="1" id="chkall4" /></td>
	  	<td colspan="15">
        	<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/pic_local_del_multi?<{$params}>" id="btn-act_sel2" class="btn2 btn3">删除全部选择项</a>
			
		</td>
	  </tr>
	  <{else}>
	  <tr>
	  	<td colspan="16">暂无已确认的图片！</td>
	  </tr>
	  <{/if}>
      
	</table>
	</form>
</div>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(function(){
	//按钮操作
	$('.btn-act').click(function(){
		var url = $(this).attr('data-href');
		location.href=url;
	});
	var listForm = $('#listForm');
	var listForm2 = $('#listForm2');
	//全选按钮
	$('#chkall1').click(function(){
		chkall(this);
	});
	$('#chkall2').click(function(){
		chkall(this);
	});
	$('#chkall3').click(function(){
		chkall2(this);
	});
	$('#chkall4').click(function(){
		chkall2(this);
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
	function chkall2(em){
		var checkboxs = $('input[type=checkbox]', listForm2);
		var f = $(em).prop('checked');
		if (f){
			checkboxs.prop('checked', true);
		} else {
			checkboxs.prop('checked', false);
		}
	}
	//操作被选择项
	$('#btn-act_sel').click(function(){
		var checkboxs = $('input[name^=pic_id]:checked', listForm);
		if (checkboxs.length == 0){
			alert('请选择要操作的记录');
			return false;
		} else {
			var data = listForm.serialize();
			var url = $(this).attr('data-href');
			//location.href=url+data; return false;
			popup.confirm('确定此操作吗？', this, function(){
				$.post(url, data, function(dt){
					var data= json(dt);
					alert(data.msg);
					location.reload();
				});
			});
		}
	});
	$('#btn-act_sel2').click(function(){
		var checkboxs = $('input[name^=pic_id]:checked', listForm2);
		if (checkboxs.length == 0){
			alert('请选择要操作的记录');
			return false;
		} else {
			var data = listForm2.serialize();
			var url = $(this).attr('data-href');
			//location.href=url+data; return false;
			popup.confirm('确定此操作吗？', this, function(){
				$.post(url, data, function(dt){
					var data= json(dt);
					alert(data.msg);
					location.reload();
				});
			});
		}
	});
});
</script>
<{include file="common/footer.tpl"}>