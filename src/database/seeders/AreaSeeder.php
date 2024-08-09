<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run()
    {
        DB::table('areas')->insert([
            ['name' => '東京都'],
            ['name' => '大阪府'],
            ['name' => '福岡県'],
            // 他のエリアも追加する場合はここに挿入します
        ]);
    }
}
