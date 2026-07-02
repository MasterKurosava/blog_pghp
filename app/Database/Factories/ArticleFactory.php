<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\DTO\CreateArticleData;
use App\Support\SlugGenerator;
use DateTimeImmutable;
use Faker\Generator;

final class ArticleFactory
{
    public function __construct(
        private readonly Generator $faker,
        private readonly SlugGenerator $slugGenerator,
    ) {
    }

    public function make(): CreateArticleData
    {
        $title = $this->generateTitle();
        $slug = $this->slugGenerator->generate($title);
        $imageSeed = $this->faker->unique()->numberBetween(1, 999999);

        return new CreateArticleData(
            title: $title,
            slug: $slug,
            description: $this->faker->paragraph(random_int(2, 3)),
            content: $this->generateContent(),
            image: $this->generateImageUrl($imageSeed),
            views: random_int(
                (int) config('seeder.views_min', 50),
                (int) config('seeder.views_max', 25000),
            ),
            publishedAt: $this->generatePublishedAt(),
        );
    }

    private function generateTitle(): string
    {
        $patterns = [
            fn (): string => $this->faker->sentence(random_int(5, 9)),
            fn (): string => 'Как ' . mb_strtolower($this->faker->sentence(random_int(3, 6)), 'UTF-8'),
            fn (): string => $this->faker->sentence(random_int(3, 5)) . ': ' . $this->faker->sentence(random_int(4, 7)),
            fn (): string => (string) random_int(5, 12) . ' ' . $this->faker->words(random_int(3, 5), true) . ' в ' . $this->faker->word(),
        ];

        $title = $patterns[array_rand($patterns)]();
        $title = rtrim($title, '.!?');

        return mb_strtoupper(mb_substr($title, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($title, 1, null, 'UTF-8');
    }

    private function generateContent(): string
    {
        $sections = [];
        $sections[] = '<p>' . $this->faker->paragraph(random_int(3, 5)) . '</p>';
        $sections[] = '<h2>' . rtrim($this->faker->sentence(random_int(4, 7)), '.') . '</h2>';
        $sections[] = '<p>' . $this->faker->paragraph(random_int(4, 6)) . '</p>';
        $sections[] = '<p>' . $this->faker->paragraph(random_int(3, 5)) . '</p>';

        $sections[] = '<h2>' . rtrim($this->faker->sentence(random_int(3, 6)), '.') . '</h2>';
        $sections[] = '<ul>';

        foreach (range(1, random_int(3, 5)) as $_) {
            $sections[] = '<li>' . rtrim($this->faker->sentence(random_int(6, 12)), '.') . '.</li>';
        }

        $sections[] = '</ul>';
        $sections[] = '<p>' . $this->faker->paragraph(random_int(4, 7)) . '</p>';
        $sections[] = '<h2>' . rtrim($this->faker->sentence(random_int(3, 5)), '.') . '</h2>';
        $sections[] = '<p>' . $this->faker->paragraph(random_int(3, 5)) . '</p>';
        $sections[] = '<p>' . $this->faker->paragraph(random_int(4, 6)) . '</p>';

        return implode("\n", $sections);
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
        $date = $this->faker->dateTimeBetween($period, 'now');

        return (new DateTimeImmutable($date->format('Y-m-d H:i:s')))->format('Y-m-d H:i:s');
    }
}
