<{include file="common/header.tpl"}>
<style type="text/css">
.tb-attr{}
.tb-attr li{ float:left; width:25%; padding:5px 0;}
.tb-attr-checkbox li{ width:10%;}
.prd-style li{ float:left; width:10%; padding:5px 0;}

.tbl-prop{ margin-bottom:10px; border:1px solid #eee}
.tbl-prop:last-child{ margin-bottom:0;}
.tbl-tbattr:last-child{ margin-bottom:0}
</style>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到商品列表页</a>
	</div>
	<hr class="line" />
	<form name="f" method="post" action="/<{$mod}>/<{$col}>/edit/<{$prd_data.prd_id}>">
	<input type="hidden" name="cat_id" value="<{$prd_data.cat_id}>" />
	<input type="hidden" name="prd_id" value="<{$prd_data.prd_id}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">类目</td>
		<td><{$cat_data.cat_path}></td>
	  </tr>
	  <tr>
		<td class="ltd">名称（韩）</td>
		<td><input type="text" name="product_name_kr" class="txt" size="80" value="<{$prd_data.product_name_kr}>" />
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">名称（中）</td>
		<td><input type="text" name="product_name" class="txt" size="80" value="<{$prd_data.product_name}>" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">不能含有韩文字符，支持英文，数字，简体中文</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">品牌</td>
		<td><select name="brand_id">
			<option value="0">-选择品牌-</option>
			<{if $brand_items}>
			<{foreach $brand_items as $item}>
			<option value="<{$item.brand_id}>" <{if $item.brand_id eq $prd_data.brand_id}>selected="selected"<{/if}>><{$item.brand_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩网编号</td>
		<td><input type="text" name="product_sn" class="txt" size="20" value="<{$prd_data.product_sn}>" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">唯一，韩网商品的编号，品牌名不必填写</span>
	  </tr>
	  <tr>
		<td class="ltd">商品主图</td>
		<td><input type="text" name="pic" class="txt txt2" size="60" readonly="1" id="form-pic" value="<{$prd_data.pic}>" />
			<a class="btn" id="upload-btn" href="javascript:;">选择图片</a>
			<span class="required" title="必填项">*</span>
			<span class="lpad">500×500 图片只能大于这个尺寸，图片太小就模糊了，该图要用作淘宝商品图的。</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd"></td>
		<td><img src="<{$prd_data.pic_thumb}>" width="200" height="200" /></td>
	  </tr>
	  <tr>
		<td class="ltd">韩网价格</td>
		<td><input type="text" name="price_kr" class="txt" size="20" value="<{$prd_data.price_kr}>" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">请填写原价，不要填写打折后的价格，注意！</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">RMB价格</td>
		<td><input type="text" name="price" class="txt" size="20" value="<{$prd_data.price}>" />
			<span class="lpad">韩网价格与RMB价格填一个即可，另一个会通过汇率换算出来。两个都填则按填写的值为准！一般请填写韩网价格</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否包含国际运费</td>
		<td><input type="checkbox" name="is_freight" value="1" <{if $prd_data.is_freight eq 1}>checked="checked"<{/if}> />
			<span class="lpad">价格中是否包含国际运费</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">重量</td>
		<td><input type="text" name="weight" class="txt" size="20" value="<{$prd_data.weight}>" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">单位（克），用以计算国际运费，默认值为类目对应的重量，应根据商品的实际情况设置。</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩网地址</td>
		<td><input type="text" name="kr_url" size="80" class="txt" value="<{$prd_data.kr_url}>" />
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  
	  <tr>
		<td class="ltd" height="340">细节描述</td>
		<td><script type="text/plain" name="content" id="editor"><{$prd_data.content}></script></td>
	  </tr>
	  <tr>
		<td class="ltd">风格特征</td>
		<td><ul class="prd-style clearfix">
				<{if $product_style}>
					<{foreach $product_style as $item}>
						<{$f = 0}>
						<{if $prd_data.product_style}>
						<{foreach $prd_data.product_style as $style}>
							<{if $style eq $item}><{$f = 1}><{break}><{/if}>
						<{/foreach}>
						<{/if}>
						<li><input type="checkbox" name="style[]" value="<{$item}>" <{if $f eq 1}>checked="checked"<{/if}> /> <span class="rpad"><{$item}></span></li>
					<{/foreach}>
				<{/if}>
			</ul>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">淘宝属性</td>
		<td>
			<{if $tb_attr_items}>
			<!--输出所有单选属性-->
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover tbl-tbattr">
			  <tr>
			  	<th>单选：</th>
			  </tr>
			  <tr>
			  	<td>
					<ul class="tb-attr clearfix">
					<{foreach $tb_attr_items as $item}>
						<{if $item.type eq 'select'}>
							<li><span class="rpad"><{$item.tb_attr_value}>：</span>
							<select name="tb_attrs[<{$item.tb_attr_id}>]">
							<option value=""></option>
							<{foreach $item.items as $item2}>
								<{$f=0}>
								<{if $prd_attr_items}>
									<!--循环判断属性值-->
									<{foreach $prd_attr_items as $item3}>
										<{if $item3.tb_attr_id eq $item.tb_attr_id and $item3.tb_attr_value eq $item2.tb_attr_id}>
											<{$f=1}>
											<{break}>
										<{/if}>
									<{/foreach}>
								<{/if}>
								<option value="<{$item2.tb_attr_id}>" <{if $f eq 1}>selected="selected"<{/if}>><{$item2.tb_attr_value}></option>
							<{/foreach}>
							</select>
							<{if $item.required eq 1}><span class="required" title="必填项">*</span><{/if}>
							</li>
						<{/if}>
					<{/foreach}>
					</ul>
				</td>
			  </tr>
			</table>
			
			
			<!--输出所有多选属性-->
			<{foreach $tb_attr_items as $item}>
			<{if $item.type eq 'checkbox' and $item.items|count gt 0}>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover tbl-tbattr">
			  <tr>
			  	<th>多选：<{$item.tb_attr_value}>
					<{if $item.required eq 1}><span class="required" title="必填项">*</span><{/if}>
				</th>
			  </tr>
			  <tr>
			  	<td>
					<ul class="tb-attr tb-attr-checkbox clearfix">
					<{foreach $item.items as $item2}>
						<{$f=0}>
						<{foreach $prd_attr_items as $item3}>
							<{if $item3.tb_attr_id eq $item.tb_attr_id and $item3.tb_attr_value eq $item2.tb_attr_id}>
								<{$f=1}>
								<{$break}>
							<{/if}>
						<{/foreach}>
						<li><input type="checkbox" name="tb_attrs[<{$item.tb_attr_id}>][]" value="<{$item2.tb_attr_id}>" <{if $f eq 1}>checked="checked"<{/if}> />
							<span class="rpad"><{$item2.tb_attr_value}></span>
						</li>
					<{/foreach}>
					</ul>
				</td>
			  </tr>
			</table>
			<{/if}>
			<{/foreach}>
			
			<{/if}>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">SKU销售属性</td>
		<td><{if $tb_prop_items}>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover tbl-prop">
				  <tr>
				  	<{foreach $tb_prop_items as $item}>
					<th><{$item.tb_prop_value}>（中文/韩文）</th>
				  	<{/foreach}>
					<th>价格（KRW/CNY）</th>
					<th>起售数量</th>
					<th>库存数量</th>
					<th>断货</th>
					<th><a href="javascript:;" class="icon_add btn-add" title="添加新选项">添加</a></th>
				  </tr>
				  
				  <{if $prd_sku_items}>
				  <{foreach $prd_sku_items as $sku_item}>
				  <tr class="prop" data-line="<{$sku_item.sku_id}>">
				  	<{foreach $tb_prop_items as $item}>
						<{foreach $sku_item.edit_data as $key => $val}>
							<{if $key eq $item.prop_value_id}>
								<{$value=$val[0]}>
								<{$value_kr=$val[1]}>
								<{$break}>
							<{/if}>
						<{/foreach}>
					<td class="prop_td">
						<input type="text" name="prop_value_cn[<{$sku_item.sku_id}>][<{$item.prop_value_id}>]" class="txt prop_value_cn" data-prop="<{$item.prop_value_id}>" data-name="prop_value_cn" size="15" value="<{$value}>" />
						<input type="text" name="prop_value_kr[<{$sku_item.sku_id}>][<{$item.prop_value_id}>]" class="txt prop_value_kr" data-prop="<{$item.prop_value_id}>" data-name="prop_value_kr" size="15" value="<{$value_kr}>" />
					</td>
					<{/foreach}>
					
					<td><input type="text" name="prop_price_kr[<{$sku_item.sku_id}>]" data-name="prop_price_kr" class="txt" size="10" value="<{$sku_item.price_kr}>" />
						<input type="text" name="prop_price[<{$sku_item.sku_id}>]" data-name="prop_price" class="txt" size="6" value="<{$sku_item.price}>" />
					</td>
					<td><input type="text" name="prop_number[<{$sku_item.sku_id}>]" data-name="prop_number" class="txt" size="6" value="<{$sku_item.number}>" /></td>
					<td><input type="text" name="prop_stock[<{$sku_item.sku_id}>]" data-name="prop_stock" class="txt" size="6" value="<{$sku_item.stock}>" /></td>
					<td><input type="checkbox" name="prop_soldout[<{$sku_item.sku_id}>]" data-name="prop_soldout" value="1" <{if $sku_item.is_soldout eq 1}>checked="checked"<{/if}> /></td>
					<td><a href="javascript:;" class="icon_del btn-del" title="删除该选项">删除</a></td>
				  </tr>
				  <{/foreach}>
				  
				  		<!--计算SKU数-->
				  		<{$len=$prd_sku_items|count}>
				  <{else}>
				  		<{$len=0}>
				  <{/if}>
				  
				  
				  <{if $len gte 6}><{$len=1}><{else}><{$len=6-$len}><{/if}>
				  <{for $i=1 to $len}>
				  <tr class="prop" data-line="<{$i}>">
				  	<{foreach $tb_prop_items as $item}>
					<td class="prop_td">
						<input type="text" name="prop_value_cn[<{$i}>][<{$item.prop_value_id}>]" class="txt prop_value_cn" data-prop="<{$item.prop_value_id}>" data-name="prop_value_cn" size="15" />
						<input type="text" name="prop_value_kr[<{$i}>][<{$item.prop_value_id}>]" class="txt prop_value_kr" data-prop="<{$item.prop_value_id}>" data-name="prop_value_kr" size="15" />
					</td>
					<{/foreach}>
					<td><input type="text" name="prop_price_kr[<{$i}>]" data-name="prop_price_kr" class="txt" size="10" />
						<input type="text" name="prop_price[<{$i}>]" data-name="prop_price" class="txt" size="6" />
					</td>
					<td><input type="text" name="prop_number[<{$i}>]" data-name="prop_number" class="txt" size="6" value="1" /></td>
					<td><input type="text" name="prop_stock[<{$i}>]" data-name="prop_stock" class="txt" size="6" value="1000" /></td>
					<td><input type="checkbox" name="prop_soldout[<{$i}>]" data-name="prop_soldout" value="1" /></td>
					<td><a href="javascript:;" class="icon_del btn-del" title="删除该选项">删除</a></td>
				  </tr>
				  <{/for}>
				</table>
			<{/if}>
			<span class="pad">
				SKU商品信息，一行为一个单品。<br />
				价格为实际价格，而不是增加、减少的价格。如果不填写，最终的价格和上面的价格一样。韩价和RMB价格填一个即可，一般填写韩价<br />
				颜色，尺码等销售属性，中文和韩文都必须正确填写，少一项都不能正确发布商品。
			</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否现货</td>
		<td><input type="hidden" name="is_spot" value="<{$prd_data.is_spot}>" />
			<input type="checkbox" name="is_spot_1" value="1" disabled="disabled" <{if $prd_data.is_spot eq 1}>checked="checked"<{/if}> />
			<span class="lpad">该值不可修改</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否不可退换</td>
		<td><input type="checkbox" name="is_no_refund" value="1"  <{if $prd_data.is_no_refund eq 1}>checked="checked"<{/if}> />
			<span class="lpad">默认值继承自当前商品类目</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">关键词</td>
		<td><input type="text" name="keywords" class="txt" size="100" value="<{$prd_data.keywords}>" />
			<span class="lpad">用逗号（,）分开，用以页面优化</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="description" cols="100" rows="3" class="txt"><{$prd_data.description}></textarea>
			<span class="lpad">用以页面优化</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">备注信息</td>
		<td><textarea name="remark" cols="100" rows="3" class="txt"><{$prd_data.remark}></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">&nbsp;</td>
		<td><input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="/js/upload.picture.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.min.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.init.js"></script>
<script type="text/javascript">
$(function(){
	var btn = $('#upload-btn')[0];
	upload.picture(btn, 'product', function(d){
		if (d.error == 0){
			$('#form-pic').val(d.path);
		} else {
			popup.alert(d.message);
		}
	});

	editor.init('editor', {'folder' : 'prdDetail'});
	
	//添加销售属性选项
	var line = 10;
	$('.btn-add').click(function(){
		var tbl = $(this).parents('table.tbl');
		var tr  = $('tr:last', tbl);
		var tr_clone = $('<tr class="prop" data-line="'+ line +'">' + tr.html() +'</tr>');
		var input = $('input', tr_clone);
		input.each(function(){
			var prop = $(this).attr('data-prop');
			var name = $(this).attr('data-name');
			
			var name2 = name + '['+ line +']';
			if (prop){
				name2 += '['+ prop +']';
			}
			
			$(this).attr('name', name2);
		});
		
		tr.after(tr_clone);
		
		$('.btn-del', tr_clone).click(function(){
			delProp(this);
		});
		
		$('input.prop_value_cn', tr_clone).blur(function(){
			doWord(this);
		});
		
		tabLine();
		line += 1;
	});
	
	//删除销售属性选项
	$('.btn-del').click(function(){
		delProp(this);
	});
	
	function delProp(em){
		var tbl = $(em).parents('table.tbl');
		var len = $('tr', tbl).length;
		if (len > 2){
			$(em).parents('tr.prop').remove();
			tabLine();
		} else {
			$(em).parents('tr.prop').find('input').val('');
			popup.alert('你要删除我？NO.<br />程序员说我可以拒绝您的请求，呵呵~~~');
		}
	}
	
	
	function doWord(em){
		var cnBox = $(em);
		var val = cnBox.val();
			val = $.trim(val);
		if (val == ''){return false;}
			val = preg_replace('（', '(', val);
			val = preg_replace('）', ')', val);
			
		
		var td = cnBox.parents('td.prop_td');
		var krBox = $('input.prop_value_kr', td);
		
		var ret = val.match(/^([^\(]+)\(([^\)]+)\)$/);
		if (ret){
			cnBox.val($.trim(ret[1]));
			krBox.val($.trim(ret[2]));
		} else {
			val2 = val.toLowerCase();
			if (val2 == 'free' || val2 == 'one size' || val2 == 'onesize'){
				krBox.val(val);
				cnBox.val('均码');
			} else {
				var f = val.match(/^[a-zA-Z0-9]+$/);
				if (f){
					cnBox.val(val);
					krBox.val(val);
				}
			}
		}
	}
	
	//中英文自动转换
	$('input.prop_value_cn').blur(function(){
		doWord(this);
	});
});
</script>
<{include file="common/footer.tpl"}>