<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
    	<div class="y">
        	<a href="/<{$mod}>/<{$col}>/brand/add" class="btn btn3">添加品牌折扣</a>
        </div>
        <div class="z">
        	<a href="/<{$mod}>/<{$col}>/brand" class="btn btn3">品牌折扣列表</a>
        	<a href="/<{$mod}>/<{$col}>/product" class="btn btn3">商品折扣列表</a>
        </div>
	</div>
	<div class="alert-box">
    	品牌折扣：供应商给我们的商品折扣，以最近的一条记录为准。韩国店铺发货时，会记录商品优惠价格及优惠的折扣，品牌的折扣是每一个都要设置的，切勿忘记！！
    </div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl">
      <tr>
      	<th>编号</th>
        <th>品牌</th>
        <th>BRAND_ID</th>
        <th>折扣</th>
        <th>添加时间</th>
        <th>有效</th>
        <th width="200">操作</th>
      </tr>
      <{if $discount_items}>
      	<{foreach $discount_items as $item}>
      	<tr>
        	<td><{$item.id}></td>
            <td><{$item.brand_name}></td>
            <td><{$item.brand_id}></td>
            <td><{$item.discount}>%</td>
            <td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
            <td><{if $item.status eq 1}>
            		<a href="/<{$mod}>/<{$col}>/brand/hide/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="green">是</a>
                <{else}>
                	<a href="/<{$mod}>/<{$col}>/brand/show/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="red">否</a>
                <{/if}>
            </td>
            <td><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/brand/del/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-del">删除</a></td>
        </tr>
        <{/foreach}>
      <{/if}>
    </table>
    <{$pagebox}>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-del').click(function(){
    	var href = $(this).attr('data-href');
        popup.confirm('确定删除该品折扣信息吗？<br />删除前请确保该品牌还有其他折扣信息！<br />否则程序无法计算商品价格！', this, function(){
        	location.href = href;
        });
    });
});
</script>
<{include file="common/footer.tpl"}>
