<div class="home">
    {include file="components/blog/hero.tpl" hero=$hero}

    {capture assign="homeBody"}
        {if $categories|@count > 0}
            {foreach $categories as $category}
                {include file="components/blog/category-section.tpl" category=$category}
            {/foreach}
        {else}
            <div class="home__empty">
                <div class="home__empty-skeleton" aria-hidden="true">
                    {include file="components/feedback/skeleton-group.tpl" count=3}
                </div>

                {capture assign="_emptyTitle"}{str key='home.empty_title'}{/capture}
                {capture assign="_emptyDescription"}{str key='home.empty_description'}{/capture}
                {include file="components/feedback/empty-state.tpl"
                    title=$_emptyTitle
                    description=$_emptyDescription
                    icon='file'
                }
            </div>
        {/if}
    {/capture}

    {include file="components/layout/container.tpl" class='home__body' content=$homeBody id='categories'}
</div>
