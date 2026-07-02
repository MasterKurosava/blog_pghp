<section class="category-block fade-in">
    <div class="category-block__header">
        <div class="category-block__info">
            <h2 class="category-block__title">
                <a href="{$category.url|escape}" class="category-block__title-link">
                    {$category.title|escape}
                </a>
            </h2>
            {if $category.description}
                <p class="category-block__description">{$category.description|escape}</p>
            {/if}
        </div>
        <a href="{$category.url|escape}" class="btn btn--ghost category-block__all">
            Все статьи
        </a>
    </div>

    <div class="category-block__grid">
        {foreach $category.articles as $article}
            {include file="partials/article-card.tpl" article=$article}
        {/foreach}
    </div>
</section>
