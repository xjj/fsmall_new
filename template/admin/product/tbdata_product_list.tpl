<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="searchForm" method="get" action="/<{$mod}>/<{$col}>">
		
		<select name="brand_id">
		<option value="0">--品牌--</option>
		<{if $brand_items}>
		<{foreach $brand_items as $item}>
		<option value="<{$item.brand_id}>" <{if $smarty.get.brand_id eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
		<{/foreach}>
		<{/if}>
		</select>
		
		<span class="lpad">商品ID：</span>
		<input type="text" name="id" class="txt" size="10" value="<{$smarty.get.id}>" id="form-id" />
		<span class="lpad">关键词：</span>
		<input type="text" name="k" class="txt" size="20" value="<{$smarty.get.k}>" id="form-k" />
		<span class="lpad">更新时间：</span>
		<input type="text" name="st" class="txt" size="18" id="st" value="<{$smarty.get.st}>" />
		<span class="lpad rpad">~</span>
		<input type="text" name="et" class="txt" size="18" id="et" value="<{$smarty.get.et}>" />
		<a href="javascript:;" class="btn" id="btn-search">查询</a>
		</form>
	</div>
	<form name="f2" id="listForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th width="13"><input type="checkbox" name="chkall" value="1" id="chkall1" /></th>
		<th>商品图片</th>
		<th>ID</th>
		<th>商品名称</th>
		<th>品牌名称</th>
		<th>韩网编号</th>
		<th>商品更新时间</th>
        <th>数据包制作时间</th>
		<th width="14%">操作</th>
	  </tr>
	  <{if $product_items}>
	  <{foreach $product_items as $item}>
	  <tr>
	  	<td><input type="checkbox" name="prd_id[]" value="<{$item.prd_id}>" /></td>
		<td><a href="http://www.fs-mall.com/product/<{$item.prd_id}>" target="_blank" class="bold"><img src="<{$item.pic_small}>" width="60" height="60" /></a></td>
		<td><{$item.prd_id}></td>
		<td><a href="http://www.fs-mall.com/product/<{$item.prd_id}>" target="_blank" class="bold"><{$item.product_name}></a>
			<a href="<{$item.kr_url}>" class="link" target="_blank" title="韩网地址">韩网地址</a>
		</td>
		<td><{$item.brand_name}></td>
		<td><{$item.product_sn}></td>
		<td><{$item.add_time|date_format:"Y-m-d H:i"}></td>
        <td><{$item.add_time|date_format:"Y-m-d H:i"}></td>
		<td>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/dotbdata/<{$item.prd_id}>" class="btn-dopack">制作数据包</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall" value="1" id="chkall2" /></td>
	  	<td colspan="15">
			<a href="javascript:;" id="btn_dotbdata_sel" class="btn2 btn3">全部选择项制作数据包</a>
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
	$('#st').datetimepicker();
	$('#et').datetimepicker();
	
	//提交表单操作
	var btn = $('#btn-search');
	var searchForm = $('#searchForm');
	btn.click(function(){
		searchForm.submit();
	});
	//回车提交表单操作
	searchForm.keyup(function(event){
		if (event.keyCode == 13){btn.trigger('click');}
	});
	
	//单个做数据包
	$('.btn-dopack').click(function(){
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
	
	//批量制作数据包
	$('#btn_dotbdata_sel').click(function(){
		var checkboxs = $('input[name^=prd_id]:checked', listForm);
		if (checkboxs.length == 0){
			return false;
		} else {
			var data = listForm.serialize();
			var url  = '/<{$mod}>/<{$col}>/dotbdata_sel?<{$params}>'+data;
			//alert(url);
			location.href=url;
			
				/*$.post(url, data, function(dt){
					alert(dt);
					//location.reload();
				});*/
		}
	});
});
</script>
<{include file="common/footer.tpl"}>