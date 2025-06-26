<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(MFLSeeder::class);
        // $this->call(PatientSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(CountySeeder::class);
        // $this->call(SubcountySeeder::class);
        $this->call(ChuSeeder::class);
        $this->call(ConceptsSeeder::class);
        $this->call(MappingsSeeder::class);
        // $this->call(NhddSeeder::class);
//        $this->call(ReferralSeeder::class);
        $this->call(TestUserSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
    }
}
