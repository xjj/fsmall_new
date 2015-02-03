<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
    	<div class="y">
        	<a href="/<{$mod}>/<{$col}>/product/add" class="btn btn3">添加商品折扣</a>
        	<a href="/<{$mod}>/<{$col}>/product/clear" class="btn btn3">清理断货的商品的折扣</a>
        </div>
        <div class="z">
        	<a href="/<{$mod}>/<{$col}>/brand" class="btn btn3">品牌折扣列表</a>
        	<a href="/<{$mod}>/<{$col}>/product" class="btn btn3">商品折扣列表</a>
        </div>
	</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tbl">
      <tr>
        <th width="60">商品图片</th>
        <th>商品名称</th>
        <th>韩网编号</th>
        <th>折扣</th>
        <th>添加时间</th>
        <th>有效</th>
        <th width="200">操作</th>
      </tr>
      <{if $discount_items}>
      	<{foreach $discount_items as $item}>
      	<tr>
            <td><img src="<{$item.pic_small}>" width="60" height="60" style=" vertical-align:top" /></td>
            <td><{$item.product_name}></td>
            <td><{$item.product_sn}></td>
            <td><{$item.discount}>%</td>
            <td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
            <td><{if $item.status eq 1}>
            		<a href="/<{$mod}>/<{$col}>/product/hide/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="green">是</a>
                <{else}>
                	<a href="/<{$mod}>/<{$col}>/product/show/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="red">否</a>
                <{/if}>
            </td>
            <td><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/product/del/<{$item.id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-del">删除</a></td>
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
        popup.confirm('确定删除该折扣信息吗？', this, function(){
        	location.href = href;
        });
    });
});
</script>
<{include file="common/footer.tpl"}>
