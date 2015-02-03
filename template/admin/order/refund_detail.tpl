<{include file="common/header.tpl"}>

<div class="admin-wrap">
    <div class="col-box clearfix">
    <a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到退货列表</a>
    </div>
    <hr class="line" />
    <form name="f" method="post" id="refundForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
        <td class="ltd" width="80">商品名称</td>  
        <td><{$refund_info.product_name}></td>
      </tr>
      <tr>
        <td class="ltd" width="80">商品编号</td>  
        <td><{$refund_info.product_sn}></td>
      </tr>
      <tr>
        <td class="ltd" width="80">退换类型</td>  
        <td><{if $refund_info.refund_type eq 0}><span class="red">质量问题</span><{else}><span class="red">积分无理由退货</span><{/if}></td>
      </tr>
      <tr>
        <td class="ltd" width="80">商品价格</td>  
        <td>￥<{$refund_info.price}></td>
      </tr>
      <tr>
        <td class="ltd" width="80">申请退货时间</td>  
        <td><{$refund_info.refund_time|date_format:'Y-m-d H:i'}></td>
      </tr>
      <tr>
        <td class="ltd" width="80">退换原因</td>  
        <td><{$refund_info.reason}>
        	<{if $refund_info.pics}>
                <div style=" padding-top:15px">
                <{foreach $refund_info.pics as $pic}>
                    <img style="max-width:600px;height:auto;" src="<{$pic}>" />
                <{/foreach}>
                </div>
        	<{/if}>
        </td>
      </tr>
      <tr>
       <td class="ltd" width="80">回复</td>
       <td>
       		<{if $refund_info.refund_status eq 1}>
       			<textarea name="reply" rows="4" cols="100" class="txt"></textarea>
       	   	<{else}>
            	<{$refund_info.replay}>
            <{/if}>
       </td>
      </tr>
      <tr>
        <td class="ltd" width="80">审核</td>
        <td>
        	<{if $refund_info.refund_status eq 1}>
                <a href="javascript:;" data-action="/<{$mod}>/<{$col}>/allow/<{$refund_info.id}>" class="btn-submit btn2">同意</a>
                <span class="dl">|</span>
                <a href="javascript:;" data-action="/<{$mod}>/<{$col}>/deny/<{$refund_info.id}>" class="btn-submit btn2">拒绝</a>
            <{elseif $refund_info.refund_status eq 2}>
            	<span class="green">已通过</span>
            <{elseif $refund_info.refund_status eq 3}>
            	<span class="red">已拒绝</span>
            <{/if}>
		  </td>
       </tr>
	</table>
    </form>
</div>
<script type="text/javascript">
$(function(){
    var form = $('#refundForm');
    $('.btn-submit').click(function(){
    	var action = $(this).attr('data-action');
        form.attr('action', action);
        form.submit();
    });
});
</script>
<{include file="common/footer.tpl"}>