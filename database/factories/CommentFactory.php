<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'post_id' => Post::factory(), 
            'comment' => $this->faker->sentence(10), 
            'parent_id' => null, 
        ];
    }
    public function reply()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Comment::factory(), 
            ];
        });
    }
}
