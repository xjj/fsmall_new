<{include file="common/header.tpl"}>
<style type="text/css">
.disc-box{ float:left; border:1px solid #eee; border-right:0; border-bottom:0; position:relative;}
.disc-box li{ float:left; padding:10px 1.5em; border:1px solid #eee; border-top:0; border-left:0; position:relative;}
.disc-box li a{ display:block; position:absolute; top:0px; right:0px; width:16px; height:16px; border-radius:0 0 0 100%; background:#eee; color:#FFF; line-height:12px; text-indent:5px;}
.disc-box li a:hover{ background:#ff6600}
</style>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到会员等级列表</a>
	</div>
	<div class="alert-box">
		没有设置品牌折扣，则该品牌的折扣与会员折扣相同（<{$grade_data.grade_name}>：<{$grade_data.discount}>%），如果设置了，则以设置的值为准。
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
	  	<th><{$grade_data.grade_name}>（<{$grade_data.discount}>%） -> 品牌折扣信息</th>
	  </tr>
	  <tr>
	  	<td>
			<form name="f" method="post" action="/<{$mod}>/<{$col}>/brand/<{$grade_data.grade_id}>/add">
			<input type="hidden" name="grade_id" value="<{$grade_data.grade_id}>" />
			品牌名称：<input type="text" name="brand_name" class="txt" />
			<span class="lpad">折扣值：</span>
			<input type="text" name="discount" class="txt" size="10" />
			<span class="dl"></span>
			<input type="submit" name="submit" value="添加" class="btn2" />
			</form>
		</td>
	  </tr>
	</table>
	<{if $discount_items}>
		<ul class="disc-box clearfix">
		<{foreach $discount_items as $item}>
			<li><a href="javascript:;" data-href="/<{$mod}>/<{$col}>/brand/<{$item.grade_id}>/del/<{$item.brand_id}>" class="btn-del" title="删除">×</a><{$item.brand_name|capitalize}> [<{$item.discount}>%]</li>
		<{/foreach}>
		</ul>
	<{/if}>
</div>
<script type="text/javascript">
$(function(){
	//删除
	$('.btn-del').click(function(){
		var url = $(this).attr('data-href');
		popup.confirm('确定删除该品牌折扣信息吗？', this, function(){
			location.href=url;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>