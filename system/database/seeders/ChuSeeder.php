<?php

namespace Database\Seeders;

use App\Models\CommunityHealthUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //community health units seeder
        $path = public_path("chu_kirinyaga.json");
        if (file_exists($path)) {
            // Read the file contents
            $json = file_get_contents($path);
    
            // Decode the JSON data into a PHP array
            $chus = json_decode($json, true);
            foreach ($chus as $chu) {
                CommunityHealthUnit::create([
                    "subcounty_id" => $chu["subcounty_id"],
                    "name" => $chu["chu_name"]
                ]);
            }
        
        }
    }
}
