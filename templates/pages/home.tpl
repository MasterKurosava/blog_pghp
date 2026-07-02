<div class="home">
    {include file="components/blog/hero.tpl" hero=$hero}

    {capture assign="homeBody"}
        {if $categories|@count > 0}
            {foreach $categories as $category}
                {include file="components/blog/category-section.tpl" category=$category}
            {/foreach}
        {else}
            {include file="components/feedback/empty-state.tpl"
                title='Материалы скоро появятся'
                description='Мы готовим свежие публикации. Загляните позже.'
                icon='file'
            }
        {/if}
    {/capture}

    {include file="components/layout/container.tpl" class='home__body' content=$homeBody id='categories'}
</div>
