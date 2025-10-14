<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\DataModels;

class MessageController extends Controller
{
    public function start()
    {
        $messages = DB::table('messages')
            ->join('users', 'messages.sender_id', '=', 'users.id')
            ->select('messages.*', 'users.name as sender_name','users.role')
            ->latest()
            ->take(20)
            ->get()
            ->reverse(); // show oldest first

        $amigoId = session('id'); // or auth()->id() if you use auth
        echo view('partial.header');
        echo view('partial.navbar');
        echo view('chat.index', compact('messages', 'amigoId'));
        echo view('partial.footer');
    }

        public function index()
    {
        $messages = DB::table('messages')
            ->join('users', 'messages.sender_id', '=', 'users.id')
            ->select('messages.*', 'users.name as sender_name','users.role')
            ->latest()
            ->take(20)
            ->get()
            ->reverse(); // show oldest first

        $amigoId = session('id'); // or auth()->id() if you use auth
        return view('chat.index', compact('messages', 'amigoId'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        DB::table('messages')->insert([
            'sender_id' => session('id'), // or however you get current amigo
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }
}
