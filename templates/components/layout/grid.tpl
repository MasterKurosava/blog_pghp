<div class="grid grid--cols-{$cols|default:'auto'}{if $gap} grid--gap-{$gap|escape}{/if}{if $class} {$class|escape}{/if}"{if $role} role="{$role|escape}"{/if}>
    {$content nofilter}
</div>
