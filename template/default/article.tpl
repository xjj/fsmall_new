<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/article.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><{$article_data.title}>
</div>

<div class="wrap clearfix">
	<div class="article-side">
    	<div class="article-side-wrap">
        	<div class="article-side-box">
            	
            </div>
        </div>
    </div>
	<div class="article-main">
    	<div class="article-title"><{$article_data.title}></div>
        <div class="article-main-wrap">
        	<{$article_data.content}>
        </div>
    </div>
</div>
<{include file="common/footer.tpl"}>