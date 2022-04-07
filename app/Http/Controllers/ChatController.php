<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $role = Auth::user()->role;
        $user_id = Auth::user()->id;
        $chat = Chat::find($id);
        $UserAccessibleFound = $chat->to == $user_id || $chat->from == $user_id;

        if ($role == "admin" || $role == "support" || $UserAccessibleFound) {
            $msgs = Message::where("chat_id", $id)
                ->get()
                ->toArray();
            return [
                "current_page" => +$request->page,
                "last_page" => ceil(sizeof($msgs) / 10),
                "data" => array_reverse(
                    (function () use ($msgs) {
                        $msgs = array_reverse($msgs);
                        $msg_split_array = [];
                        $local_msg_area = [];

                        for ($i = 0; $i < sizeof($msgs); $i++) {
                            array_push($local_msg_area, $msgs[$i]);
                            if (
                                sizeof($local_msg_area) == 10 ||
                                sizeof($msgs) == $i + 1
                            ) {
                                array_push($msg_split_array, $local_msg_area);
                                $local_msg_area = [];
                            }
                        }
                        return $msg_split_array;
                    })()[$request->page - 1]
                ),
            ];
        }
        return [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            "message" => "required",
        ]);
        $message = $request->message;
        $to = $request->to
            ? (User::where("email", $request->to)->first()
                ? User::where("email", $request->to)->first()->id
                : "0")
            : "0";

        $order_id = $request->order_id ? $request->order_id : null;
        $user = Auth::user();

        $chat = Chat::create([
            "from" => $user->id,
            "to" => $to,
            "last_sender" => $user->id,
            "order_id" => $order_id,
        ]);

        Message::create([
            "from" => $user->id,
            "name" => $user->name,
            "message" => $message,
            "chat_id" => $chat->id,
        ]);

        return [
            "success" => true,
        ];
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
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $role = Auth::user()->role;
        $id = Auth::user()->id;
        $chats =
            $role == "admin" || $role == "support"
                ? Chat::all()
                : Chat::where("from", $id)
                    ->orWhere("to", $id)
                    ->get();
        return View("chat", [
            "chats" => $chats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        $role = Auth::user()->role;
        $id = Auth::user()->id;
        $UserAccessibleFound =
            $chat->from == $id || $chat->to == $id ? true : false;

        if ($role == "admin" || $role == "support" || $UserAccessibleFound) {
            $chat->delete();
            Message::where("chat_id", $chat->id)->delete();
        }
    }
}