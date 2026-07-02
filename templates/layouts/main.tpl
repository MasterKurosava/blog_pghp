<!DOCTYPE html>
<html lang="ru">
<head>
    {include file="partials/head.tpl"}
</head>
<body>
    {include file="partials/header.tpl"}

    <main class="main">
        <div class="container">
            {$content nofilter}
        </div>
    </main>

    {include file="partials/footer.tpl"}
</body>
</html>
