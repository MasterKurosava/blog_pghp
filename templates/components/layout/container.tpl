<div class="container{if $narrow|default:false} container--narrow{/if}{if $fluid|default:false} container--fluid{/if}{if $class|default:''} {$class|escape}{/if}"{if $id|default:''} id="{$id|escape}"{/if}>
    {$content nofilter}
</div>
