<?php

namespace App\Http\Controllers;

use App\Models\colors;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
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
    public function create()
    {
        //
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            "needed" => "required",
            "color" => "required",
        ]);

        $item->refresh();
        // reset the benefits
        $order = Order::find($item->order_id);
        // Refund
        OrderController::payBenefit($request, $order, 0);

        // reset the colors values
        $color = colors::find($item->color_id);
        $color->available = +$color->available + +$item->needed;
        $color->save();
        // refresh the values
        $color->refresh();
        // update the new needed items
        $item->needed = $request->needed;
        $item->color_id = $request->color;
        $Selectedcolor = Colors::find($request->color);
        $item->color = $Selectedcolor->color;
        $item->size = $Selectedcolor->size;
        $Selectedcolor->available -= $request->needed;
        $Selectedcolor->save();
        $item->benefits =
            $request->needed * ($item->needed_price - $item->min_price);
        $item->save();
        // calcuate and pay the new benefits
        OrderController::payBenefit($request, $order, false, false);

        // // reset the create benefits
        // $created_by = User::find($order->created_by);
        // $created_by->pending_balance =
        //     +$created_by->pending_balance - $item->benefits; // remove old benefits
        // $created_by->save();
        // // calc new benefits and add the user again
        // $item->benefits =
        //     $item->needed * ($item->needed_price - $item->min_price);
        // $item->save();
        // $item->refresh();
        // // send new benefits to user
        // $created_by->pending_balance =
        //     +$created_by->pending_balance + $item->benefits; // remove old benefits
        // $created_by->save();
        return $item->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public static function destroy(Request $request, Item $item)
    {
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            $back_items_qauntity = $item->needed;
            $color = colors::find($item->color_id);
            $order = Order::find($item->order_id);
            if ($color) {
                $color->update([
                    "available" => +$color->available + $back_items_qauntity,
                ]);
            }
            $item->delete();
            // delete the order if there no items
            $items = $order->items;
            if (sizeof($items) === 0) {
                $order->delete();
            }
        }
    }
}