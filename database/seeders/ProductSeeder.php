<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    const TABLE_NAME = 'products';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if($this->isTableEmpty()) {
            $faker = Faker::create();
            $file = base_path("database/seeders/data/".self::TABLE_NAME.".csv");

            $records = Helper::importCSV($file);
            if(!$records) {
                Log::info('File not exists');
                return;
            }

            foreach ($records as $record) {
                Product::create([
                    'sku' => $record['sku'] ?? $faker->unique()->name,
                    'name' => $record['name'] ?? $faker->name
                ]);
            }
        }
    }

    private function isTableEmpty(): bool {
        return Helper::isTableEmpty(self::TABLE_NAME);
    }
}
