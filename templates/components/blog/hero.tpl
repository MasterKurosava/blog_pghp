<section class="hero" aria-labelledby="hero-title">
    <div class="hero__decor" aria-hidden="true"></div>

    {capture assign="_heroContent"}
        <div class="hero__inner section--animate">
            {if $hero.badge}
                <div class="hero__badge">
                    {include file="components/ui/badge.tpl" label=$hero.badge variant='category'}
                </div>
            {/if}

            {include file="components/layout/page-title.tpl" title=$hero.title level='1' class='hero__title' id='hero-title'}

            {if $hero.subtitle}
                {include file="components/layout/page-subtitle.tpl" text=$hero.subtitle size='lg' class='hero__subtitle'}
            {/if}

            {if $hero.cta}
                <div class="hero__actions">
                    {include file="components/ui/button.tpl"
                        variant='primary'
                        size='lg'
                        href=$hero.cta_href|default:'#'
                        label=$hero.cta
                    }
                </div>
            {/if}
        </div>
    {/capture}

    {include file="components/layout/container.tpl" content=$_heroContent}
</section>
