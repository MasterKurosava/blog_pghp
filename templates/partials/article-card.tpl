<article class="article-card">
    <a href="{$article.url|escape}" class="article-card__media">
        <img
            src="{$article.image|escape}"
            alt="{$article.title|escape}"
            class="article-card__image"
            loading="lazy"
            width="400"
            height="225"
        >
    </a>

    <div class="article-card__body">
        <span class="badge">{$article.category|escape}</span>

        <h3 class="article-card__title">
            <a href="{$article.url|escape}" class="article-card__title-link">
                {$article.title|escape}
            </a>
        </h3>

        {if $article.description}
            <p class="article-card__description">{$article.description|escape}</p>
        {/if}

        <div class="article-card__meta">
            <time class="article-card__date" datetime="{$article.published_at|escape}">
                {$article.published_at|escape}
            </time>
            <span class="article-card__views">{$article.views|escape} просмотров</span>
        </div>

        <a href="{$article.url|escape}" class="btn btn--text article-card__read">
            Читать
            <span class="btn__arrow" aria-hidden="true">→</span>
        </a>
    </div>
</article>
