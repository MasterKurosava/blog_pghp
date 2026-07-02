<!DOCTYPE html>
<html lang="ru">
<head>
    {include file="partials/head.tpl"}
</head>
<body>
    <a href="#main-content" class="skip-link">Перейти к содержимому</a>

    {include file="partials/header.tpl"}

    <main id="main-content" class="main{if $mainClass} {$mainClass|escape}{/if}" tabindex="-1">
        {if $fullWidthContent}
            {$content nofilter}
        {else}
            <div class="container">
                {$content nofilter}
            </div>
        {/if}
    </main>

    {include file="partials/footer.tpl"}
</body>
</html>
