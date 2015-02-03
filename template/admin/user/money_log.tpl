<{include file="common/header.tpl"}>
	<div class="admin-wrap">
		<div class="col-box">
		<form name="f" id="search-form" method="get" action="/<{$mod}>/<{$col}>">
			<span class="lpad">用户名：</span>
			<input type="text" name="uname" size="20" class="txt" value="<{$smarty.get.uname}>" />
			<a href="javascript:;" id="btn-submit" class="btn">查询</a>
		</form>
		</div>

	<table width="50%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
		<th>编号</th>
		<th>用户名</th>
		<th>操作类型</th>
		<th>金额</th>
        <th>原因</th>
        <th>订单号</th>
        <th>条形码</th>
        <th>操作时间</th>
	  </tr>
	  <{if $log_items}>
	  <{foreach $log_items as $item}>
	  <tr>
		<td><{$item.id}></td>
		<td><{$item.uname}></td>
		<td><{if $item.act_type eq 0}><span class="green">增加</span><{else}><span class="red">减少</span><{/if}></td>
		<td><{$item.money}></td>
		<td><{$item.content}></td>
        <td><{if $item.order_sn gt 0}><{$item.order_sn}><{/if}></td>
        <td><{if $item.barcode gt 0}><{$item.barcode}><{/if}></td>
		<td><{$item.add_time|date_format:'Y-m-d H:i'}></td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	<{$pagebox}>
</div>
<script type="text/javascript">
function editscore(){
	var uid= $('#uid').val();
	var edittype= $('#edittype').val();
	var score= $('#score').val();
	var adm_uid= $('#adm_uid').val();
	var url= $('#url').val();
	$.ajax({
		url:url,
		dataType:"json",
		type:"post", 
		data:{ uid:uid,edittype:edittype,score:score,adm_uid:adm_uid,act:'act' },
		success:function(result)
		{	
			if(result.code==0){//
				alert('操作成功');
				//alert(url);
				location.href= '/user/score';//"../../admin/logistics/sendout"
			}
			if(result.code==1){//
				alert('操作失败');
			}
			if(result.code==2){//
				alert('输入值为0无需更改');
			}
			if(result.code==3){//
				alert('输入值不能大于用户现有积分!');
			}
		},error:function(x,e){
			alert('操作请求出错 请联系技术支持');//有可能出错了
		}
	});	
	
}
$(function(){
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