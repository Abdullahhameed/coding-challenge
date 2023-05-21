<?php

namespace Database\Seeders;

use App\Models\NetworkConnection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        for($i = 0; $i < 30; $i++)
        {
            for($j = 31; $j < 60; $j++)
            {
                NetworkConnection::create([
                    "sender_id" => $users[$i]->id,
                    "status"=> 1,
                    "receiver_id" => $users[$j]->id
                ]);
            }
        }
    }
}
