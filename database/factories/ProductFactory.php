<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\TaxRate;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $cost = $this->faker->randomFloat(2, 5, 100);
        $price = $cost * $this->faker->randomFloat(2, 1.2, 2.0); // Price is 1.2x to 2x cost

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'code' => $this->faker->unique()->ean13(),
            'type' => ProductTypeEnum::STANDARD->value,
            'description' => $this->faker->sentence(),
            'cost' => $cost,
            'price' => $price,
            'category_id' => Category::inRandomOrder()->first()?->id,
            'brand_id' => Brand::inRandomOrder()->first()?->id,
            'sale_unit_id' => Unit::whereNull('base_unit_id')->inRandomOrder()->first()?->id, // Use base units for simplicity
            'purchase_unit_id' => Unit::whereNull('base_unit_id')->inRandomOrder()->first()?->id,
            'tax_rate_id' => TaxRate::inRandomOrder()->first()?->id,
            'tax_method' => $this->faker->randomElement(['inclusive', 'exclusive']),
            'is_active' => true,
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
