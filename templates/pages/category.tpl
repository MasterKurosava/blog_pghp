{if isset($category)}
{capture assign="_emptyTitle"}{str key='category.empty_title'}{/capture}
{capture assign="_emptyDescription"}{str key='category.empty_description'}{/capture}
{capture assign="_emptyAction"}{str key='category.empty_action'}{/capture}

<div
    class="category-page"
    data-category-api="{url path='/api/category/'|cat:$category.slug|cat:'/articles'}"
    data-category-slug="{$category.slug|escape}"
    data-current-sort="{$currentSort|default:'newest'|escape}"
    data-current-page="{$pagination.current|default:1}"
    data-last-page="{$pagination.last|default:1}"
    data-infinite-loading="{str key='category.loading_more'|escape:'html'}"
    data-infinite-end="{str key='category.all_loaded'|escape:'html'}"
>
    {include file="components/navigation/breadcrumb.tpl" items=$breadcrumbs}

    <header class="category-page__header">
        {include file="components/layout/page-header.tpl"
            title=$category.title
            titleLevel='1'
            subtitle=$category.description
        }
        {if $articlesCount > 0}
            <p class="category-page__count">{str key='category.materials_count' count=$articlesCount}</p>
        {/if}
    </header>

    {if $hasArticles}
        <div class="sort-bar" role="group" aria-label="{str key='category.sort_label'}">
            <span class="sort-bar__label">{str key='category.sort_label'}</span>
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

        <div class="category-page__grid-wrap js-category-grid-wrap">
            {capture assign="_articlesGrid"}
                {foreach $articles as $article}
                    <div role="listitem">
                        {include file="components/blog/article-card.tpl" article=$article}
                    </div>
                {/foreach}
            {/capture}

            {include file="components/layout/grid.tpl" cols=3 role='list' content=$_articlesGrid class='category-page__grid js-category-grid'}
        </div>

        <div class="infinite-scroll js-infinite-scroll"{if $pagination.last|default:1 <= 1} hidden{/if}>
            <div class="infinite-scroll__sentinel js-infinite-sentinel" aria-hidden="true"></div>
            <div class="infinite-scroll__loader js-infinite-loader" hidden aria-live="polite">
                {include file="components/feedback/skeleton-group.tpl" count=3}
                <p class="infinite-scroll__status">{str key='category.loading_more'}</p>
            </div>
            <p class="infinite-scroll__end js-infinite-end" hidden>{str key='category.all_loaded'}</p>
        </div>
    {else}
        {include file="components/feedback/empty-state.tpl"
            title=$_emptyTitle
            description=$_emptyDescription
            icon='file'
            button=$_emptyAction
            buttonUrl=$homeUrl
        }
    {/if}
</div>
{else}
<section class="welcome">
    <h1 class="welcome__title">{$heading|escape}</h1>
    <p class="welcome__text">{str key='category.index_text'}</p>
</section>
{/if}
