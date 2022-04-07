<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\AdvanceOrder;
use Maatwebsite\Excel\Facades\Excel;

class export extends Controller
{
    public function show()
    {
        return view("advanceExport", [
            "orders" => Order::all(),
        ]);
    }
    public function advance_export_orders(Request $request)
    {
        if ($request->selector == "") {
            return;
        }
        $request->selector = explode(",", $request->selector);
        return Excel::download(
            new AdvanceOrder($request->selector),
            "orders.xlsx"
        );
    }
}