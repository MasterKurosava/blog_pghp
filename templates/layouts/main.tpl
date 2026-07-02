<!DOCTYPE html>
<html lang="ru">
<head>
    {include file="partials/head.tpl"}
</head>
<body
    class="app-body"
    data-api-search="{url path='/api/search'}"
    data-toast-copied="{str key='toast.link_copied'|escape:'html'}"
    data-toast-copy-error="{str key='toast.copy_error'|escape:'html'}"
    data-toast-search-error="{str key='toast.search_error'|escape:'html'}"
    data-search-min="{$app.ui.search_min_length|default:2}"
    data-search-empty-title="{str key='search.empty_title'|escape:'html'}"
    data-search-empty-description="{str key='search.empty_description'|escape:'html'}"
>
    {include file="components/feedback/page-loader.tpl"}
    <a href="#main-content" class="skip-link">{str key='ux.skip_link'}</a>

    {include file="partials/header.tpl"}

    <main id="main-content" class="main{if $mainClass|default:''} {$mainClass|escape}{/if}" tabindex="-1">
        <div class="page-skeleton js-page-skeleton" aria-hidden="true">
            {include file="components/feedback/skeleton-group.tpl" count=3}
        </div>
        <div class="page-content js-page-content">
            {if $fullWidthContent|default:false}
                {$content nofilter}
            {else}
                <div class="container">
                    {$content nofilter}
                </div>
            {/if}
        </div>
    </main>

    {include file="partials/footer.tpl"}
    {include file="components/feedback/share-modal.tpl"}
    {include file="components/feedback/toast-stack.tpl"}
    {include file="components/ui/back-to-top.tpl"}
</body>
</html>
