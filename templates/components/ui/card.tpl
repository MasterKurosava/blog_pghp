<{$tag|default:'div'} class="card{if $elevated} card--elevated{/if}{if $interactive} card--interactive{/if}{if $class} {$class|escape}{/if}"{if $role} role="{$role|escape}"{/if}>
    {$content nofilter}
</{$tag|default:'div'}>
