<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@fixit.com',
            'password' => Hash::make('password'),
        ]);

        // Create providers
        $providers = User::factory()->provider()->count(5)->create();

        // Create customers
        User::factory()->count(10)->create();

        // Create categories
        $categories = collect([
            ['name' => 'Home Repair', 'slug' => 'home-repair', 'icon' => 'wrench', 'description' => 'General home repair services'],
            ['name' => 'Tutoring', 'slug' => 'tutoring', 'icon' => 'book', 'description' => 'Academic tutoring services'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'icon' => 'sparkles', 'description' => 'Beauty and personal care services'],
            ['name' => 'Plumbing', 'slug' => 'plumbing', 'icon' => 'droplet', 'description' => 'Plumbing and pipe repair services'],
            ['name' => 'Electrical', 'slug' => 'electrical', 'icon' => 'bolt', 'description' => 'Electrical installation and repair services'],
            ['name' => 'Cleaning', 'slug' => 'cleaning', 'icon' => 'star', 'description' => 'Professional cleaning services'],
        ])->map(fn ($data) => Category::create($data));

        // Create 3 services per provider
        $providers->each(function (User $provider) use ($categories) {
            $randomCategories = $categories->random(3);

            $randomCategories->each(function (Category $category) use ($provider) {
                $title = fake()->words(3, true);

                Service::create([
                    'provider_id' => $provider->id,
                    'category_id' => $category->id,
                    'title' => $title,
                    'slug' => Str::slug($title).'-'.$provider->id.'-'.Str::random(4),
                    'description' => fake()->paragraph(),
                    'price' => fake()->randomFloat(2, 20, 500),
                    'duration_minutes' => fake()->randomElement([30, 60, 90, 120]),
                    'status' => 'active',
                ]);
            });
        });
    }
}
