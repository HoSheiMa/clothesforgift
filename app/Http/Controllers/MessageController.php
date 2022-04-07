<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Chat $chat)
    {
        $role = Auth::user()->role;
        $id = Auth::user()->id;
        $UserAccessibleFound =
            $chat->from == $id || $chat->to == $id ? true : false;
        if ($role == "admin" || $role == "support" || $UserAccessibleFound) {
            $data = (object) $request->validate([
                "message" => "required",
            ]);
            $user = Auth::user();
            $chat->update([
                "last_sender" => $id,
            ]);
            return Message::create([
                "from" => $user->id,
                "name" => $user->name,
                "message" => $data->message,
                "chat_id" => $chat->id,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $role = Auth::user()->role;
        $chat = Chat::find($message->chat_id);
        $id = Auth::user()->id;
        $UserAccessibleFound =
            $chat->from == $id || $chat->to == $id ? true : false;
        if ($role == "admin" || $role == "support" || $UserAccessibleFound) {
            $message->delete();
        }
    }
}