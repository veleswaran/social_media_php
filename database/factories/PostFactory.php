<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'content' => $this->faker->sentence(10), 
            'file' => $this->faker->randomElement([null, $this->generateFakeFile()]),
        ];

    }
    private function generateFakeFile(): string
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('post_image.jpg');
        return $file->store('uploads/posts', 'public');
    }
}
