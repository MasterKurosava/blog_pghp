{if isset($pagination) && $pagination.visible}
<nav class="pagination{if $class|default:''} {$class|escape}{/if}" aria-label="{$ariaLabel|default:'Пагинация'|escape}">
    <a
        href="{if $pagination.prev.disabled}#{else}{$pagination.prev.url|escape}{/if}"
        class="pagination__nav pagination__nav--prev{if $pagination.prev.disabled} pagination__nav--disabled{/if}"
        aria-label="Предыдущая страница"
        {if $pagination.prev.disabled}aria-disabled="true" tabindex="-1"{/if}
    >
        {include file="components/ui/icon.tpl" name="chevron-left" size="sm" ariaHidden=true}
    </a>

    <ol class="pagination__list">
        {foreach $pagination.items as $item}
            <li class="pagination__item">
                {if $item.ellipsis}
                    <span class="pagination__ellipsis" aria-hidden="true">…</span>
                {else}
                    <a
                        href="{$item.url|escape}"
                        class="pagination__link{if $item.active} pagination__link--active{/if}"
                        {if $item.active}aria-current="page"{/if}
                    >
                        {$item.number|escape}
                    </a>
                {/if}
            </li>
        {/foreach}
    </ol>

    <a
        href="{if $pagination.next.disabled}#{else}{$pagination.next.url|escape}{/if}"
        class="pagination__nav pagination__nav--next{if $pagination.next.disabled} pagination__nav--disabled{/if}"
        aria-label="Следующая страница"
        {if $pagination.next.disabled}aria-disabled="true" tabindex="-1"{/if}
    >
        {include file="components/ui/icon.tpl" name="chevron-right" size="sm" ariaHidden=true}
    </a>
</nav>
{/if}
