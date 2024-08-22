<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $title = $this->faker->unique()->name();
        $slug = Str::slug($title);

        $subCategories = [30, 29];
        $subCatRandKey = array_rand($subCategories);

        $brand = [1, 2, 3, 4, 5, 6, 7, 8];
        $brandRandKey = array_rand($brand);

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 42,
            "sub_category_id" => $subCategories[$subCatRandKey],
            "brand_id" => $brand[$brandRandKey],
            'sku' => rand(200, 900),
            'price' => rand(200, 900),
            'track_qty' => 'Yes',
            'status' => 1,
        ];
    }
}
