<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use SplFileInfo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    private static Collection $fixtures;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
            'title' => str(fake()->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL . PHP_EOL),
        ];
    }

    public function withFixture()
    {
        $posts = static::getFixtures()
            ->map(fn (string $contents) => str($contents)->explode("\n", 2))
            ->map(fn (Collection $parts) => [
                'title' => str($parts[0])->trim()->after('# '),
                'body' => str($parts[1])->trim(),
            ]);

        return $this->sequence(...$posts);
    }

    private static function getFixtures(): Collection
    {
        return self::$fixtures ??= collect(File::files(database_path('factories/fixtures/posts')))
            ->map(fn (SplFileInfo $fileInfo) => $fileInfo->getContents());
    }
}
