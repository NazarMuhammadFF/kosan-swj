<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Room::insert([
        ['code'=>'A-01','size_sqm'=>12,'base_price'=>1200000,'has_ac'=>true,'has_private_bath'=>false,'created_at'=>now(),'updated_at'=>now()],
        ['code'=>'A-02','size_sqm'=>10,'base_price'=>1000000,'has_ac'=>false,'has_private_bath'=>false,'created_at'=>now(),'updated_at'=>now()],
        ['code'=>'B-01','size_sqm'=>14,'base_price'=>1500000,'has_ac'=>true,'has_private_bath'=>true,'created_at'=>now(),'updated_at'=>now()],
    ]);
    }
}
