<span
    class="icon icon--{$size|default:'md'}{if $class|default:''} {$class|escape}{/if}"
    {if $fill|default:'' || $stroke|default:''}style="{if $fill|default:''}--icon-fill: {$fill|escape};{/if}{if $stroke|default:''}--icon-stroke: {$stroke|escape};{/if}"{/if}
    {if $ariaHidden|default:false || (!$ariaLabel|default:'' && !$label|default:'')}aria-hidden="true"{/if}
    {if $ariaLabel|default:''}aria-label="{$ariaLabel|escape}" role="img"{elseif $label|default:''}aria-label="{$label|escape}" role="img"{/if}
>
    {include file="components/ui/icons/`$name`.tpl"}
</span>
