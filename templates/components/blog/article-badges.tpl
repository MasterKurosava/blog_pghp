<div class="article-card__badges">
    {if isset($categories) && $categories|@count > 0}
        {foreach $categories as $categoryItem}
            {include file="components/ui/badge.tpl"
                label=$categoryItem.label|default:$categoryItem
                variant=$categoryItem.variant|default:'category'
            }
        {/foreach}
    {elseif $category|default:''}
        {include file="components/ui/badge.tpl" label=$category variant='category'}
    {/if}

    {if $isNew|default:false}
        {capture assign="_badgeNew"}{str key='article_card.badge_new'}{/capture}
        {include file="components/ui/badge.tpl" label=$_badgeNew variant='new'}
    {/if}

    {if $isPopular|default:false}
        {capture assign="_badgePopular"}{str key='article_card.badge_popular'}{/capture}
        {include file="components/ui/badge.tpl" label=$_badgePopular variant='popular'}
    {/if}
</div>
