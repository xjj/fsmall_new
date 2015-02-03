<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/notice.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/notice">公告-NOTICE</a>
</div>

<div class="wrap">
	<div class="notice-title"></div>
	
    <div class="notice-wrap">
    	<div class="notice-detail clearfix">
        	<div class="z"><{$notice_data.title|capitalize}></div>
            <div class="y">
            	<{$notice_data.add_time|date_format:'Y-m-d H:i'}>（点击量：<{$notice_data.visit_count}>）
            </div>
        </div>
    	
        <div class="notice-content">
        	<{$notice_data.content}>
        </div>
        
        <div class="notice-more">
        	<a href="/event">了解更多非尚活动</a>	
        </div>
    </div>
</div>

<{include file="common/footer.tpl"}>