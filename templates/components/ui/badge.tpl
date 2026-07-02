<span class="badge badge--{$variant|default:'neutral'}{if $class} {$class|escape}{/if}">
    {if $icon}
        {include file="components/ui/icon.tpl" name=$icon class="badge__icon" ariaHidden=true}
    {/if}
    {$label|escape}
</span>
