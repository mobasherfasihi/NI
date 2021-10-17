<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    const TABLE_NAME = 'users';
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
                User::create([
                    'name' => $record['name'] ?? $faker->name,
                    'email' => $record['email'] ?? $faker->unique()->email(),
                    'password' => bcrypt($record['password'] ?? '12345678')
                ]);
            }
        }
    }

    private function isTableEmpty(): bool {
        return Helper::isTableEmpty(self::TABLE_NAME);
    }
}
