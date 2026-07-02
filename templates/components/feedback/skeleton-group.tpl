{assign var="_count" value=$count|default:3}

<div class="grid grid--cols-3 grid--gap-sm skeleton-group{if $class|default:''} {$class|escape}{/if}" aria-hidden="true" role="presentation">
    {section name=skeleton loop=$_count}
        {include file="components/feedback/skeleton-card.tpl"}
    {/section}
</div>
