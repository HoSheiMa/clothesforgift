<?php
namespace App\Exports;

use App\Invoice;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdvanceOrder implements FromView
{
    public $selector = "*";
    public function __construct($selector, $type = "id_filter")
    {
        $this->selector = $selector;
        $this->type = $type;
    }
    public function view(): View
    {
        switch($this->type) {
            case "id_filter":
                $orders = Order::whereIn("id", $this->selector)->get();
                break;
            case "status_filter":
                $orders = Order::where("status", $this->selector)->get();
                break;
            case 'status_file':
                $orders = Order::whereIn("id", $this->selector['orders_ids']);
                foreach ($this->selector['phones'] as $phone) {
                    $orders->orWhere("phone", 'LIKE', "%$phone%");
                }
                $orders = $orders->get();

                break;
            }
        return view("exportsOrders", [
            "orders" => $orders,
        ]);
    }
}