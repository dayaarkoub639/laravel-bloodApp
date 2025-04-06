<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupages = [
            ['type' => 'A+', 'description' => 'Groupe sanguin A positif'],
            ['type' => 'A-', 'description' => 'Groupe sanguin A négatif'],
            ['type' => 'B+', 'description' => 'Groupe sanguin B positif'],
            ['type' => 'B-', 'description' => 'Groupe sanguin B négatif'],
            ['type' => 'AB+', 'description' => 'Groupe sanguin AB positif'],
            ['type' => 'AB-', 'description' => 'Groupe sanguin AB négatif'],
            ['type' => 'O+', 'description' => 'Groupe sanguin O positif'],
            ['type' => 'O-', 'description' => 'Groupe sanguin O négatif'],
        ];

        foreach ($groupages as $groupage) {
            DB::table('groupage')->insert([
                'type' => $groupage['type'],
                'description' => $groupage['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}