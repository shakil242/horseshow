<?php

use Illuminate\Database\Seeder;

class FormTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('form_types')
         	->insert([
     		[
	            'id' => 10,
	            'name' => "Trainer Register",
	        ]
        ]);
    }
}
