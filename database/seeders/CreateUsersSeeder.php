<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'is_admin' => '1',
                'password' => Hash::make('12345')
            ],
            [
                'name' => 'Dushan',
                'email' => 'dushan@gmail.com',
                'is_admin' => '0',
                'password' => Hash::make('dushan123')
            ]
        ];
        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}
