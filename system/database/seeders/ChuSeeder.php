<?php

namespace Database\Seeders;

use App\Models\CommunityHealthUnit;
use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ChuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path("chu_kirinyaga.json");
        
        if (File::exists($path)) {
            $json = File::get($path);
            $chus = json_decode($json, true);
            
            // Get some facility IDs to associate with
            $facilityIds = Facility::pluck('id')->toArray();
            
            if (empty($facilityIds)) {
                $this->command->info('No facilities found. Skipping CHU seeder.');
                return;
            }
            
            foreach ($chus as $chu) {
                CommunityHealthUnit::create([
                    "facility_id" => $facilityIds[array_rand($facilityIds)],
                    "subcounty_id" => $chu["subcounty_id"],
                    "name" => $chu["chu_name"]
                ]);
            }
        } else {
            $this->command->info('chu_kirinyaga.json not found. Skipping CHU seeder.');
        }
    }
}
