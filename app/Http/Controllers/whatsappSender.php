<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;

class whatsappSender extends Controller
{
    public function index(Request $request) {
        return View('whatsapp_sender');
    }
    public function send(Request $request) {
        $request->validate([
            "file" => "required",
            "message" => "required",
        ]);
        $message = $request->message;
        $attach = $request->attach;
        $image = $request->image;
        $file =  explode("\n", $request->file('file')->get());
        $phones = [];
        foreach ($file as $value) {
            if (str_contains($value, '+2')) {
                $value = str_replace('+2', '', $value);
                array_push($phones, $value);
            } else {
                $o = Order::find($value)->first();
                $_phones = json_decode($o->phone);
                $phones = array_merge($phones, $_phones);
            }
        }
            $w = new Whatsapp();
            // message
            $w->to = $phones;
            $w->send_message($message);
            // attach (optional)
            if ($attach) {
                $filename = 'file'. rand(1000, 1000000). '.' . $attach->extension();
                $fileinbase64 = base64_encode(file_get_contents($attach));
                $w->send_file($filename,$fileinbase64 );
            }
             // image (optional)
             if ($image) {
                $filename = 'file'. rand(1000, 1000000). '.' . $image->extension();
                $fileinbase64 = base64_encode(file_get_contents($image));
                $w->send_image('',$fileinbase64 );
            }
        $request->session()->flash('success');
        return View('whatsapp_sender');
    }
       
}
