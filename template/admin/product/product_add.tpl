<{include file="common/header.tpl"}>
<style type="text/css">
.tb-attr{}
.tb-attr li{ float:left; width:25%; padding:5px 0;}
.tb-attr-checkbox li{ width:10%;}
.prd-style li{ float:left; width:100px; padding:5px 0; overflow:hidden;}

.tbl-prop{ margin-bottom:10px; border:1px solid #eee}
.tbl-prop:last-child{ margin-bottom:0;}
.tbl-tbattr:last-child{ margin-bottom:0}
</style>
<div class="admin-wrap">
	<div class="col-box">
		<a href="/<{$mod}>/add" class="btn btn3">返回到商品类目选择页面</a>
	</div>
	<hr class="line" />
	<form name="f" method="post" action="/<{$mod}>/<{$col}>/<{$cat_data.cat_id}>">
	<input type="hidden" name="cat_id" value="<{$cat_data.cat_id}>" />
	<input type="hidden" name="adm_uid" value="<{$smarty.session.admin.uid}>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl2">
	  <tr>
		<td class="ltd" width="100">商品类目</td>
		<td><{$cat_data.cat_path}></td>
	  </tr>
	  <tr>
		<td class="ltd">名称（韩）</td>
		<td><input type="text" name="product_name_kr" class="txt" size="80" />
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  
	  <tr>
		<td class="ltd">品牌</td>
		<td><select name="brand_id">
			<option value="0">-选择品牌-</option>
			<{if $brand_items}>
			<{foreach $brand_items as $item}>
			<option value="<{$item.brand_id}>"><{$item.brand_name}></option>
			<{/foreach}>
			<{/if}>
			</select>
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩网编号</td>
		<td><input type="text" name="product_sn" class="txt" size="20" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">唯一，韩网商品的编号，品牌名不必填写</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">商品主图</td>
		<td><input type="text" name="pic" class="txt txt2" size="60" readonly="1" id="form-pic" />
			<a class="btn" id="upload-btn" href="javascript:;">选择图片</a>
			<span class="required" title="必填项">*</span>
			<span class="lpad">500×500 图片只能大于这个尺寸，该图要用作淘宝数据包商品图。其他小图通过裁切该图获得</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩网价格</td>
		<td><input type="text" name="price_kr" class="txt" size="20" />
			<span class="lpad">整型，请填写原价，不要填写打折后的价格！如需打折请另行设置！！</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">RMB价格</td>
		<td><input type="text" name="price" class="txt" size="20" />
			<span class="lpad">整型，韩网价格与RMB价格填一个即可，另一个会通过汇率换算出来。两个都填则按填写的值为准！一般请填写韩网价格</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否包含国际运费</td>
		<td><input type="checkbox" name="is_freight" value="1" checked="checked" />
			<span class="lpad">价格中是否包含国际运费</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">重量</td>
		<td><input type="text" name="weight" class="txt" size="20" value="<{$cat_data.weight}>" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">单位（克），用以计算国际运费，默认值继承自类目对应的重量，应根据商品的实际情况设置。</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">韩网地址</td>
		<td><input type="text" name="kr_url" size="80" class="txt" />
			<span class="required" title="必填项">*</span>
		</td>
	  </tr>
	  
	  <tr>
		<td class="ltd" height="340">商品描述</td>
		<td><script type="text/plain" name="content" id="editor"></script></td>
	  </tr>
	  <tr>
		<td class="ltd">风格特征</td>
		<td><ul class="prd-style clearfix" id="prd_style">
				<li style="padding:8px;width:32%;border-right:2px dotted red;float:left; list-style:none;margin:0;padding:0; padding-right:5px;background:#ccc;height:100px;">		<input type="checkbox" name="style[]" value="时尚" /><span class="rpad">时尚</span>
                <input type="checkbox" name="style[]" value="个性" /><span class="rpad">个性</span>
                <input type="checkbox" name="style[]" value="淑女" /><span class="rpad">淑女</span>
                <input type="checkbox" name="style[]" value="性感" /><span class="rpad">性感</span>
                <input type="checkbox" name="style[]" value="可爱" /><span class="rpad">可爱</span>
                <input type="checkbox" name="style[]" value="优雅" /><span class="rpad">优雅</span>
                <input type="checkbox" name="style[]" value="高贵" /><span class="rpad">高贵</span>
                <input type="checkbox" name="style[]" value="气质" /><span class="rpad">气质</span>
                <input type="checkbox" name="style[]" value="经典" /><span class="rpad">经典</span>
                 <input type="checkbox" name="style[]" value="韩版" /><span class="rpad">韩版</span>
                 
                 <input type="checkbox" name="style[]" value="OL" /><span class="rpad">OL</span>
                 <input type="checkbox" name="style[]" value="甜美" /><span class="rpad">甜美</span>
                 <input type="checkbox" name="style[]" value="复古" /><span class="rpad">复古</span>
                 <input type="checkbox" name="style[]" value="欧美" /><span class="rpad">欧美</span>
                 <input type="checkbox" name="style[]" value="简约" /><span class="rpad">简约</span>
                 <input type="checkbox" name="style[]" value="军事风" /><span class="rpad">军事风</span>
                 <input type="checkbox" name="style[]" value="英伦" /><span class="rpad">英伦</span>
                 <input type="checkbox" name="style[]" value="哈伦" /><span class="rpad">哈伦</span>
                
                </li>
                <li style="padding:8px;width:32%;border-right:2px dotted red;float:left; list-style:none;margin:0;padding:0; padding-right:5px;background:#f1f1f1;height:100px;">		<input type="checkbox" name="style[]" value="长款" /><span class="rpad">长款</span>
                <input type="checkbox" name="style[]" value="短款" /><span class="rpad">短款</span>
                <input type="checkbox" name="style[]" value="中长款" /><span class="rpad">中长款</span>
                <input type="checkbox" name="style[]" value="双排" /><span class="rpad">双排</span>
                <input type="checkbox" name="style[]" value="蝴蝶结" /><span class="rpad">蝴蝶结</span>
                <input type="checkbox" name="style[]" value="高腰" /><span class="rpad">高腰</span>
                <input type="checkbox" name="style[]" value="中腰" /><span class="rpad">中腰</span>
                <input type="checkbox" name="style[]" value="低腰" /><span class="rpad">低腰</span>
                <input type="checkbox" name="style[]" value="蕾丝" /><span class="rpad">蕾丝</span>
                
                <input type="checkbox" name="style[]" value="立领" /><span class="rpad">立领</span>
                <input type="checkbox" name="style[]" value="圆领" /><span class="rpad">圆领</span>
                <input type="checkbox" name="style[]" value="v领" /><span class="rpad">v领</span>
                <input type="checkbox" name="style[]" value="翻领" /><span class="rpad">翻领</span>
                <input type="checkbox" name="style[]" value="高领" /><span class="rpad">高领</span>
                <input type="checkbox" name="style[]" value="无袖" /><span class="rpad">无袖</span>
                <input type="checkbox" name="style[]" value="短袖" /><span class="rpad">短袖</span>
                <input type="checkbox" name="style[]" value="长袖" /><span class="rpad">长袖</span>
                <input type="checkbox" name="style[]" value="七分" /><span class="rpad">七分</span>
                <input type="checkbox" name="style[]" value="九分" /><span class="rpad">九分</span>
                <input type="checkbox" name="style[]" value="蝙蝠袖" /><span class="rpad">蝙蝠袖</span>
                
                </li>
                <li style="padding:8px;width:32%;border-right:2px dotted red;float:left; list-style:none;margin:0;padding:0; padding-right:5px;background:#fbfbfb;height:100px;">		<input type="checkbox" name="style[]" value="保暖" /><span class="rpad">保暖</span>
                <input type="checkbox" name="style[]" value="带帽" /><span class="rpad">带帽</span>
                <input type="checkbox" name="style[]" value="显瘦" /><span class="rpad">显瘦</span>
                <input type="checkbox" name="style[]" value="紧身" /><span class="rpad">紧身</span>
                <input type="checkbox" name="style[]" value="宽松" /><span class="rpad">宽松</span>
                <input type="checkbox" name="style[]" value="两件套" /><span class="rpad">两件套</span>
                <input type="checkbox" name="style[]" value="拉链" /><span class="rpad">拉链</span>
                <input type="checkbox" name="style[]" value="口袋" /><span class="rpad">口袋</span>
                <input type="checkbox" name="style[]" value="不规则" /><span class="rpad">不规则</span>
                
                 <input type="checkbox" name="style[]" value="格子" /><span class="rpad">格子</span>
                 <input type="checkbox" name="style[]" value="花纹" /><span class="rpad">花纹</span>
                 <input type="checkbox" name="style[]" value="图案" /><span class="rpad">图案</span>
                 <input type="checkbox" name="style[]" value="条纹" /><span class="rpad">条纹</span>
                 <input type="checkbox" name="style[]" value="麻花" /><span class="rpad">麻花</span>
                 <input type="checkbox" name="style[]" value="豹纹" /><span class="rpad">豹纹</span>
                 <input type="checkbox" name="style[]" value="纯色" /><span class="rpad">纯色</span>
                 <input type="checkbox" name="style[]" value="混色" /><span class="rpad">混色</span>
                 <input type="checkbox" name="style[]" value="拼色" /><span class="rpad">拼色</span>

                </li>
			</ul>
		</td>
	  </tr>
      <tr>
		<td class="ltd">名称（中）</td>
		<td><input type="text" name="product_name" id="product_name" class="txt" size="80" />
			<span class="required" title="必填项">*</span>
			<span class="lpad">不能含有韩文字符，支持英文，数字，简体中文</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">淘宝属性</td>
		<td>
			<{if $tb_attr_items}>
			
			<!--输出所有单选属性-->
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover tbl-tbattr">
			  <tr>
			  	<th>单选</th>
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
							<option value="<{$item2.tb_attr_id}>"><{$item2.tb_attr_value}></option>
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
					<li><input type="checkbox" name="tb_attrs[<{$item.tb_attr_id}>][]" value="<{$item2.tb_attr_id}>" />
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
				  <{for $i=0 to 5}>
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
					<td><input type="text" name="prop_number[<{$i}>]" data-name="prop_number" class="txt" size="5" value="1" /></td>
					<td><input type="text" name="prop_stock[<{$i}>]" data-name="prop_stock" class="txt" size="5" value="1000" /></td>
					<td><input type="checkbox" name="prop_soldout[<{$i}>]" data-name="prop_soldout" value="1" /></td>
					<td><a href="javascript:;" class="icon_del btn-del" title="删除该选项">删除</a></td>
				  </tr>
				  <{/for}>
				</table>
			<{/if}>
			<span class="pad">
				SKU商品信息，一行为一个单品。颜色，尺码等销售属性，中文和韩文都必须正确填写，少一项都不能正确发布商品。<br />
				价格为实际价格，而不是增加、减少的价格。如果不填写，最终的价格和上面的价格一样。韩价和RMB价格填一个即可，一般填写韩价
				
			</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否现货</td>
		<td><input type="checkbox" name="is_spot" value="1" /><span class="lpad">设置后，该值不可修改</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">是否不可退换</td>
		<td><input type="checkbox" name="is_no_refund" value="1" <{if $cat_data.is_no_refund eq 1}>checked="checked"<{/if}> /><span class="lpad">默认值继承自当前商品类目</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">关键词</td>
		<td><input type="text" name="keywords" class="txt" size="100" />
			<span class="lpad">用逗号（,）分开，用以页面优化</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">描述</td>
		<td><textarea name="description" cols="100" rows="3" class="txt"></textarea>
			<span class="lpad">用以页面优化</span>
		</td>
	  </tr>
	  <tr>
		<td class="ltd">备注信息</td>
		<td><textarea name="remark" cols="100" rows="3" class="txt"></textarea></td>
	  </tr>
	  <tr>
		<td class="ltd">&nbsp;</td>
		<td>
		<input type="submit" name="submit" value="提交" class="btn2" /></td>
	  </tr>
	</table>
	</form>
</div>

<script type="text/javascript" src="/js/upload.picture.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.min.js"></script>
<script type="text/javascript" src="/js/ueditor/ueditor.init.js"></script>
<script type="text/javascript">
function modify_product_name(value){
	var product_name= $('#product_name').val();
	var i=product_name.indexOf(value);
	if(i=-1){
		product_name= product_name+value;	
	} 
	if(i>=0){
		product_name= product_name.replace(value,'');	
	}
	$('#product_name').val(product_name );
}
$(function(){
	var btn = $('#upload-btn')[0];
	upload.picture(btn, 'product', function(d){
		if (d.error == 0){
			$('#form-pic').val(d.url);
		} else {
			popup.alert(d.message);
		}
	});

	editor.init('editor', {'folder' : 'detail'});
	
	//添加销售属性选项
	var line = 10;
	$('.btn-add').click(function(){
		var tbl = $(this).parents('table.tbl');
		var tr  = $('tr:last', tbl);
		var tr_clone = $('<tr class="prop">' + tr.html() +'</tr>');
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
			popup.alert('你要删除我？NO.<br />程序员说我可以拒绝您的请求，呵呵！');
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
	
	var input_items = $('#prd_style input');
	input_items.click(function(){
		var title = '';
		input_items.each(function(){
			if ($(this).prop('checked')){
				title += $(this).val();	
			}
		});
		
		$('#product_name').val(title);
	});
});
</script>
<{include file="common/footer.tpl"}>