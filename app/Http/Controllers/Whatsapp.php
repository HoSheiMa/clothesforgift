<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhatsappRequest;
use App\Models\Note;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class Whatsapp extends Controller
{
    public $to = null;
    public static function invoice($file_name, $order)
    {
        $mpdf = new \Mpdf\Mpdf(["mode" => "utf-8", "format" => "A4"]);
        $mpdf->SetDirectionality("rtl");

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $orders = [$order];
        foreach ($orders as $order) {
            $order->items = $order->items;
            $order->Shipping_to = $order->Shipping_location;
            $order->total = 0;
            $order->totalWithoutShipping = 0;
            foreach ($order->items as $item) {
                $order->total += $item->needed_price * $item->needed;
                $order->totalWithoutShipping +=
                    $item->needed_price * $item->needed;
                $item->last_note = Note::where("order_id", $item->products_id)
                    ->latest()
                    ->first();
            }
            $_id = (int) $order->created_by;
            $order->created_by = User::find($_id)
                ? User::find($_id)->name
                : "User";
            $order->total += $order->Shipping_fees;
            // $order->role = Auth::user()->role;
        }
        $mpdf->WriteHTML(view("invoice-report", ["orders" => $orders]));

        // $mpdf->Output(
        //     storage_path("documents/$file_name"),
        //     \Mpdf\Output\Destination::FILE
        // );
        // return url("/") . "documents/$file_name";
        return base64_encode($mpdf->Output("", "S"));
    }
    public function endPoint($type)
    {
        switch ($type) {
            case "text":
                return "https://api.ultramsg.com/instance4216/messages/chat";
            case "image":
                return "https://api.ultramsg.com/instance4216/messages/image";
            case "document":
                return "https://api.ultramsg.com/instance4216/messages/document";
        }
    }
    public function send_execute($args, $type)
    {
        $https_failed_list = [];
        foreach ($this->to as $to) {
            $h = Http::post(
                $this->endPoint($type),
                array_merge(
                    [
                        "token" => env("ultramsg", "hzxyv9578888bhof"),
                        "to" => "+2$to", // +201234567890
                        "priority" => 1,
                        "referenceId" => "",
                    ],
                    $args
                )
            );
            array_push($https_failed_list, $h->failed());
        }
        $http_class = new class ($https_failed_list) {
            public $https_failed_list;
            public function __construct($https_failed_list)
            {
                $this->https_failed_list = $https_failed_list;
            }
            public function failed()
            {
                if (in_array(true, $this->https_failed_list)) {
                    return true;
                }
                return false;
            }
        };
        return $http_class;
    }
    public function send_invoice($file_name, $order)
    {
        $document_url = $this->invoice($file_name, $order);
        return $this->send_execute(
            [
                "filename" => $file_name,
                "document" => $document_url,
            ],
            "document"
        );
    }
    public function send_file($file_name, $document_url)
    {
        return $this->send_execute(
            [
                "filename" => $file_name,
                "document" => $document_url,
            ],
            "document"
        );
    }
    public function send_message($message_body)
    {
        return $this->send_execute(
            [
                "body" => $message_body,
            ],
            "text"
        );
    }
    public function send_image($caption, $image_url)
    {
        return $this->send_execute(
            [
                "caption" => $caption,
                "image" => $image_url,
            ],
            "image"
        );
    }
    public function send(WhatsappRequest $request, $orders)
    {
        $orders = explode(",", $orders);
        foreach ($orders as $order) {
            $order = Order::find($order);
            $this->to = json_decode($order->phone);
            if (is_null($this->to)) {
                $this->to = $order->phone;
            }
            if (is_null($this->to)) {
                return ["success" => false];
            }
            // ? type of callers
            // - order status change
            // - manually msg send

            // ? type of whatsapp msg contents
            // - text
            // - caption + img
            // - text + invoice_render (pdf)
            // - caption + img + invoice_render (pdf)

            if (
                filter_var($request->invoice, FILTER_VALIDATE_BOOLEAN) == true
            ) {
                $r = $this->send_invoice(
                    "invoice_order_{$order->id}.pdf",
                    $order
                );
                if ($r->failed()) {
                    return ["success" => false];
                }
            }
            if ($request->hasFile("image_file")) {
                $image = base64_encode(
                    file_get_contents($request->file("image_file"))
                );
                $r = $this->send_image($request->body, $image);

                if ($r->failed()) {
                    return ["success" => false];
                }
            }

            // normal message
            if (
                filter_var($request->text_only, FILTER_VALIDATE_BOOLEAN) == true
            ) {
                $r = $this->send_message($request->body);
                if ($r->failed()) {
                    return ["success" => false];
                }
            }
        }
        return ["success" => true];
    }
}