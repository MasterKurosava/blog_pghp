<{$tag|default:'div'} class="card{if $elevated|default:false} card--elevated{/if}{if $interactive|default:false} card--interactive{/if}{if $class|default:''} {$class|escape}{/if}"{if $role|default:''} role="{$role|escape}"{/if}>
    {$content nofilter}
</{$tag|default:'div'}>
