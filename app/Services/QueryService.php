<?php

namespace App\Services;

use App\Models\NetworkConnection;
use App\Models\User;

class QueryService
{
    public function suggestion($allConnections, $user)
    {
        $ids = [];
        foreach ($allConnections as $connection) {
            if ($connection->receiver_id != $user->id) {
                array_push($ids, $connection->receiver_id);
            }
            if ($connection->sender_id != $user->id) {
                array_push($ids, $connection->sender_id);
            }
        }

        return $ids;
    }

    public function myConnection($connectedUsers, $connections, $user)
    {
        $connectedUserIds = [];
        foreach ($connectedUsers as $connectedUser) {
            if ($connectedUser->receiver_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->receiver_id);
            }
            if ($connectedUser->sender_id != $user->id) {
                array_push($connectedUserIds, $connectedUser->sender_id);
            }
        }
        foreach ($connections as $connection) {
            $connected_ids = [];
            $userConnections = [];
            if ($connection->receiver_id != $user->id) {
                $userConnections = NetworkConnection::where('sender_id', '!=', $user->id)
                    ->where('receiver_id', '!=', $user->id)->where(function ($q) use ($connection) {
                        $q->where('receiver_id', $connection->receiver_id)
                            ->orWhere('sender_id', $connection->receiver_id);
                    })->where('status', 2)->get();
            }
            if ($connection->sender_id != $user->id) {
                $userConnections = NetworkConnection::where('sender_id', '!=', $user->id)->where('receiver_id', '!=', $user->id)->where(function ($q) use ($connection) {
                    $q->where('receiver_id', $connection->sender_id)
                        ->orWhere('sender_id', $connection->sender_id);
                })->where('status', 2)->get();
            }
            foreach ($userConnections as $userConnection) {
                if (
                    $userConnection->receiver_id != $user->id
                    && ($userConnection->receiver_id != $connection->receiver_id
                        && $userConnection->receiver_id != $connection->sender_id)
                ) {
                    array_push($connected_ids, $userConnection->receiver_id);
                }
                if (
                    $userConnection->sender_id != $user->id
                    && ($userConnection->sender_id != $connection->sender_id
                        && $userConnection->sender_id != $connection->receiver_id)
                ) {
                    array_push($connected_ids, $userConnection->sender_id);
                }
            }

            $commonConnectionIds = array_intersect($connectedUserIds, $connected_ids);
            $connection->commonConnections = User::whereIn('id', $commonConnectionIds)->where('id', '!=', $user->id)->Paginate(10);
        }
    }
}
