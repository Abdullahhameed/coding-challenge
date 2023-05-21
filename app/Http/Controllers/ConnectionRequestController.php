<?php

namespace App\Http\Controllers;

use App\Models\NetworkConnection;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\QueryService;

class ConnectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $type = $request->query('type') ? $request->query('type') : 'suggestions';
            $user = auth()->user();

            $userId = $request->query('user_id') ? $request->query('user_id') : 0;
            if ($type == 'suggestions') 
            {
                $allConnections = NetworkConnection::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)->get();
                $connectedUserIds = (new QueryService())->suggestion($allConnections, $user);
                $data = User::whereNotIn('id', $connectedUserIds)->where('id', '!=', $user->id)->Paginate(10);
                return $data;
            }
            if ($type == 'sent') 
            {
                return NetworkConnection::where('sender_id', $user->id)->with('receiver')->where('status', 1)->Paginate(10);
            }
            if ($type == 'received') 
            {
                return NetworkConnection::where('receiver_id', $user->id)->with('sender')->where('status', 1)->Paginate(10);
            }
            if ($type == 'connections') 
            {
                $connections = NetworkConnection::where(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id);
                })->with(['sender', 'receiver'])->where('status', 2)->Paginate(10);

                $connectedUsers = NetworkConnection::where(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id);
                })->with(['sender', 'receiver'])->where('status', 2)->get();

                (new QueryService())->myConnection($connectedUsers, $connections, $user);

                return response()->json($connections);
            }
            if ($type == 'common-connections') 
            {
                $connectedUsers = NetworkConnection::where(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id);
                })->where('status', 2)->get();
                $connectedUserIds = [];
                foreach ($connectedUsers as $connectedUser) 
                {
                    if ($connectedUser->receiver_id != $user->id) {
                        array_push($connectedUserIds, $connectedUser->receiver_id);
                    }
                    if ($connectedUser->sender_id != $user->id) {
                        array_push($connectedUserIds, $connectedUser->sender_id);
                    }
                }
                $connected_ids = [];
                $userConnections = NetworkConnection::where('sender_id', '!=', $user->id)->where('receiver_id', '!=', $user->id)->where(function ($q) use ($userId) {
                    $q->where('receiver_id', $userId)
                        ->orWhere('sender_id', $userId);
                })->where('status', 2)->get();
                foreach ($userConnections as $userConnection) 
                {
                    if ($userConnection->receiver_id != $user->id && $userConnection->receiver_id != $userId) {
                        array_push($connected_ids, $userConnection->receiver_id);
                    }
                    if ($userConnection->sender_id != $user->id && $userConnection->sender_id != $userId) {
                        array_push($connected_ids, $userConnection->sender_id);
                    }
                }

                $commonConnectionIds = array_intersect($connectedUserIds, $connected_ids);
                $data = User::whereIn('id', $commonConnectionIds)->where('id', '!=', $user->id)->Paginate(10);
                return $data;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $receiverId = $request->input('id');
            NetworkConnection::create([
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'status' => 1
            ]);
            return redirect()->route('home');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            NetworkConnection::where('id', $id)->update(['status' => 2]);
            return "Data Updated Successfully";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            NetworkConnection::where('id', $id)->delete();
            return "Data Deleted Successfully";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
