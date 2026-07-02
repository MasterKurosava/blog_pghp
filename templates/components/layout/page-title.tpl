<h{$level|default:'1'} class="page-title{if $class} {$class|escape}{/if}"{if $id} id="{$id|escape}"{/if}>
    {if $href}
        <a href="{$href|escape}" class="page-title__link">
            {$title|escape}
        </a>
    {else}
        {$title|escape}
    {/if}
</h{$level|default:'1'}>
