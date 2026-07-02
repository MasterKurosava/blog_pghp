<article class="article-page">
    {include file="components/navigation/breadcrumb.tpl" items=$breadcrumbs}

    {if $article.image}
        <figure class="article-page__hero">
            <img
                src="{$article.image|escape}"
                alt="{$article.title|escape}"
                class="article-page__hero-image"
                width="1200"
                height="630"
                decoding="async"
                fetchpriority="high"
            >
        </figure>
    {/if}

    <header class="article-page__header">
        {include file="components/layout/page-title.tpl" title=$article.title level='1'}

        <div class="article-page__meta">
            {if $article.published_at}
                <time class="article-page__meta-item" datetime="{$article.published_at|escape}">
                    {$article.published_at|escape}
                </time>
            {/if}

            {if $article.views}
                <span class="article-page__meta-divider" aria-hidden="true"></span>
                <span class="article-page__meta-item">
                    {include file="components/ui/icon.tpl" name="eye" size="sm" ariaHidden=true}
                    <span>{$article.views|escape} просмотров</span>
                </span>
            {/if}

            {if $article.categories|@count > 0}
                <span class="article-page__meta-divider" aria-hidden="true"></span>
                <div class="article-page__categories">
                    {foreach $article.categories as $category}
                        {include file="components/ui/tag.tpl" label=$category.label href=$category.url accent=true}
                    {/foreach}
                </div>
            {/if}
        </div>

        {if $article.description}
            {include file="components/layout/page-subtitle.tpl" text=$article.description size='lg' class='article-page__lead'}
        {/if}
    </header>

    <div class="article-page__content article-prose">
        {$article.content nofilter}
    </div>

    {if $hasRelatedArticles}
        {include file="components/ui/divider.tpl" size='lg'}

        {capture assign="_relatedSection"}
            {include file="components/layout/page-title.tpl" title='Похожие статьи' level='2' class='article-page__related-title'}

            {capture assign="_relatedGrid"}
                {foreach $relatedArticles as $relatedArticle}
                    <div role="listitem">
                        {include file="components/blog/article-card.tpl" article=$relatedArticle}
                    </div>
                {/foreach}
            {/capture}

            {include file="components/layout/grid.tpl" cols=3 role='list' content=$_relatedGrid class='article-page__related-grid'}
        {/capture}

        {include file="components/layout/section.tpl" content=$_relatedSection class='article-page__related'}
    {/if}
</article>
