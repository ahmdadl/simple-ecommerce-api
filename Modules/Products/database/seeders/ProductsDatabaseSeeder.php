<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brands\Database\Seeders\BrandsDatabaseSeeder;
use Modules\Brands\Models\Brand;
use Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder;
use Modules\Categories\Models\Category;
use Modules\Products\Models\Product;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create categories
        (new CategoriesDatabaseSeeder())->run();
        (new BrandsDatabaseSeeder())->run();

        $categories = Category::all();
        $brands = Brand::all();

        Product::query()->delete();

        $i = 0;
        while (true) {
            $category = $categories->random();
            $brand = $brands->random();

            Product::factory()->create([
                "category_id" => $category->id,
                "brand_id" => $brand->id,
            ]);

            if (++$i >= 400) {
                break;
            }

            $i++;
        }
    }
}
