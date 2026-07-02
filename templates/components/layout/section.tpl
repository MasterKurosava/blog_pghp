<section class="section{if $size} section--{$size|escape}{/if}{if $muted} section--muted{/if}{if $bordered} section--bordered{/if}{if $animate} section--animate{/if}{if $class} {$class|escape}{/if}"{if $id} id="{$id|escape}"{/if}>
    {$content nofilter}
</section>
