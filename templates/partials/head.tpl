<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{if $title}{$title|escape} — {/if}{$app.name|escape}</title>
<meta name="description" content="{if $metaDescription}{$metaDescription|escape}{else}{$app.defaultMetaDescription|escape}{/if}">
{if $robots}<meta name="robots" content="{$robots|escape}">{/if}
{if $canonical}<link rel="canonical" href="{$canonical|escape}">{/if}

<meta property="og:type" content="{if $ogType}{$ogType|escape}{elseif $ogImage}article{else}website{/if}">
<meta property="og:site_name" content="{$app.name|escape}">
<meta property="og:url" content="{if $canonical}{$canonical|escape}{else}{$app.url|escape}{/if}">
{if $ogTitle}<meta property="og:title" content="{$ogTitle|escape}">{else}<meta property="og:title" content="{if $title}{$title|escape} — {/if}{$app.name|escape}">{/if}
{if $ogDescription}<meta property="og:description" content="{$ogDescription|escape}">{else}<meta property="og:description" content="{if $metaDescription}{$metaDescription|escape}{else}{$app.defaultMetaDescription|escape}{/if}">{/if}
{if $ogImage}<meta property="og:image" content="{$ogImage|escape}">{/if}

<meta name="twitter:card" content="{if $ogImage}summary_large_image{else}summary{/if}">
<meta name="twitter:title" content="{if $ogTitle}{$ogTitle|escape}{elseif $title}{$title|escape} — {$app.name|escape}{else}{$app.name|escape}{/if}">
<meta name="twitter:description" content="{if $ogDescription}{$ogDescription|escape}{elseif $metaDescription}{$metaDescription|escape}{else}{$app.defaultMetaDescription|escape}{/if}">
{if $ogImage}<meta name="twitter:image" content="{$ogImage|escape}">{/if}

<meta name="theme-color" content="#4f46e5">
<link rel="icon" href="{asset path='icons/favicon.svg'}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{asset path='icons/apple-touch-icon.svg'}">
<link rel="manifest" href="{asset path='manifest.webmanifest'}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{asset path='css/main.css'}">
<script src="{asset path='js/main.js'}" defer></script>
