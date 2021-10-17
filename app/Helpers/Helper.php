<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helper {
    public static function importCSV(string $filename, string $delimiter = ','): array|bool
    {
        if(!file_exists($filename) || !is_readable($filename)) return false;

        $header = null;
        $data = array();

        if(($handle = fopen($filename, 'r')) !== false) {
            while(($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public static function isTableEmpty(string $table = null): bool
    {
        if(!is_null($table) && DB::table($table)->count() == 0) return true;

        return false;
    }
}
