<?php

namespace Database\Seeders;

use App\Models\CommentRecord;
use App\Models\Record;
use App\Models\User;
use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommentRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $item) {
            CommentRecord::create(
                [
                    'uuid' => Str::uuid(),
                    'comment' => Lorem::text(),
                    'user_id' => User::inRandomOrder()->first()->id,
                    'record_id' => Record::inRandomOrder()->first()->id,
                ]
            );
        }
    }
}
