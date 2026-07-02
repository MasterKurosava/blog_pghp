<div class="home">
    <section class="hero">
        <div class="hero__decor" aria-hidden="true"></div>
        <div class="container hero__inner fade-in">
            <span class="hero__badge">{$hero.badge|escape}</span>
            <h1 class="hero__title">{$hero.title|escape}</h1>
            <p class="hero__subtitle">{$hero.subtitle|escape}</p>
            <a href="{$hero.cta_href|escape}" class="btn btn--primary hero__cta">{$hero.cta|escape}</a>
        </div>
    </section>

    <div class="container home__body" id="categories">
        {if $categories|@count > 0}
            {foreach $categories as $category}
                {include file="partials/category-block.tpl" category=$category}
            {/foreach}
        {else}
            <section class="home__empty fade-in">
                <p class="home__empty-text">Материалы скоро появятся.</p>
            </section>
        {/if}
    </div>
</div>
