<?php

use Illuminate\Database\Seeder;

class classTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('class_types')
            ->insert([
                [
                    'id' => 1,
                    'name' => "Hunter",
                ],[
                    'id' => 2,
                    'name' => "Jumper",
                ],[
                    'id' => 3,
                    'name' => "Equitation",
                ]
            ]);
    }
}
