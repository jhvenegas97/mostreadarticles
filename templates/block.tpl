{* Template to display the most read articles block *}
<div class="pkp_block">
    <h2 class="most-read-title">{translate key="plugins.blocks.mostReadArticles.displayName"}</h2>
    <ul class="most-read-list">
        {foreach from=$mostReadArticles item=article}
            <li class="most-read-item">
                <a class="most-read-link" href="{url page="article" op="view" path=$article.article_id}">
                    <h4><span class="article-title">{$article.title|escape}</span></h4>
                </a>
                <span class="article-views">({$article.total_views} views)</span>
            </li>
        {/foreach}
    </ul>
</div>

<style>
    .most-read-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 10px;
        text-align: center;
        border-bottom: 2px solid #6c757d;
        padding-bottom: 5px;
    }
    .most-read-list {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .most-read-item {
        margin-bottom: 10px;
        font-size: 14px;
        line-height: 1.5;
        color: #495057;
    }
    .most-read-item:last-child {
        margin-bottom: 0;
    }
    .article-title {
        display: inline-block;
        margin-right: 5px;
    }
    .article-views {
        font-size: 12px;
        color: #6c757d;
    }
</style>
