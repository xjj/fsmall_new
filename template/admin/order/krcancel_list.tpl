<{include file="common/header.tpl"}>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>

<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
			<span class="lpad">订单号：</span>
			<input type="text" name="order_sn" size="18" class="txt" value="<{$smarty.get.order_sn}>" />
            
             <select name="brand_id">
            <option value="0">选择品牌</option>
            <{if $brands}>
        	<{foreach $brands as $item}>
			<option value="<{$item.brand_id}>"<{if $smarty.get.brand_id eq $item.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
            <{/foreach}>
            <{/if}>
			</select>
            
       
			<a href="javascript:;" id="btn-submit" class="btn2">查询</a>
		</form>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>订单号</th>
        <th >条码</th>
	 <th>品牌</th>

        <th>商品名称</th>
		<th>断货时间</th>
        <th>资金状态</th>
		<th>操作</th>
        
	  </tr>
      	  <{if $total eq 0}>
	  <tr class="nohover">
	  	<td colspan="14">暂无记录！</td>
	  </tr>
		<{/if}>
	  <{if $items}>
	  <{foreach $items as $item}>
	  <tr>
		<td><{$item.order_sn}></td>
        <td><{$item.barcode}></td>
         <td><{$item.brand_name}></td>
        <td><{$item.product_name}></td>
        

        <td><{$item.kcancle_time|date_format:'Y-m-d H:i:s'}></td>


        <td><{if $item.return_status eq 0}><span class="orange">未返还</span><{elseif $item.return_status eq 1}><span class="green">已返还</span><{/if}>
        </td>
		<td>
			<a href="/<{$mod}>/<{$col}>/return_money/<{$item.id}>">返还金额</a>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	$('#st').datetimepicker();
	$('#et').datetimepicker();

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