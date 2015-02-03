<{include file="common/header.tpl"}>
<div class="suplier-wrap">
开发中..
</div>
<div class="suplier-wrap" style=" display:none">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
	  <tr>
		<th><{$LANG.PRODUCT_QUERY}></th>
	  </tr>
	  <tr>
		<td>
			<form name="f" id="fs" method="get" action="/product">
			<span class="mpad"><{$LANG.PRODUCT_NAME}>：</span>
			<input type="text" name="pn" class="txt" size="16" value="<{$smarty.get.pn}>" />
			<span class="lpad"><{$LANG.PRODUCT_SN}>：</span>
			<input type="text" name="sn" class="txt" size="16" value="<{$smarty.get.sn}>" />
			<span class="lpad"><{$LANG.PRODUCT_STATUS}>：</span>
			<select name="ps">
			<option value="">--<{$LANG.PRODUCT_STATUS_ALL}>--</option>
			<option value="soldout" <{if $smarty.get.ps eq 'soldout'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_SOLDOUT}></option>
			<option value="sale" <{if $smarty.get.ps eq 'sale'}>selected="selected"<{/if}>><{$LANG.PRODUCT_STATUS_SALE}></option>
			</select>
			<span class="lpad"><{$LANG.PRODUCT_ADD_TIME}>：</span>
			<input type="text" name="st" class="txt" size="16" value="<{$smarty.get.st}>" />
			~
			<input type="text" name="et" class="txt" size="16" value="<{$smarty.get.et}>" />
			<a href="javascript:;" class="btn" onclick="$('#fs').submit();"><{$LANG.SUBMIT}></a>
			</form>
		</td>
	  </tr>
	</table>	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl">
	  <tr>
	  	<th><input type="checkbox" name="chk1" id="chk1" value="1" />
			<span class="lpad">SKUID</span>
		</th>
		<th><{$LANG.PRODUCT_NAME}></th>
		<th><{$LANG.PRODUCT_SN}></th>
		<th><{$LANG.PRODUCT_ATTRIBUTE}></th>
		<th><{$LANG.PRODUCT_PRICE}></th>
		<th><{$LANG.PRODUCT_PRICE_DISCOUNT}></th>
		<th><{$LANG.PRODUCT_ADD_TIME}></th>
		<th><{$LANG.PRODUCT_STATUS}></th>
		<th><{$LANG.OPERATE}></th>
	  </tr>
	  <{if $product_items}>
	  <{foreach $product_items as $item}>
	  <tr>
		<td><input type="checkbox" name="sku_id[<{$item.sku_id}>]" value="<{$item.sku_id}>" />
			<span class="lpad"><{$item.sku_id}></span></td>
		<td><{$item.product_name_kr}></td>
		<td><{$item.product_sn}></td>
		<td><{$item.props_value_kr}></td>
		<td>₩<{$item.price_kr}></td>
		<td>₩<{$item.price_kr_discount}></td>
		<td><{$item.add_time|date_format:'Y-m-d'}></td>
		<td><{if $item.is_soldout eq 1}>
				<span class="orange"><{$LANG.STATUS_SOLDOUT}></span>
			<{else}>
				<span class="green"><{$LANG.STATUS_SALE}></span>
			<{/if}>
		</td>
		<td>
			<{if $item.is_soldout eq 1}>
				<a href="javascript:;" data-href="/product/cancle_soldout/<{$item.sku_id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-cancle"><{$LANG.OPERATE_SKU_SOLDOUT_CANCLE}></a>
			<{else}>
				<a href="javascript:;" data-href="/product/soldout/<{$item.sku_id}><{if $params neq ''}>?<{$params}><{/if}>" class="btn-soldout"><{$LANG.OPERATE_SKU_SOLDOUT}></a>
			<{/if}>
		</td>
	  </tr>
	  <{/foreach}>
	  <{/if}>
	</table>
	
	<div class="col-box clearfix" style="background:#f9f9f9;padding:15px 10px;border:1px solid #eee">
		<div class="z">
			<span class="mpad"><input type="checkbox" name="chk2" id="chk2" value="1" /></span>
			<span class="lpad">
				<a href="javascript:;" id="soldout" class="btn btn3"><{$LANG.OPERATE_SKU_SOLDOUT}></a>
				<a href="javascript:;" id="soldout_cancle" class="btn btn3"><{$LANG.OPERATE_SKU_SOLDOUT_CANCLE}></a>
			</span>
		</div>
		<div class="y"><{$pagebox}></div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var conf_cancle = {
		'title' : '<{$LANG.POP_TITLE_DEFAULT}>',
		'content' : '<{$LANG.POP_CONTENT_SKU_CANCLE}>',
		'noBtnVal' : '<{$LANG.POP_BUTTON_NO}>',
		'yesBtnVal' : '<{$LANG.POP_BUTTON_YES}>'
	};
	
	var conf_soldout = {
		'title' : '<{$LANG.POP_TITLE_DEFAULT}>',
		'content' : '<{$LANG.POP_CONTENT_SKU_SOLDOUT}>',
		'noBtnVal' : '<{$LANG.POP_BUTTON_NO}>',
		'yesBtnVal' : '<{$LANG.POP_BUTTON_YES}>'
	};
	
	var items = $('input[type=checkbox]');
	
	$('#chk1').click(function(){
		checked(this, items);
	});
	$('#chk2').click(function(){
		checked(this, items);
	});
	
	function checked(em, items){
		var val = $(em).prop('checked');
		if (val){
			items.each(function(){
				$(this).prop('checked', true);
			});
		} else {
			items.each(function(){
				$(this).prop('checked', false);
			});
		}
	}
	
	$('.btn-cancle').click(function(){
		var url = $(this).attr('data-href');
		conf_cancle['follow'] = this;
		var tbox = popup.box(conf_cancle, function(){
			location.href = url;
		});
	});
	
	$('.btn-soldout').click(function(){
		var url = $(this).attr('data-href');
		conf_soldout['follow'] = this;
		var tbox = popup.box(conf_soldout, function(){
			location.href = url;
		});
	});
	
	function fetch_checked(){
		var items = [];
		$('input[name^=sku_id]').each(function(){
			if ($(this).prop('checked')){
				var sku_id = $(this).val();
				items.push(sku_id);
			}
		});
		
		return items;
	}
	
	var btn_stat = false;
	$('#soldout').click(function(){
		if (btn_stat){return false;} else {
			btn_stat = true;
		}
		
		conf_soldout['follow'] = this;
		var tbox = popup.box(conf_soldout, function(){
			var items = fetch_checked();
			if (items.length > 0){
				var sku_ids = items.join(',');
				$.post('/product/soldout_multi', {'sku_id' : sku_ids}, function(dt){
					
					location.reload();
					
				});
			} 
		}, function(){
			this.close();
			btn_stat = false;
		});
	});
	
	$('#soldout_cancle').click(function(){
		if (btn_stat){return false;} else {
			btn_stat = true;
		}
		
		conf_cancle['follow'] = this;
		var tbox = popup.box(conf_cancle, function(){
			var items = fetch_checked();
			if (items.length > 0){
				var sku_ids = items.join(',');
				$.post('/product/soldout_cancle_multi', {'sku_id' : sku_ids}, function(dt){
					
					location.reload();
					
				});
			} 
		}, function(){
			this.close();
			btn_stat = false;
		});
	});
});
</script>
<{include file="common/footer.tpl"}>