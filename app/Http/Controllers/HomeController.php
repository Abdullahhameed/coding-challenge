<?php

namespace App\Http\Controllers;

use App\Models\NetworkConnection;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\QueryService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $type = $request->query('type') ? $request->query('type') : 'suggestions';
        $user = auth()->user();

        $connections = NetworkConnection::where('status', 2)->where(function ($q) use ($user) {
            $q->where('receiver_id', $user->id)
                ->orWhere('sender_id', $user->id);
        })->with(['sender', 'receiver'])->Paginate(10);

        $connectedUsers = NetworkConnection::where('status', 2)->where(function ($q) use ($user) {
            $q->where('receiver_id', $user->id)
                ->orWhere('sender_id', $user->id);
        })->get();

        (new QueryService())->myConnection($connectedUsers, $connections, $user);
        $allConnections = NetworkConnection::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)->get();

        $networkUserIds = (new QueryService())->suggestion($allConnections, $user);

        $suggestions = User::whereNotIn('id', $networkUserIds)->where('id','!=', $user->id)->Paginate(10);
        $sendRequests = NetworkConnection::where('sender_id', $user->id)->with('receiver')->where('status', 1)->Paginate(10);
        $receivedRequests = NetworkConnection::where('receiver_id', $user->id)->with('sender')->where('status', 1)->Paginate(10);

        return view('home', compact("sendRequests", "receivedRequests", "connections", "suggestions", "type"));
    }
}
