<section class="section{if $size|default:''} section--{$size|escape}{/if}{if $muted|default:false} section--muted{/if}{if $bordered|default:false} section--bordered{/if}{if $animate|default:false} section--animate{/if}{if $class|default:''} {$class|escape}{/if}"{if $id|default:''} id="{$id|escape}"{/if}>
    {$content nofilter}
</section>
