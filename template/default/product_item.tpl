<{if $item}>
<div class="prd-item">
    <div class="prd-img">
        <a href="/product/<{$item.prd_id}>"><img src="<{$item.pic_thumb}>" /></a>
        <div class="prd-item-price">￥<{$item.price_sale|string_format:'%d'}></div>
    </div>
    <div class="prd-item-title">
        <a href="/product/<{$item.prd_id}>"><{$item.product_name}></a>
    </div>
    <div class="prd-item-tips">
        <{if $item.is_hot eq 1}><span class="icon-hot"></span><{/if}>
        <{if $item.is_new eq 1}><span class="icon-new"></span><{/if}>
        <{if $item.is_spot eq 1}><span class="icon-spot"></span><{/if}>
        <{if $item.discount gt 0}><span class="disc"><{$item.discount}>%OFF</span><{/if}>
        
        <span class="hot"></span>
        <span class="new"></span>
        <span class="spot"></span>
    </div>
</div>
<{/if}>