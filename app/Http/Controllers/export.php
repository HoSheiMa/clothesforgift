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
    public function advance_export_orders_by_type(Request $request)
    {
        
        if ($request->type == "") {
            return;
        }
        return Excel::download(
            new AdvanceOrder($request->type, 'status_filter'),
            "orders.xlsx"
        );
    }
    public function advance_export_orders_by_file(Request $request)
    {
        if (!$request->hasFile('file')) {
            return;
        }
        $data = explode("\n", $request->file('file')->get());
        $phones = [];
        $orders_ids = [];
        foreach ($data as $value) {
            if (str_contains($value, '+2')) {
                $value = str_replace('+2', '', $value);
                array_push($phones, $value);
            } else {
                array_push($orders_ids, $value);
            }
        }
        $data = array(
            "phones" => $phones,
            "orders_ids" => $orders_ids
        );
        return Excel::download(
            new AdvanceOrder($data, 'status_file'),
            "orders.xlsx"
        );
    }

    
}