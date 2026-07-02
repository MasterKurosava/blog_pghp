<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{$title|escape}</title>
    <style>
        body { font-family: monospace; margin: 2rem; background: #fafafa; color: #1a1a2e; }
        pre { background: #fff; border: 1px solid #e5e7eb; padding: 1rem; overflow: auto; }
        h1 { color: #dc2626; }
    </style>
</head>
<body>
    <h1>{$message|escape}</h1>
    <p><strong>URI:</strong> {$uri|escape}</p>
    <p><strong>Method:</strong> {$method|escape}</p>
    <pre>{$trace|escape}</pre>
</body>
</html>
