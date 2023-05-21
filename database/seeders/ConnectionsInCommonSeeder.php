<?php

namespace Database\Seeders;

use App\Models\NetworkConnection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectionsInCommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        for($i = 0; $i < 40; $i++)
        {
            for($j = 50; $j < 100; $j++)
            {
                if($i != $j)
                {
                    NetworkConnection::create([
                        "sender_id" => $users[$i]->id,
                        "status" => 2,
                        "receiver_id" => $users[$j]->id
                    ]);
                }  
            }
        }
        for($i = 70; $i < 80; $i++)
        {
            for($j = 80; $j < 100; $j++)
            {
                if($i != $j)
                {
                    NetworkConnection::create([
                        "sender_id" => $users[$i]->id,
                        "status" => 2,
                        "receiver_id" => $users[$j]->id
                    ]);
                }  
            }
        }
    }
}
