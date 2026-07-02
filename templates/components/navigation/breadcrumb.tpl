<nav class="breadcrumb{if $class|default:''} {$class|escape}{/if}" aria-label="{$ariaLabel|default:'Навигация'|escape}">
    <ol class="breadcrumb__list">
        {foreach $items as $item name="breadcrumbItems"}
            <li class="breadcrumb__item">
                {if !$smarty.foreach.breadcrumbItems.last && $item.url}
                    <a href="{$item.url|escape}" class="breadcrumb__link">{$item.label|escape}</a>
                    <span class="breadcrumb__separator" aria-hidden="true">/</span>
                {else}
                    <span class="breadcrumb__current" aria-current="page">{$item.label|escape}</span>
                {/if}
            </li>
        {/foreach}
    </ol>
</nav>
