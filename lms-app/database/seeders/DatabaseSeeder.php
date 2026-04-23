<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache role
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // buat role
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'instructor']);
        Role::firstOrCreate(['name' => 'student']);

        // admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.com'],
            [
                'name'     => 'Admin LMS',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles('admin');

        // instructor
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@lms.com'],
            [
                'name'     => 'Instructor Demo',
                'password' => Hash::make('password'),
            ]
        );
        $instructor->syncRoles('instructor');

        $student = User::firstOrCreate(
             ['email' => 'student@lms.com'],
            [
            'name'     => 'Student Demo',
            'password' => Hash::make('password'),
             ]
        );
        $student->syncRoles('student');
    }
}