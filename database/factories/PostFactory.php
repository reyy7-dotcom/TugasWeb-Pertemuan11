<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'content' => fake()->paragraphs(5, true),
            'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&auto=format&fit=crop&q=80',
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
