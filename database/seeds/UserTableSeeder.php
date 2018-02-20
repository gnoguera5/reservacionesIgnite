<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name='admin';
        $user->email='demo@gmail.com';
        $user->password= bcrypt('abc123');
        $user->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'admin'; // optional
        $admin->description  = 'Usuario admin acceso total'; // optional
        $admin->save();

        $client = new Role();
        $client->name         = 'client';
        $client->display_name = 'client'; // optional
        $client->description  = 'Usuario cliente podra hacer reservas'; // optional
        $client->save();

        $user->attachRole($admin);
    }
}
