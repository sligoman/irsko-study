<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AiblogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Sligoman\AiblogApiWeb\Models\AiblogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(6);
        return [
            'title' => $title,
            'slug' => 
                \Illuminate\Support\Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'excerpt' => $this->faker->paragraph(2),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(4)) . '</p>',
            'featured_image' => null,
            'status_id' => 1,
            'type_id' => 1,
            'scheduled_at' => now(),
            'user_id' => 1,
        ];
    }
}
