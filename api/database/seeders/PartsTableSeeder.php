<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Part::create([
            'part_code' => '39000',
            'part_name' => '万能板　ＦＲＰ直線',
        ]);

        Part::create([
            'part_code' => '39001',
            'part_name' => '朝顔鋼製補助板',
        ]);

        Part::create([
            'part_code' => '39010',
            'part_name' => 'フレームＬ',
        ]);

        Part::create([
            'part_code' => '39011',
            'part_name' => 'フレームＲ',
        ]);

        Part::create([
            'part_code' => '39020',
            'part_name' => '万能板受け　上　1829',
        ]);

        Part::create([
            'part_code' => '39021',
            'part_name' => '万能板受け　上　1524',
        ]);
        
        Part::create([
            'part_code' => '39022',
            'part_name' => '万能板受け　上　1219',
        ]);

        Part::create([
            'part_code' => '39023',
            'part_name' => '万能板受け　上　914',
        ]);

        Part::create([
            'part_code' => '39024',
            'part_name' => '万能板受け　上　610',
        ]);

        Part::create([
            'part_code' => '39012',
            'part_name' => '万能板受け　下　1829',
        ]);

        Part::create([
            'part_code' => '39013',
            'part_name' => '万能板受け　下　1524',
        ]);

        Part::create([
            'part_code' => '39014',
            'part_name' => '万能板受け　下　1219',
        ]);

        Part::create([
            'part_code' => '39015',
            'part_name' => '万能板受け　下　914',
        ]);

        Part::create([
            'part_code' => '39016',
            'part_name' => '万能板受け　下　610',
        ]);

        Part::create([
            'part_code' => '39040',
            'part_name' => '万能板押さえ　1829',
        ]);

        Part::create([
            'part_code' => '39041',
            'part_name' => '万能板押さえ　1524',
        ]);

        Part::create([
            'part_code' => '39042',
            'part_name' => '万能板押さえ　1219',
        ]);

        Part::create([
            'part_code' => '39043',
            'part_name' => '万能板押さえ　914',
        ]);

        Part::create([
            'part_code' => '39044',
            'part_name' => '万能板押さえ　610',
        ]);

        Part::create([
            'part_code' => '39050',
            'part_name' => '振れ止め　1829',
        ]);

        Part::create([
            'part_code' => '39051',
            'part_name' => '振れ止め　1524',
        ]);

        Part::create([
            'part_code' => '39052',
            'part_name' => '振れ止め　1219',
        ]);

        Part::create([
            'part_code' => '39053',
            'part_name' => '振れ止め　914',
        ]);

        Part::create([
            'part_code' => '39054',
            'part_name' => '振れ止め　610',
        ]);

        Part::create([
            'part_code' => '39060',
            'part_name' => 'フレーム受金具',
        ]);

        Part::create([
            'part_code' => '39061',
            'part_name' => '斜材受金具',
        ]);

        Part::create([
            'part_code' => '39062',
            'part_name' => '斜材',
        ]);

        Part::create([
            'part_code' => '39070',
            'part_name' => '朝顔ＦＲＰ万能板大',
        ]);

        Part::create([
            'part_code' => '39071',
            'part_name' => '朝顔ＦＲＰ万能板中',
        ]);

        Part::create([
            'part_code' => '39072',
            'part_name' => '朝顔ＦＲＰ万能板小',
        ]);

        Part::create([
            'part_code' => '39080',
            'part_name' => '隅サイドフレームＬ',
        ]);

        Part::create([
            'part_code' => '39081',
            'part_name' => '隅サイドフレームＲ',
        ]);

        Part::create([
            'part_code' => '39082',
            'part_name' => '隅センターフレーム',
        ]);

        Part::create([
            'part_code' => '39083',
            'part_name' => '隅万能板　押え上',
        ]);

        Part::create([
            'part_code' => '39084',
            'part_name' => '隅振れ止めＡ',
        ]);

        Part::create([
            'part_code' => '39085',
            'part_name' => '隅振れ止めＢ',Ｂ
        ]);

        Part::create([
            'part_code' => '39086',
            'part_name' => '隅フレーム受け金具',
        ]);

        Part::create([
            'part_code' => '39087',
            'part_name' => '隅斜材受け金具',
        ]);

        Part::create([
            'part_code' => '39088',
            'part_name' => '妻側フレーム受け金具',
        ]);

        Part::create([
            'part_code' => '39089',
            'part_name' => '妻側斜材受け金具',
        ]);

        Part::create([
            'part_code' => '39063',
            'part_name' => 'クサビ足場用フレーム受け金具',
        ]);

        Part::create([
            'part_code' => '39064',
            'part_name' => 'クサビ足場用斜材受け金具',
        ]);

        Part::create([
            'part_code' => '39090',
            'part_name' => 'クサビ足場用隅斜材受け金具',
        ]);

        Part::create([
            'part_code' => '39091',
            'part_name' => 'クサビ足場用隅フレーム受け金具',
        ]);

        Part::create([
            'part_code' => '39099',
            'part_name' => '朝顔設置用ロープ',
        ]);
    }
}
