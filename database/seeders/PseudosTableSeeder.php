<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PseudosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pseudos')->insert([
            ['name' => 'ShadowHunter'],
            ['name' => 'CyberNinja'],
            ['name' => 'PixelWarrior'],
            ['name' => 'NeoSpectre'],
            ['name' => 'GlitchMaster'],
            ['name' => 'QuantumGhost'],
            ['name' => 'DarkPhantom'],
            ['name' => 'HyperNova'],
            ['name' => 'ZeroGravity'],
            ['name' => 'CodeWizard'],
        ]);
    }
}
