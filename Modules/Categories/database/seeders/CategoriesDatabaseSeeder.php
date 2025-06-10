<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Categories\Models\Category;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "title" => [
                    "en" => "Protein Powders",
                    "ar" => "جزر البروتين",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Pre-Workout",
                    "ar" => "قبل العملية",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Post-Workout",
                    "ar" => "بعد العملية",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Vitamins",
                    "ar" => "الفيتامينات",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Minerals",
                    "ar" => "المعادن",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Amino Acids",
                    "ar" => "الأمينواكيدات",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
            [
                "title" => [
                    "en" => "Fat Burners",
                    "ar" => "الفات بيرنرز",
                ],
                "image" => "https://picsum.photos/400/300?random=1",
            ],
        ];

        Category::query()->delete();

        foreach ($categories as $category) {
            Category::factory()->create($category);
        }
    }
}
