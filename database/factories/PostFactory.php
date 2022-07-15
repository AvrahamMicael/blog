<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->unique()->sentence();
        return [
            'title' => $title,
            'slug' => strtolower(str_replace(['.', ' '], ['', '-'], $title)),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Post $post) {
            $post->body()->create([
                'order' => 0,
                'type' => 'text',
                'value' => $this->faker->paragraph(),
            ]);
        });
    }
}
