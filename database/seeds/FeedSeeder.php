<?php

use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    factory(\App\Models\Feed::class, 2) -> create();
    
    }
}
