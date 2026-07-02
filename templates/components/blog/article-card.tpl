{if isset($article)}
    {assign var="_title" value=$article.title}
    {assign var="_url" value=$article.url}
    {assign var="_description" value=$article.description|default:''}
    {assign var="_image" value=$article.image|default:''}
    {assign var="_date" value=$article.published_at|default:$article.date|default:''}
    {assign var="_views" value=$article.views|default:''}
    {assign var="_categories" value=$article.categories|default:null}
    {assign var="_category" value=$article.category|default:''}
    {assign var="_isNew" value=$article.is_new|default:$article.isNew|default:false}
    {assign var="_isPopular" value=$article.is_popular|default:$article.isPopular|default:false}
{else}
    {assign var="_title" value=$title}
    {assign var="_url" value=$url}
    {assign var="_description" value=$description|default:''}
    {assign var="_image" value=$image|default:''}
    {assign var="_date" value=$date|default:''}
    {assign var="_views" value=$views|default:''}
    {assign var="_categories" value=$categories|default:null}
    {assign var="_category" value=$category|default:''}
    {assign var="_isNew" value=$isNew|default:false}
    {assign var="_isPopular" value=$isPopular|default:false}
{/if}

{assign var="_showImage" value=true}
{assign var="_showDescription" value=true}
{assign var="_showMeta" value=true}

{if isset($showImage) && !$showImage}{assign var="_showImage" value=false}{/if}
{if isset($showDescription) && !$showDescription}{assign var="_showDescription" value=false}{/if}
{if isset($showMeta) && !$showMeta}{assign var="_showMeta" value=false}{/if}

{if $_showImage && $_image === ''}{assign var="_showImage" value=false}{/if}

{capture assign="_cardContent"}
    {if $_showImage && $_url}
        <a href="{$_url|escape}" class="article-card__media" tabindex="-1" aria-label="{$_title|escape}">
            <img
                src="{$_image|escape}"
                alt=""
                class="article-card__image"
                loading="lazy"
                decoding="async"
                width="400"
                height="225"
            >
        </a>
    {elseif $_showImage}
        <div class="article-card__media">
            <img
                src="{$_image|escape}"
                alt="{$_title|escape}"
                class="article-card__image"
                loading="lazy"
                decoding="async"
                width="400"
                height="225"
            >
        </div>
    {/if}

    <div class="article-card__body">
        {include file="components/blog/article-badges.tpl"
            categories=$_categories
            category=$_category
            isNew=$_isNew
            isPopular=$_isPopular
        }

        <h3 class="article-card__title">
            {if $_url}
                <a href="{$_url|escape}" class="article-card__title-link">{$_title|escape}</a>
            {else}
                {$_title|escape}
            {/if}
        </h3>

        {if $_showDescription && $_description}
            <p class="article-card__description">{$_description|escape}</p>
        {/if}

        {if $_showMeta && ($_date || $_views)}
            <div class="article-card__meta">
                {if $_date}
                    <span class="article-card__meta-item">
                        <time datetime="{$_date|escape}">{$_date|escape}</time>
                    </span>
                {/if}

                {if $_date && $_views}
                    <span class="article-card__meta-divider" aria-hidden="true"></span>
                {/if}

                {if $_views}
                    <span class="article-card__meta-item">
                        {include file="components/ui/icon.tpl" name="eye" size="sm" ariaHidden=true}
                        <span>{$_views|escape}</span>
                    </span>
                {/if}
            </div>
        {/if}

        {if $_url}
            <div class="article-card__footer">
                {include file="components/ui/button.tpl"
                    variant='text'
                    size='md'
                    href=$_url
                    label='Читать'
                    icon='arrow-right'
                    iconPosition='right'
                    ariaLabel="Читать: {$_title|escape}"
                }
            </div>
        {/if}
    </div>
{/capture}

{include file="components/ui/card.tpl"
    tag='article'
    interactive=true
    class="article-card{if $class} {$class|escape}{/if}"
    content=$_cardContent
}
