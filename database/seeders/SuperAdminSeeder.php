<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;

class SuperAdminSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      // Create Company
      $company = Company::create([
         'company_name' => 'HomeZen',
         'company_address' => 'Sama Savli Road, Vadodara, Gujarat, India',
         'status' => 1,
      ]);

      // Create Super Admin
      User::create([
         'first_name' => 'Super',
         'last_name'  => 'Admin',
         'email'      => 'Super@gmail.com',
         'password'   => Hash::make('Super@123'),
         'company_id' => 1,
         'role'       => 1,
         'status'     => 1,
         'phone'      => '9999999999',
      ]);

      // Create Admin
      User::create([
         'first_name' => 'Admin',
         'last_name'  => 'Admin',
         'email'      => 'admin@gmail.com',
         'password'   => Hash::make('Admin@123'),
         'company_id' => 1,
         'role'       => 2,
         'status'     => 1,
         'phone'      => '8888888888',
      ]);

      // Create User
      User::create([
         'first_name' => 'User',
         'last_name'  => 'User',
         'email'      => 'user@gmail.com',
         'password'   => Hash::make('User@123'),
         'company_id' => 1,
         'role'       => 3,
         'status'     => 1,
         'phone'      => '7777777777',
      ]);
   }
}
