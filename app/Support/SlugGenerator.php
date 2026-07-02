<?php

declare(strict_types=1);

namespace App\Support;

final class SlugGenerator
{
    private array $used = [];

    public function generate(string $title): string
    {
        $base = $this->slugify($title);

        if ($base === '') {
            $base = 'item';
        }

        return $this->makeUnique($base);
    }

    public function register(string $slug): string
    {
        $normalized = $this->slugify($slug);

        if ($normalized === '') {
            $normalized = $this->makeUnique('item');

            return $normalized;
        }

        return $this->makeUnique($normalized);
    }

    private function makeUnique(string $base): string
    {
        $slug = $base;
        $suffix = 1;

        while (isset($this->used[$slug])) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        $this->used[$slug] = true;

        return $slug;
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = str_replace(['ё', 'Ё'], ['е', 'е'], $text);

        if (function_exists('transliterator_transliterate')) {
            $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text) ?: $text;
        } else {
            $text = $this->transliterateManual($text);
        }

        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        $text = trim($text, '-');

        return $text;
    }

    private function transliterateManual(string $text): string
    {
        $map = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
            'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y',
            'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        $result = '';

        foreach (mb_str_split($text, 1, 'UTF-8') as $char) {
            $result .= $map[$char] ?? $char;
        }

        return $result;
    }
}
