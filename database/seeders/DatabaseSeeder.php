<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        /* $date = date_create('2022-01-01');
        for($i=0; $i < 10000; $i++) 
        {
            $kw = date("W o", $date->getTimestamp());
            $temp = 'KW ' . $kw;
            DB::table('wochen')->insert([
                'name' => $temp,
            ]);
            $date->modify('+7 day');
        } */
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
