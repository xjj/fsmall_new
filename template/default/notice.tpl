<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/notice.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span>公告-NOTICE
</div>

<div class="wrap">
	<div class="notice-title"></div>
	
    <div class="notice-wrap">
    	<div class="notice-search">
        <form name="searchNotice" method="get" action="/notice" id="searchNotice">
        	<div class="notice-search-box">
            	<input type="text" name="k" class="notice-txt" value="<{$smarty.get.k}>" /><a href="javascript:;" class="notice-btn" id="btn-notice" onClick="$('#searchNotice').submit();">搜索</a>
            </div>
        </form>
        </div>
    	
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="notice-tbl">
          <tr>
            <th width="60"><span class="notice-tbl-title t1">编号</span></th>
            <th width="60%"><span class="notice-tbl-title t2">主题</span></th>
            <th><span class="notice-tbl-title t3">时间</span></th>
            <th width="100"><span class="notice-tbl-title t4">点击量</span></th>
          </tr>
          <{if $notice_items}>
          <{foreach $notice_items as $item}>
          <tr>
            <td align="center"><{$item.id}></td>
            <td><{if $item.is_top eq 1}><span class="bold orange rpad">[置顶]</span><{/if}><a href="/notice/<{$item.id}>" class="mpad"><{$item.title|capitalize}></a></td>
            <td align="center"><{$item.add_time|date_format:'Y-m-d H:i'}></td>
            <td align="center"><{$item.visit_count}></td>
          </tr>
          <{/foreach}>
          <{/if}>
        </table>
        <{$pagebox}>
    </div>
</div>

<{include file="common/footer.tpl"}>