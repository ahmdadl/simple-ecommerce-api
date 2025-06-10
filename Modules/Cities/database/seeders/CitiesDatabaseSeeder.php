<?php

namespace Modules\Cities\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cities\Models\City;

class CitiesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $citiesByGovernorate = [
            "Alexandria" => [
                ["en" => "Alexandria", "ar" => "الإسكندرية"],
                ["en" => "Borg El Arab", "ar" => "برج العرب"],
                ["en" => "Montaza", "ar" => "المنتزه"],
                ["en" => "El Amreya", "ar" => "العامرية"],
                ["en" => "Agami", "ar" => "العجمي"],
            ],
            "Aswan" => [
                ["en" => "Aswan", "ar" => "أسوان"],
                ["en" => "Kom Ombo", "ar" => "كوم أمبو"],
                ["en" => "Edfu", "ar" => "إدفو"],
                ["en" => "Abu Simbel", "ar" => "أبو سمبل"],
                ["en" => "Daraw", "ar" => "دراو"],
            ],
            "Asyut" => [
                ["en" => "Asyut", "ar" => "أسيوط"],
                ["en" => "Abnub", "ar" => "أبنوب"],
                ["en" => "Manfalut", "ar" => "منفلوط"],
                ["en" => "Dairut", "ar" => "ديروط"],
                ["en" => "El Qusiya", "ar" => "القوصية"],
            ],
            "Beheira" => [
                ["en" => "Damanhur", "ar" => "دمنهور"],
                ["en" => "Kafr El Dawwar", "ar" => "كفر الدوار"],
                ["en" => "Rosetta", "ar" => "رشيد"],
                ["en" => "Edku", "ar" => "إدكو"],
                ["en" => "Abu Hummus", "ar" => "أبو حمص"],
                ["en" => "Hosh Issa", "ar" => "حوش عيسى"],
            ],
            "Beni Suef" => [
                ["en" => "Beni Suef", "ar" => "بني سويف"],
                ["en" => "El Wasta", "ar" => "الواسطى"],
                ["en" => "Nasser", "ar" => "ناصر"],
                ["en" => "Beba", "ar" => "ببا"],
                ["en" => "Fashn", "ar" => "الفشن"],
            ],
            "Cairo" => [
                ["en" => "Cairo", "ar" => "القاهرة"],
                ["en" => "Nasr City", "ar" => "مدينة نصر"],
                ["en" => "Heliopolis", "ar" => "مصر الجديدة"],
                ["en" => "Maadi", "ar" => "المعادي"],
            ],
            "Dakahlia" => [
                ["en" => "Mansoura", "ar" => "المنصورة"],
                ["en" => "Mit Ghamr", "ar" => "ميت غمر"],
                ["en" => "Talkha", "ar" => "طلخا"],
                ["en" => "Belqas", "ar" => "بلقاس"],
                ["en" => "Sherbin", "ar" => "شربين"],
                ["en" => "Dikirnis", "ar" => "دكرنس"],
            ],
            "Damietta" => [
                ["en" => "Damietta", "ar" => "دمياط"],
                ["en" => "Ras El Bar", "ar" => "رأس البر"],
                ["en" => "Faraskur", "ar" => "فارسكور"],
                ["en" => "Kafr Saad", "ar" => "كفر سعد"],
                ["en" => "Zarqa", "ar" => "الزرقا"],
            ],
        ];

        City::query()->delete();

        foreach ($citiesByGovernorate as $governorate => $cities) {
            foreach ($cities as $city) {
                City::create([
                    "title" => $city,
                ]);
            }
        }
    }
}
