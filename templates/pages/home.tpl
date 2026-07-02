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

                {include file="components/feedback/empty-state.tpl"
                    title='Материалы скоро появятся'
                    description='Мы готовим свежие публикации. Загляните позже.'
                    icon='file'
                }
            </div>
        {/if}
    {/capture}

    {include file="components/layout/container.tpl" class='home__body' content=$homeBody id='categories'}
</div>
