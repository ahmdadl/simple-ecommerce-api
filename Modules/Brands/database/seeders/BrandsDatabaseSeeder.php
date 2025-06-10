<?php

namespace Modules\Brands\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brands\Models\Brand;

class BrandsDatabaseSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            [
                "title" => [
                    "en" => "Optimum Nutrition",
                    "ar" => "أوبتيموم نيوتريشن",
                ],
            ],
            [
                "title" => [
                    "en" => "MuscleTech",
                    "ar" => "ماسلتيك",
                ],
            ],
            [
                "title" => [
                    "en" => "MyProtein",
                    "ar" => "ماي بروتين",
                ],
            ],
            [
                "title" => [
                    "en" => "BSN",
                    "ar" => "بي إس إن",
                ],
            ],
            [
                "title" => [
                    "en" => "Dymatize",
                    "ar" => "ديماتايز",
                ],
            ],
            [
                "title" => [
                    "en" => "Cellucor",
                    "ar" => "سيلكور",
                ],
            ],
            [
                "title" => [
                    "en" => "Grenade",
                    "ar" => "غرينايد",
                ],
            ],
            [
                "title" => [
                    "en" => "RSP Nutrition",
                    "ar" => "آر إس بي نيوتريشن",
                ],
            ],
            [
                "title" => [
                    "en" => "JYM Supplement Science",
                    "ar" => "جيم سابيلمينت ساينس",
                ],
            ],
            [
                "title" => [
                    "en" => "Ghost Lifestyle",
                    "ar" => "غوست لايفستايل",
                ],
            ],
        ];

        Brand::query()->delete();

        foreach ($brands as $brand) {
            Brand::factory()->create($brand);
        }
    }
}
