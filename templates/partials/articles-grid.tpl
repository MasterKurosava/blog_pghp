{foreach $articles as $article}
    <div role="listitem">
        {include file="components/blog/article-card.tpl" article=$article}
    </div>
{/foreach}
