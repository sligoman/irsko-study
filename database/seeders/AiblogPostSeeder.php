<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AiblogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $model = '\\Sligoman\\AiblogApiWeb\\Models\\AiblogPost';

        if (! class_exists($model)) {
            $this->command->warn('AiblogPost model not found: ' . $model);
            return;
        }

        // Create some sample posts via factory
        $model::factory()->count(12)->create();
        $this->command->info('Seeded aiblog posts');
    }
}
