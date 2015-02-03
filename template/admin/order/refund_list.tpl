<{include file="common/header.tpl"}>
<link type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datetimepicker/jquery.datetimepicker.js"></script>

<div class="admin-wrap">
	<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
        <span class="lpad">订单号：</span>
        <input type="text" name="order_sn" size="18" class="txt" value="<{$smarty.get.order_sn}>" />
        <span class="lpad">用户名：</span>
        <input type="text" name="uname" size="20" class="txt" value="<{$smarty.get.uname}>" />
        <span class="lpad">审核状态：</span>
        <select name="refund_status">
        <option value="0">所有</option>
        <option value="1" <{if $smarty.get.refund_status eq '1'}>selected="selected"<{/if}>>申请中</option>
        <option value="2" <{if $smarty.get.refund_status eq '2'}>selected="selected"<{/if}>>已通过</option>
        <option value="3" <{if $smarty.get.refund_status eq '3'}>selected="selected"<{/if}>>已拒绝</option>
        </select>
        <span class="lpad">申请时间：</span>
		<input type="text" name="start_time" class="txt" size="16" id="st" value="<{$smarty.get.start_time}>" />
		<span class="lpad rpad">~</span>
		<input type="text" name="end_time" class="txt" size="16" id="et" value="<{$smarty.get.end_time}>" />
        
		<a href="javascript:;" id="btn-submit" class="btn2">查询</a>
		</form>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>	
		<th>订单号</th>
        <th>条形码</th>
        <th>用户名</th>
        <th>商品名称</th>
        <th>编号</th>
		<th>属性</th>
		<th>单价</th>
        <th>退货类型</th>
		<th>申请时间</th>
		<th>审核状态</th>
        <th>资金状态</th>
        <th>韩方同意</th>
		<th>操作</th>
	  </tr>
	  <{if $refund_items}>
	  <{foreach $refund_items as $item}>
	  <tr>
		<td><{$item.order_sn}></td>
        <td><{$item.barcode}></td>
		<td><{$item.uname}></td>
        <td><{$item.product_name}></td>
        <td><{$item.product_sn}></td>
        <td><{if $item.prop_value}>
        		<{foreach $item.prop_value as $value}><{$value}>; <{/foreach}>
            <{/if}>
        </td>
        <td>￥<{$item.price}></td>
        <td><{if $item.refund_type eq 0}><span class="red">质量问题</span><{elseif $item.return_status eq 1}><span class="orange">积分无理由退</span><{/if}></td>
        <td><{$item.refund_time|date_format:'Y-m-d H:i'}></td>
		<td><{if $item.refund_status eq 1}>
        		<span class="orange">申请中</span>
           	<{elseif $item.refund_status eq 2}>
            	<span class="green">已通过</span>
            <{elseif $item.refund_status eq 3}>
            	<span class="red">已拒绝</span>
           	<{/if}>
        </td>
        <td><{if $item.return_status eq 0}>
            <{elseif $item.return_status eq 1}>
            	<span class="green">已返还</span>
            <{elseif $item.return_status eq 2}>
            	<span class="orange">不返还</span>
            <{/if}>
        </td>
        <td>
            <{if $item.kr_agree eq 1}>
                <span class="green">是</span>
            <{elseif $item.kr_agree eq 2}>
                <span class="red">否</span>
            <{/if}>
            
        </td>
		<td>
			<a href="/<{$mod}>/<{$col}>/detail/<{$item.id}>">查看</a>
            <{if $item.refund_status eq 2 and $item.return_status eq 0}>
                <span class="dl">|</span>
                <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/return_money/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="return_money">返还金额</a>
                <span class="dl">|</span>
                <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/return_no_money/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="return_no_money">不返还金额</a>
            <{/if}>
            <{if $item.refund_status eq 2 and $item.kr_agree eq 0}>
            	<span class="dl">|</span>
            	<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/agree/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-agree">韩同意</a>
                <span class="dl">|</span>
                <a href="javascript:;" data-href="/<{$mod}>/<{$col}>/disagree/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-disagree">韩不同意</a>
            <{/if}>
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
	
    $('.return_money').click(function(){
    	var href = $(this).attr('data-href');
        popup.confirm('确定返还金额吗？', this, function(){
        	location.href = href;
       	});
    });
    
    $('.return_no_money').click(function(){
    	var href = $(this).attr('data-href');
        popup.confirm('确定不返还金额吗？', this, function(){
        	location.href = href;
       	});
    });
    
    $('.btn-agree').click(function(){
    	var href = $(this).attr('data-href');
        popup.confirm('确定设置韩方同意退货吗？', this, function(){
        	location.href = href;
       	});
    });
    
    $('.btn-disagree').click(function(){
    	var href = $(this).attr('data-href');
        popup.confirm('确定设置韩方不同意退货吗？', this, function(){
        	location.href = href;
       	});
    });
});
</script>
<{include file="common/footer.tpl"}>