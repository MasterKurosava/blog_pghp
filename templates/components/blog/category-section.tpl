{capture assign="_sectionContent"}
    <div class="category-section__header">
        {include file="components/layout/page-header.tpl"
            row=true
            title=$category.title
            titleHref=$category.url
            titleLevel='2'
            subtitle=$category.description
            actionLabel='Все статьи'
            actionHref=$category.url
            actionVariant='ghost'
            actionAriaLabel="Все статьи в категории {$category.title|escape}"
        }
    </div>

    {capture assign="_articlesGrid"}
        {foreach $category.articles as $article}
            <div role="listitem">
                {include file="components/blog/article-card.tpl" article=$article}
            </div>
        {/foreach}
    {/capture}

    {include file="components/layout/grid.tpl" cols=3 role='list' content=$_articlesGrid}
{/capture}

{include file="components/ui/card.tpl"
    interactive=true
    class="category-section section--animate"
    content=$_sectionContent
}
