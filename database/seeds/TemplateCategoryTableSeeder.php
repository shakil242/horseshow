<?php

use Illuminate\Database\Seeder;

class TemplateCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('template_categories')
         	->insert([
     		[
	            'id' => 1,
	            'name' => "Shows",
	        ],[
	            'id' => 2,
	            'name' => "Facility",
	        ],[
	            'id' => 3,
	            'name' => "Horse Template",
	        ],[
                'id' => 4,
                'name' => "Trainer",
            ]
        ]);
    }
}
