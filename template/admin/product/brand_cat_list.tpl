<{include file="common/header.tpl"}>
<div class="admin-wrap">
	<div class="col-box clearfix">
    	<div class="z"><a href="/<{$mod}>/<{$col}>" class="btn btn3">返回到品牌列表</a></div>
        <div class="y">
            <a href="/<{$mod}>/<{$col}>/cat/<{$brand_data.brand_id}>/update" class="btn btn3">更新 <{$brand_data.brand_name}> 类目</a>
        </div>
    </div>
    <div class="alert-box">
    	品牌类目表是：该品牌的商品所属的类目，制作这个功能主要是给前台品牌页面，类目查询商品提供品牌类目列表。至少每星期更新一次。这里只记录二级类目。
    </div>
    <form name="f" method="post" action="/<{$mod}>/<{$col}>/cat/<{$brand_data.brand_id}>">
    <input type="hidden" name="brand_id" value="<{$brand_data.brand_id}>" />
    <table style=" width:300px;" border="0" cellspacing="0" cellpadding="0" class="tbl">
      <tr>
        <th>品牌ID</th>
        <th>品牌名称</th>
        <th>排序</th>
      </tr>
      <{if $brand_cat_items}>
      <{foreach $brand_cat_items as $item}>
      <tr>
        <td><{$item.cat_id}></td>
        <td><{$item.cat_name}></td>
        <td><input type="text" name="displayorder[<{$item.cat_id}>]" value="<{$item.displayorder}>" size="4" class="txt" /></td>
      </tr>
      <{/foreach}>
      <tr class="nohover">
      	<td colspan="3" ><input type="submit" name="submit" class="btn" value="更新排序" /></td>
      </tr>
      <{/if}>
    </table>
    </form>
</div>
<{include file="common/footer.tpl"}>