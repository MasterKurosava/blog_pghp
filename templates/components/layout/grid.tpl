<div class="grid grid--cols-{$cols|default:'auto'}{if $gap|default:''} grid--gap-{$gap|escape}{/if}{if $class|default:''} {$class|escape}{/if}"{if $role|default:''} role="{$role|escape}"{/if}>
    {$content nofilter}
</div>
