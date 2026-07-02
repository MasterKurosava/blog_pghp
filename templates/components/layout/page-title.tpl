<h{$level|default:'1'} class="page-title{if $class|default:''} {$class|escape}{/if}"{if $id|default:''} id="{$id|escape}"{/if}>
    {if $href|default:''}
        <a href="{$href|escape}" class="page-title__link">
            {$title|escape}
        </a>
    {else}
        {$title|escape}
    {/if}
</h{$level|default:'1'}>
