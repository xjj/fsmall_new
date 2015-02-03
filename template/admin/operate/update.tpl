<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl nohover">
      <tr>
        <th>品牌类目更新</th>
      </tr>
      <tr>
        <td>
        	<div style=" padding-bottom:10px;">更新商品所在品牌都有哪些类目，包含二级类目和三级类目。主要用于前台品牌页面的类目查询。至少每周更新一次。</div>
            <a href="/<{$mod}>/<{$col}>/brand_category" class="btn">更新</a>
            <span class="lpad"><{if $cfg.update_brand_category_time gt 0}>最后更新时间：<{$cfg.update_brand_category_time|date_format:'Y-m-d H:i'}><{/if}></span>
        </td>
      </tr>
    </table>

</div>
<{include file="common/footer.tpl"}>