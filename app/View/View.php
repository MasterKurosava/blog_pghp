<?php

declare(strict_types=1);

namespace App\View;

use Smarty;

final class View
{
    private function __construct(
        private readonly Smarty $smarty,
    ) {
    }

    public static function create(): self
    {
        $smarty = new Smarty();

        $smarty->setTemplateDir(base_path('templates'));
        $smarty->setCompileDir(storage_path('cache/templates/compile'));
        $smarty->setCacheDir(storage_path('cache/templates/cache'));

        $smarty->escape_html = true;
        $smarty->caching = false;

        if (config('app.debug')) {
            $smarty->error_reporting = E_ALL & ~E_WARNING & ~E_NOTICE;
        }

        $smarty->assign('app', view_shared_data());

        register_smarty_plugins($smarty);

        return new self($smarty);
    }

    public function render(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $defaults = [
            'title' => '',
            'metaDescription' => '',
            'robots' => '',
            'canonical' => '',
            'ogType' => '',
            'ogTitle' => '',
            'ogDescription' => '',
            'ogImage' => '',
            'mainClass' => '',
            'fullWidthContent' => false,
        ];

        foreach (array_merge($defaults, $data) as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $content = $this->smarty->fetch("{$template}.tpl");

        if ($layout === null) {
            return $content;
        }

        $this->smarty->assign('content', $content);

        return $this->smarty->fetch("{$layout}.tpl");
    }

    public function share(string $key, mixed $value): void
    {
        $this->smarty->assign($key, $value);
    }
}
