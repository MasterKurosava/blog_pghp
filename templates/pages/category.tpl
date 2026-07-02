<section class="welcome">
    <h1 class="welcome__title">{$heading|escape}</h1>
    {if isset($slug)}
        <p class="welcome__text">Категория: {$slug|escape}</p>
    {else}
        <p class="welcome__text">Список категорий в разработке.</p>
    {/if}
</section>
