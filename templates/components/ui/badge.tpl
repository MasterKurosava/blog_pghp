<span class="badge badge--{$variant|default:'neutral'}{if $class|default:''} {$class|escape}{/if}">
    {if $icon|default:''}
        {include file="components/ui/icon.tpl" name=$icon class="badge__icon" ariaHidden=true}
    {/if}
    {$label|escape}
</span>
