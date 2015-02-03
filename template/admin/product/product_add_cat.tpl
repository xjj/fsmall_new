<{include file="common/header.tpl"}>
<style type="text/css">
.cat_list{ width:180px; float:left; margin-right:20px;}
.cat_list a{ display:block; line-height:35px; padding:0 12px; border:1px solid #eee; margin-bottom:-1px; background:#f9f9f9}
.cat_list a.sel,
.cat_list a:hover{ background:url(/images/right_arrow.png) 160px 14px #FFF no-repeat;}
.cat_head{ line-height:35px; border:1px solid #eee; background:url(/images/line-bg.png); padding:0 12px; margin-bottom:10px;}
</style>
<div class="admin-wrap">
	<div class="alert-box">
		请先选择商品的类目，以便调取淘宝属性信息及销售属性信息！
	</div>
	<div class="col-box clearfix">
		<div class="cat_list">
			<div class="cat_head">一级类目</div>
			<div id="cat1">
			<{if $category_items}>
				<{foreach $category_items as $item}>
					<a href="javascript:;" data-cat-id="<{$item.cat_id}>"><{$item.cat_name}></a>
				<{/foreach}>
			<{/if}>
			</div>
		</div>
		<div class="cat_list">
			<div class="cat_head">二级类目</div>
			<div id="cat2"></div>
		</div>
		<div class="cat_list">
			<div class="cat_head">三级类目</div>
			<div id="cat3"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/category.js"></script>
<script type="text/javascript">
$(function(){
	var cat1 = $('#cat1');
	var cat2 = $('#cat2');
	var cat3 = $('#cat3');
	
	$('a', cat1).click(function(){
		$(this).siblings().removeClass('sel');
		$(this).addClass('sel');
		
		var cat_id = $(this).attr('data-cat-id');
		cat2.empty();
		cat3.empty();
		category.children({'cat_id' : cat_id}, function(data){
			var box2 = '';
			for (var i = 0; i < data.length; i++){
				box2 += '<a href="javascript:;" data-cat-id="'+ data[i].cat_id +'">'+ data[i].cat_name +'</a>';
			}
			cat2.append(box2);
			
			$('a', cat2).click(function(){
				$(this).siblings().removeClass('sel');
				$(this).addClass('sel');
				var cat_id = $(this).attr('data-cat-id');
				cat3.empty();
				category.children({'cat_id' : cat_id}, function(data){
					var box3 = '';
					for (var i = 0; i < data.length; i++){
						box3 += '<a href="/<{$mod}>/<{$col}>/'+ data[i].cat_id +'">'+ data[i].cat_name +'</a>';
					}
					cat3.append(box3);
				});
			});
		});
	});
});
</script>
<{include file="common/footer.tpl"}>