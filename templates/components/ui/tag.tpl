{if $href}
<a href="{$href|escape}" class="tag{if $accent} tag--accent{/if}{if $class} {$class|escape}{/if}">
    {$label|escape}
</a>
{else}
<span class="tag{if $accent} tag--accent{/if}{if $class} {$class|escape}{/if}">
    {$label|escape}
</span>
{/if}
