<{if $discType eq 'category'}>
	<{$discName = '类目'}>
<{elseif $discType eq 'brand'}>
	<{$discName = '品牌'}>
<{elseif $discType eq 'product'}>
	<{$discName = '商品'}>
<{elseif $discType eq 'spot'}>
	<{$discName = '现货'}>
<{elseif $discType eq 'grade'}>
	<{$discName = '会员等级'}>
<{/if}>

<div class="col-box clearfix">
	<div class="z" style="padding-top:4px">
		<span>折扣类型：</span>
		<a href="/<{$mod}>/<{$col}>/category" <{if $discType eq 'category'}>class="orange bold"<{/if}>>类目折扣</a>
		<span class="dl">|</span>
		<a href="/<{$mod}>/<{$col}>/brand" <{if $discType eq 'brand'}>class="orange bold"<{/if}>>品牌折扣</a>
		<span class="dl">|</span>
		<a href="/<{$mod}>/<{$col}>/product" <{if $discType eq 'product'}>class="orange bold"<{/if}>>商品折扣</a>
		<span class="dl">|</span>
		<a href="/<{$mod}>/<{$col}>/spot" <{if $discType eq 'spot'}>class="orange bold"<{/if}>>现货折扣</a>
		<span class="dl">|</span>
		<a href="/<{$mod}>/<{$col}>/grade" <{if $discType eq 'grade'}>class="orange bold"<{/if}>>会员等级折扣</a>
	</div>
	<div class="y">
		<a href="/<{$mod}>/<{$col}>/<{$discType}>/add" class="btn btn3">添加<{$discName}>折扣</a>
	</div>
</div>
<div class="alert-box">
	商品的最终销售价格为：计算所有折扣，取折扣后价格最低的。
</div>