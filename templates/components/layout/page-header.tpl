<header class="page-header{if $row} page-header--row{/if}{if $class} {$class|escape}{/if}">
    <div class="page-header__content">
        {if $title}
            {include file="components/layout/page-title.tpl" title=$title href=$titleHref level=$titleLevel}
        {/if}
        {if $subtitle}
            {include file="components/layout/page-subtitle.tpl" text=$subtitle size=$subtitleSize}
        {/if}
    </div>
    {if $actionLabel}
        <div class="page-header__actions">
            {include file="components/ui/button.tpl"
                variant=$actionVariant|default:'ghost'
                size=$actionSize|default:'md'
                href=$actionHref
                label=$actionLabel
                icon=$actionIcon
                ariaLabel=$actionAriaLabel
            }
        </div>
    {/if}
</header>
