<div class="article-card__badges">
    {if isset($categories) && $categories|@count > 0}
        {foreach $categories as $categoryItem}
            {include file="components/ui/badge.tpl"
                label=$categoryItem.label|default:$categoryItem
                variant=$categoryItem.variant|default:'category'
            }
        {/foreach}
    {elseif isset($category) && $category}
        {include file="components/ui/badge.tpl" label=$category variant='category'}
    {/if}

    {if $isNew}
        {include file="components/ui/badge.tpl" label='Новое' variant='new'}
    {/if}

    {if $isPopular}
        {include file="components/ui/badge.tpl" label='Популярное' variant='popular'}
    {/if}
</div>
