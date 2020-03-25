<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            	'name' => 'Juan Gómez',
            	'email'=>'go.juangomez23@gmail.com',
            	'password'=>Hash::make('123456'),
            	'id_rol'=>1,
            ]
        ]);
    }
}
