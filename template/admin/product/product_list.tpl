<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="searchForm" method="get" action="/<{$mod}>/<{$col}>">
		<select name="cat_id">
		<option value="0">--商品类目--</option>
		<{if $cat_items}>
		<{foreach $cat_items as $item}>
			<option value="<{$item.cat_id}>" <{if $smarty.get.cat_id eq $item.cat_id}>selected="selected"<{/if}>><{$item.cat_name}></option>
			<{if $item.items}>
			<{foreach $item.items as $item2}>
				<option value="<{$item2.cat_id}>" <{if $smarty.get.cat_id eq $item2.cat_id}>selected="selected"<{/if}>>
					&nbsp;&nbsp;<{if $item2@last}>└─<{else}>├─<{/if}> <{$item2.cat_name}>
				</option>
				<{if $item2.items}>
				<{foreach $item2.items as $item3}>
					<option value="<{$item3.cat_id}>" <{if $smarty.get.cat_id eq $item3.cat_id}>selected="selected"<{/if}>>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<{if $item3@last}>└─<{else}>├─<{/if}> <{$item3.cat_name}>
					</option>
				<{/foreach}>
				<{/if}>
			<{/foreach}>
			<{/if}>
		<{/foreach}>
		<{/if}>
		</select>
		<select name="brand_id">
		<option value="0">--品牌--</option>
		<{if $brand_items}>
		<{foreach $brand_items as $item}>
		<option value="<{$item.brand_id}>" <{if $smarty.get.bid eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
		<{/foreach}>
		<{/if}>
		</select>
		<select name="status">
		<option value="">--状态--</option>
		<option value="best" <{if $smarty.get.status eq 'best'}>selected="selected"<{/if}>>热销</option>
		<option value="hot"  <{if $smarty.get.status eq 'hot' }>selected="selected"<{/if}>>热门</option>
		<option value="spot" <{if $smarty.get.status eq 'spot'}>selected="selected"<{/if}>>现货</option>
		<option value="sale" <{if $smarty.get.status eq 'sale'}>selected="selected"<{/if}>>上架</option>
		<option value="soldout" <{if $smarty.get.status eq 'soldout'}>selected="selected"<{/if}>>断货</option>
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
		<th>韩网价格</th>
		<th>国内价格</th>
		<th>国际运费</th>
		<th>热门</th>
		<th>热销</th>
		<th>现货</th>
		<th>上架</th>
		<th>断货</th>
		<th>更新时间</th>
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
		<td>₩<{$item.price_kr}></td>
		<td>￥<{$item.price}></td>
		<td>￥<{$item.freight * $item.is_freight}></td>
		<td><{if $item.is_hot eq 1}><span class="green">是</span><{else}><span class="gray">否</span><{/if}></td>
		<td><{if $item.is_best eq 1}><span class="green">是</span><{else}><span class="gray">否</span><{/if}></td>
		<td><{if $item.is_spot eq 1}><span class="green">是</span><{else}><span class="gray">否</span><{/if}></td>
		<td><{if $item.is_on_sale eq 1}>
				<a class="green" href="/<{$mod}>/<{$col}>/sale_cancle/<{$item.prd_id}>?<{$params}>">是</a>
			<{else}>
				<a class="red" href="/<{$mod}>/<{$col}>/sale/<{$item.prd_id}>?<{$params}>">否</a>
			<{/if}>
		</td>
		<td><{if $item.is_soldout eq 1}>
				<a href="javascript:;" class="red btn-soldout-cancle" data-href="/<{$mod}>/<{$col}>/soldout_cancle/<{$item.prd_id}>?<{$params}>">是</a>
			<{else}>
				<a href="javascript:;" class="green btn-soldout" data-href="/<{$mod}>/<{$col}>/soldout/<{$item.prd_id}>?<{$params}>">否</a>
			<{/if}>
		</td>
		<td><{$item.add_time|date_format:"Y-m-d H:i"}></td>
		<td>
        	<a href="http://admin.fs-mall.com/urld/" target="_blank">抓图</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/pics/<{$item.prd_id}>">图片</a>
			<span class="dl">|</span>
			<a href="/<{$mod}>/<{$col}>/edit/<{$item.prd_id}>">编辑</a>
			<span class="dl">|</span>
			<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$item.prd_id}>" class="btn-del">删除</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <tr class="nohover">
	  	<td><input type="checkbox" name="chkall" value="1" id="chkall2" /></td>
	  	<td colspan="15">
			<a href="javascript:;" id="btn-delete" class="btn2 btn3">删除全部选择项</a>
			<a href="javascript:;" id="btn-sale-cancle" class="btn2 btn3">下架全部选择项</a>
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
	
	//删除商品
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定要删除该商品吗？<br />删除后可在回收站找回', this, function(){
			location.href=url;
		});
	});
	
	//商品断货
	$('.btn-soldout').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定设置该商品断货吗？', this, function(){
			location.href=url;
		});
	});
	
	//商品断货取消
	$('.btn-soldout-cancle').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定取消该商品断货吗？', this, function(){
			location.href=url;
		});
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
	
	//删除被选择项
	$('#btn-delete').click(function(){
		var checkboxs = $('input[name^=prd_id]:checked', listForm);
		if (checkboxs.length == 0){
			return false;
		} else {
			var data = listForm.serialize();
			var url  = '/<{$mod}>/<{$col}>/del_multi?<{$params}>';
			popup.confirm('确定要删除选中的商品吗？', this, function(){
				$.post(url, data, function(dt){
					location.reload();
				});
			});
		}
	});
	
	//下架被选择项
	$('#btn-sale-cancle').click(function(){
		var checkboxs = $('input[name^=prd_id]:checked', listForm);
		if (checkboxs.length == 0){
			return false;
		} else {
			var data = listForm.serialize();
			var url  = '/<{$mod}>/<{$col}>/sale_cancle_multi?<{$params}>';
			popup.confirm('确定要下架选中的商品吗？', this, function(){
				$.post(url, data, function(dt){
					location.reload();
				});
			});
		}
	});
});
</script>
<{include file="common/footer.tpl"}>