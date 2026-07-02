{if isset($category)}
    {assign var="_title" value=$category.title}
    {assign var="_url" value=$category.url}
    {assign var="_description" value=$category.description|default:''}
    {assign var="_count" value=$category.articles_count|default:null}
{else}
    {assign var="_title" value=$title}
    {assign var="_url" value=$url}
    {assign var="_description" value=$description|default:''}
    {assign var="_count" value=$articlesCount|default:null}
{/if}

{capture assign="_cardContent"}
    <h3 class="category-card__title">
        {if $_url}
            <a href="{$_url|escape}" class="category-card__title-link">{$_title|escape}</a>
        {else}
            {$_title|escape}
        {/if}
    </h3>

    {if $_description}
        <p class="category-card__description">{$_description|escape}</p>
    {/if}

    {if $_count !== null}
        <div class="category-card__meta">
            <span class="category-card__count">
                {include file="components/ui/icon.tpl" name="file" size="sm" ariaHidden=true}
                <span>{$_count|escape} материалов</span>
            </span>
        </div>
    {/if}

    {if $_url}
        <div class="category-card__footer">
            {include file="components/ui/button.tpl"
                variant=$buttonVariant|default:'outline'
                size='md'
                href=$_url
                label=$buttonLabel|default:'Смотреть категорию'
                icon='arrow-right'
                iconPosition='right'
            }
        </div>
    {/if}
{/capture}

{include file="components/ui/card.tpl"
    tag='article'
    interactive=true
    class="category-card{if $class|default:''} {$class|escape}{/if}"
    content=$_cardContent
}
