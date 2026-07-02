<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\DTO\CreateArticleData;
use App\Support\SlugGenerator;
use DateTimeImmutable;

final class ArticleFactory
{
    private int $titleIndex = 0;

    public function __construct(
        private readonly SlugGenerator $slugGenerator,
    ) {
    }

    public function make(): CreateArticleData
    {
        $titles = config('content.article_titles', []);
        $descriptions = config('content.article_descriptions', []);

        $index = $this->titleIndex % max(1, count($titles));
        $cycle = intdiv($this->titleIndex, max(1, count($titles)));
        $this->titleIndex++;

        $title = $titles[$index] ?? 'Обзор инженерных практик в современных digital-командах';

        if ($cycle > 0) {
            $title .= ' — выпуск ' . ($cycle + 1);
        }
        $description = $descriptions[$index] ?? 'Практический разбор подходов, метрик и инструментов для устойчивой разработки.';

        return new CreateArticleData(
            title: $title,
            slug: $this->slugGenerator->generate($title),
            description: $description,
            content: $this->generateContent(),
            image: $this->generateImageUrl($index + 1),
            views: random_int(
                (int) config('seeder.views_min', 50),
                (int) config('seeder.views_max', 25000),
            ),
            publishedAt: $this->generatePublishedAt(),
        );
    }

    private function generateContent(): string
    {
        $sections = config('content.content_sections', []);
        $parts = [];

        foreach ($sections as $section) {
            $parts[] = '<h2>' . htmlspecialchars((string) ($section['heading'] ?? ''), ENT_QUOTES, 'UTF-8') . '</h2>';

            foreach ($section['paragraphs'] ?? [] as $paragraph) {
                $parts[] = '<p>' . htmlspecialchars((string) $paragraph, ENT_QUOTES, 'UTF-8') . '</p>';
            }

            if (!empty($section['list']) && is_array($section['list'])) {
                $parts[] = '<ul>';

                foreach ($section['list'] as $item) {
                    $parts[] = '<li>' . htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8') . '</li>';
                }

                $parts[] = '</ul>';
            }
        }

        return implode("\n", $parts);
    }

    private function generateImageUrl(int $seed): string
    {
        $width = (int) config('seeder.image_width', 1200);
        $height = (int) config('seeder.image_height', 630);

        return sprintf('https://picsum.photos/seed/blog-%d/%d/%d', $seed, $width, $height);
    }

    private function generatePublishedAt(): string
    {
        $period = (string) config('seeder.publication_period', '-2 years');
        $min = strtotime($period) ?: strtotime('-2 years');
        $max = time();
        $timestamp = random_int($min, $max);

        return (new DateTimeImmutable('@' . $timestamp))->format('Y-m-d H:i:s');
    }
}
