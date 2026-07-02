<header class="page-header{if $row|default:false} page-header--row{/if}{if $class|default:''} {$class|escape}{/if}">
    <div class="page-header__content">
        {if $title|default:''}
            {include file="components/layout/page-title.tpl" title=$title href=$titleHref|default:'' level=$titleLevel|default:'2'}
        {/if}
        {if $subtitle|default:''}
            {include file="components/layout/page-subtitle.tpl" text=$subtitle size=$subtitleSize|default:''}
        {/if}
    </div>
    {if $actionLabel|default:''}
        <div class="page-header__actions">
            {include file="components/ui/button.tpl"
                variant=$actionVariant|default:'ghost'
                size=$actionSize|default:'md'
                href=$actionHref|default:''
                label=$actionLabel
                icon=$actionIcon|default:''
                ariaLabel=$actionAriaLabel|default:''
            }
        </div>
    {/if}
</header>
