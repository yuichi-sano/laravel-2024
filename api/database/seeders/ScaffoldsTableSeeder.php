<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScaffoldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scaffold::create([
            'scaffold_id' => '1',
            'scaffold_name' => 'アルバトロス',
        ]);

        Scaffold::create([
            'scaffold_id' => '2',
            'scaffold_name' => 'クサビ式足場',
        ]);

        Scaffold::create([
            'scaffold_id' => '3',
            'scaffold_name' => '枠組足場',
        ]);

        Scaffold::create([
            'scaffold_id' => '4',
            'scaffold_name' => '単管足場',
        ]);
    }
}
