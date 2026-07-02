{assign var="_variant" value=$variant|default:'primary'}
{assign var="_size" value=$size|default:'md'}
{assign var="_type" value=$type|default:'button'}
{assign var="_iconPosition" value=$iconPosition|default:'right'}
{if $iconLeft|default:false}{assign var="_iconPosition" value='left'}{/if}

{if $href|default:''}
<a
    href="{$href|escape}"
    class="btn btn--{$_variant} btn--{$_size}{if $loading|default:false} btn--loading{/if}{if $class|default:''} {$class|escape}{/if}"
    {if $target|default:''}target="{$target|escape}"{/if}
    {if $rel|default:''}rel="{$rel|escape}"{elseif ($target|default:'') === '_blank'}rel="noopener noreferrer"{/if}
    {if $ariaLabel|default:''}aria-label="{$ariaLabel|escape}"{/if}
    {if $disabled|default:false}aria-disabled="true" tabindex="-1"{/if}
    {if $id|default:''}id="{$id|escape}"{/if}
>
{else}
<button
    type="{$_type|escape}"
    class="btn btn--{$_variant} btn--{$_size}{if $loading|default:false} btn--loading{/if}{if $class|default:''} {$class|escape}{/if}"
    {if $ariaLabel|default:''}aria-label="{$ariaLabel|escape}"{/if}
    {if $disabled|default:false}disabled{/if}
    {if $id|default:''}id="{$id|escape}"{/if}
>
{/if}
    {if $loading|default:false}
        <span class="btn__spinner" aria-hidden="true">
            {include file="components/ui/icon.tpl" name="loader" size="sm" ariaHidden=true}
        </span>
    {/if}
    {if $icon|default:'' && $_iconPosition === 'left'}
        {include file="components/ui/icon.tpl" name=$icon class="btn__icon" ariaHidden=true}
    {/if}
    <span class="btn__label">{$label|escape}</span>
    {if $icon|default:'' && $_iconPosition === 'right'}
        {include file="components/ui/icon.tpl" name=$icon class="btn__icon btn__icon--trailing" ariaHidden=true}
    {/if}
{if $href|default:''}
</a>
{else}
</button>
{/if}
