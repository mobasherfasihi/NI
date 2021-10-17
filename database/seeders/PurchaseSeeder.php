<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class PurchaseSeeder extends Seeder
{
    const TABLE_NAME = 'purchases';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if($this->isTableEmpty()) {
            $faker = Faker::create();
            $file = base_path("database/seeders/data/purchased.csv");

            $records = Helper::importCSV($file);
            if(!$records) {
                Log::info('File not exists');
                return;
            }

            foreach ($records as $record) {
                Purchase::create([
                    'user_id' => $record['user_id'] ?? $faker->unique()->name,
                    'product_sku' => $record['product_sku'] ?? $faker->name
                ]);
            }
        }
    }

    private function isTableEmpty(): bool {
        return Helper::isTableEmpty(self::TABLE_NAME);
    }
}
