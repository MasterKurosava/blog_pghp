{assign var="_variant" value=$variant|default:'primary'}
{assign var="_size" value=$size|default:'md'}
{assign var="_type" value=$type|default:'button'}
{assign var="_iconPosition" value=$iconPosition|default:'right'}
{if $iconLeft}{assign var="_iconPosition" value='left'}{/if}

{if $href}
<a
    href="{$href|escape}"
    class="btn btn--{$_variant} btn--{$_size}{if $loading} btn--loading{/if}{if $class} {$class|escape}{/if}"
    {if $target}target="{$target|escape}"{/if}
    {if $rel}rel="{$rel|escape}"{elseif $target === '_blank'}rel="noopener noreferrer"{/if}
    {if $ariaLabel}aria-label="{$ariaLabel|escape}"{/if}
    {if $disabled}aria-disabled="true" tabindex="-1"{/if}
    {if $id}id="{$id|escape}"{/if}
>
{else}
<button
    type="{$_type|escape}"
    class="btn btn--{$_variant} btn--{$_size}{if $loading} btn--loading{/if}{if $class} {$class|escape}{/if}"
    {if $ariaLabel}aria-label="{$ariaLabel|escape}"{/if}
    {if $disabled}disabled{/if}
    {if $id}id="{$id|escape}"{/if}
>
{/if}
    {if $loading}
        <span class="btn__spinner" aria-hidden="true">
            {include file="components/ui/icon.tpl" name="loader" size="sm" ariaHidden=true}
        </span>
    {/if}
    {if $icon && $_iconPosition === 'left'}
        {include file="components/ui/icon.tpl" name=$icon class="btn__icon" ariaHidden=true}
    {/if}
    <span class="btn__label">{$label|escape}</span>
    {if $icon && $_iconPosition === 'right'}
        {include file="components/ui/icon.tpl" name=$icon class="btn__icon btn__icon--trailing" ariaHidden=true}
    {/if}
{if $href}
</a>
{else}
</button>
{/if}
