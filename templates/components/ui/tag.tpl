{if $href|default:''}
<a href="{$href|escape}" class="tag{if $accent|default:false} tag--accent{/if}{if $class|default:''} {$class|escape}{/if}">
    {$label|escape}
</a>
{else}
<span class="tag{if $accent|default:false} tag--accent{/if}{if $class|default:''} {$class|escape}{/if}">
    {$label|escape}
</span>
{/if}
