<?php

use Illuminate\Database\Seeder;
use App\User;
class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         $user = new User;
        $user->name = 'Marvin Noche';
        $user->email = 'mnoche@8layertech.com';
        $user->password = bcrypt('123456');
        $user->save();
    }
}
