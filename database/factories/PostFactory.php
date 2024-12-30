<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model =Post::class;
    use WithFaker;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'body' => $this->faker->text
        ];
    }
}
