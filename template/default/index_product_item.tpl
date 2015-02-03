<{if $item}>
<div class="index-product">
    <div class="index-product-pic">
        <a href="/product/<{$item.prd_id}>"><img src="<{$item.pic_thumb}>" /></a>
        <div class="index-product-price">￥<{$item.price_sale|string_format:'%d'}></div>
    </div>
    <div class="index-product-title">
        <a href="/product/<{$item.prd_id}>"><{$item.product_name}></a>
    </div>
    <div class="index-product-tips">
        <{if $item.is_hot eq 1}><span class="icon-hot"></span><{/if}>
        <{if $item.is_new eq 1}><span class="icon-new"></span><{/if}>
        <{if $item.is_spot eq 1}><span class="icon-spot"></span><{/if}>
        <{if $item.discount gt 0}><span class="disc"><{$item.discount}>%OFF</span><{/if}>
    </div>
</div>
<{/if}>