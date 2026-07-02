<span
    class="icon icon--{$size|default:'md'}{if $class} {$class|escape}{/if}"
    {if $fill || $stroke}style="{if $fill}--icon-fill: {$fill|escape};{/if}{if $stroke}--icon-stroke: {$stroke|escape};{/if}"{/if}
    {if $ariaHidden || (!$ariaLabel && !$label)}aria-hidden="true"{/if}
    {if $ariaLabel}aria-label="{$ariaLabel|escape}" role="img"{elseif $label}aria-label="{$label|escape}" role="img"{/if}
>
    {include file="components/ui/icons/`$name`.tpl"}
</span>
