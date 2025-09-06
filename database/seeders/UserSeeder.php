<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'student_number' => 'administrator',
                'major' => 'admin',
                'sex' => 'F',
                'course' => 'admin',
                'year' => 'admin',
                'password' => bcrypt('admin'),
                'user_role' => 'admin',
            ],
            [
                'name' => 'Santos, Juan Miguel',
                'student_number' => '15-SC-2264',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Reyes, Maria Clara Dela Cruz',
                'student_number' => '15-SC-2121',
                'major' => 'SC_BSIT',
                'sex' => 'F',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Garcia, Jose Antonio',
                'student_number' => '22-SC-3920',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Lopez, Ana Beatriz Fernandez',
                'student_number' => '22-SC-3495',
                'major' => 'SC_BSIT',
                'sex' => 'F',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Mendoza, Carlos Eduardo',
                'student_number' => '22-SC-3108',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Flores, Isabella Rose',
                'student_number' => '22-SC-2816',
                'major' => 'SC_BSIT',
                'sex' => 'F',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Torres, Sofia Mae',
                'student_number' => '22-SC-4004',
                'major' => 'SC_BSIT',
                'sex' => 'F',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Cruz, Diego Alfonso',
                'student_number' => '22-SC-3193',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Morales, Luis Gabriel',
                'student_number' => '22-SC-3247',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Ramos, Carmela Joy',
                'student_number' => '20-SC-1369',
                'major' => 'SC_BSIT',
                'sex' => 'F',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
            [
                'name' => 'Villanueva, Marco Antonio',
                'student_number' => '22-SC-3923',
                'major' => 'SC_BSIT',
                'sex' => 'M',
                'course' => 'CHMBAC',
                'year' => 'THIRD YEAR',
                'password' => bcrypt('123'),
                'user_role' => 'client',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
