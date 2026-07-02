{if isset($category)}
<div class="category-page">
    {include file="components/navigation/breadcrumb.tpl" items=$breadcrumbs}

    <header class="category-page__header">
        {include file="components/layout/page-header.tpl"
            title=$category.title
            titleLevel='1'
            subtitle=$category.description
        }
        {if $articlesCount > 0}
            <p class="category-page__count">{$articlesCount|escape} материалов</p>
        {/if}
    </header>

    {if $hasArticles}
        <div class="sort-bar" role="group" aria-label="Сортировка статей">
            <span class="sort-bar__label">Сортировать:</span>
            <div class="sort-bar__options">
                {foreach $sort.options as $option}
                    <a
                        href="{$option.url|escape}"
                        class="sort-bar__option{if $option.active} sort-bar__option--active{/if}"
                        {if $option.active}aria-current="true"{/if}
                    >
                        {$option.label|escape}
                    </a>
                {/foreach}
            </div>
        </div>

        {capture assign="_articlesGrid"}
            {foreach $articles as $article}
                <div role="listitem">
                    {include file="components/blog/article-card.tpl" article=$article}
                </div>
            {/foreach}
        {/capture}

        {include file="components/layout/grid.tpl" cols=3 role='list' content=$_articlesGrid class='category-page__grid'}

        {include file="components/navigation/pagination.tpl" pagination=$pagination}
    {else}
        {include file="components/feedback/empty-state.tpl"
            title='Статей пока нет'
            description='В этой категории ещё нет опубликованных материалов.'
            icon='file'
            button='На главную'
            buttonUrl=$homeUrl
        }
    {/if}
</div>
{else}
<section class="welcome">
    <h1 class="welcome__title">{$heading|escape}</h1>
    <p class="welcome__text">Список категорий в разработке.</p>
</section>
{/if}
