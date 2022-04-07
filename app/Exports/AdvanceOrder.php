<?php
namespace App\Exports;

use App\Invoice;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdvanceOrder implements FromView
{
    public $selector = "*";
    public function __construct($selector)
    {
        $this->selector = $selector;
    }
    public function view(): View
    {
        return view("exportsOrders", [
            "orders" => Order::whereIn("id", $this->selector)->get(),
        ]);
    }
}