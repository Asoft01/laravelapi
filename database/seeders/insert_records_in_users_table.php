<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class insert_records_in_users_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'name'=> 'hamid',
                'email'=> 'lekhad19@gmail.com',
                'password'=> bcrypt('123456') 
            ],
            [
                'name'=> 'john',
                'email'=> 'john@gmail.com',
                'password'=> bcrypt('123456') 
            ],
        ];
        User::insert($users);
    }
}
