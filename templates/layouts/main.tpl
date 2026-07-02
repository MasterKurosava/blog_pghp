<!DOCTYPE html>
<html lang="ru">
<head>
    {include file="partials/head.tpl"}
</head>
<body>
    {include file="partials/header.tpl"}

    <main class="main{if $mainClass} {$mainClass|escape}{/if}">
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
