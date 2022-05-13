<?php

namespace App\Http\Controllers;

use App\Models\colors;
use App\Models\Item;
use App\Models\Note;
use App\Models\Order;
use App\Models\products;
use App\Models\Shipping;
use App\Models\StatusAction;
use App\Models\User;
use Facade\FlareClient\View;
use Faker\Provider\ar_SA\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Order $order)
    {
        $order->delete();
    }
    public function wholesale(Request $request, products $product)
    {
        return View("wholesale-orders", [
            "product" => $product,
        ]);
    }
    public static function getbenefits(
        $request,
        $order,
        $status = 1,
        $pending_balance = 1
    ) {
        $creator = User::find($order->created_by);

        $isUnderLeader = $creator ? $creator->leader_id : false;
        $leader_ratio = $creator ? $creator->leader_ratio : null;

        $benefits = 0;
        $total_without_fees = 0;
        $total = 0;
        foreach ($order->items as $item) {
            $benefits += +$item->benefits;
            $total_without_fees += $item->needed * $item->min_price;
            $total += $item->needed * $item->needed_price;
        }

        // success order
        $total_creator_benefit = 0;
        $total_system_benefit = 0;
        $total_leader_benefit = 0;
        $total_seller_benefit = [];
        $discount = 0;
        // ? dicount note:[ available type is for marketer only ]
        if ($order->discount_type === "marketer") {
            $discount = +$order->discount;
        }

        foreach ($order->items as $item) {
            $item->refresh();
            $benefit = +$item->benefits;
            $creator_benefit = 0;
            $system_benefit = 0;
            $leader_benefit = 0;
            $seller_benefit = 0;

            $system_benefit += +$item->min_price - +$item->price; // full system benefit, [system , seller]

            if ($item->product_created_by !== "SYSTEM") {
                $seller_benefit += +$item->price; // remove helf of system_benefit for seller and stay half for system
            }
            if ($isUnderLeader) {
                $leader_benefit = $benefit * ($leader_ratio / 100);
            }
            $creator_benefit = $benefit - $leader_benefit;

            $total_creator_benefit += $creator_benefit;
            $total_system_benefit += $system_benefit;
            $total_leader_benefit += $leader_benefit;
            array_push($total_seller_benefit, [
                "value" => $seller_benefit,
                "id" => $item->product_created_by,
            ]);
        }
        info("getbenefits : ", [
            $total_creator_benefit,
            $total_system_benefit,
            $total_leader_benefit,
            $total_seller_benefit,
            $discount,
        ]);
        return [
            $total_creator_benefit,
            $total_system_benefit,
            $total_leader_benefit,
            $total_seller_benefit,
            $discount,
        ];
    }
    public static function payBenefit(
        $request,
        $order,
        $pay_to_active = 1,
        $refend_from_pending = 1
    ) {
        /**
         * function pay benefits from order to buyer and system
         * so if products in this order is share item (come from outside system items like seller)
         * so the system will send 50% of benefits to user number 1 in this side
         * support and pagesCoordinator will take 5, 10 dollars for whole order
         * and others types will take benefits from each item that more than normal price
         * like the price is 50 and the item price will 55 so the user will take 5 dollar benefits
         * from this item
         * leader is user have ratio of benefits so maybe the user under leader management the benefits
         * will split to two side the leader ratio and the remains money after minus this ratio
         *
         */

        // add to balance
        // ? SYSTEM
        $creator = User::find($order->created_by);
        $isUnderLeader = !empty($creator) ? $creator->leader_id : false;

        $SYSTEM = User::find(1);
        $order->refresh();
        list(
            $total_creator_benefit,
            $total_system_benefit,
            $total_leader_benefit,
            $total_seller_benefit,
            $discount,
        ) = OrderController::getbenefits(
            $request,
            $order,
            $pay_to_active,
            $refend_from_pending
        );
        info("creator", [$creator]);
        info("data", [
            $total_creator_benefit,
            $total_system_benefit,
            $total_leader_benefit,
            $total_seller_benefit,
            $discount,
        ]);

        if ($SYSTEM) {
            $SYSTEM->refresh();

            // system money
            if ($pay_to_active) {
                $SYSTEM->active_balance =
                    +$SYSTEM->active_balance + $total_system_benefit;
            }
            if ($refend_from_pending) {
                $SYSTEM->pending_balance =
                    +$SYSTEM->pending_balance - $total_system_benefit;
            } else {
                $SYSTEM->pending_balance =
                    +$SYSTEM->pending_balance + $total_system_benefit; // add pending value
            }
            $SYSTEM->save();
        }
        // ?seller
        foreach ($total_seller_benefit as $seller_element) {
            $seller = User::find($seller_element["id"]);
            if (empty($seller)) {
                continue;
            }
            $seller_benefit = $seller_element["value"];
            if ($seller) {
                if ($pay_to_active) {
                    $seller->active_balance =
                        +$seller->active_balance + $seller_benefit;
                }
                if ($refend_from_pending) {
                    $seller->pending_balance =
                        +$seller->pending_balance - $seller_benefit;
                } else {
                    $seller->pending_balance =
                        +$seller->pending_balance + $seller_benefit; // add pending value
                }
                $seller->save();
            }
        }
        // ? leader

        if ($isUnderLeader) {
            $leader = User::find($isUnderLeader);
            if ($leader) {
                if ($pay_to_active) {
                    $leader->active_balance =
                        +$leader->active_balance + $total_leader_benefit; // add free value
                }
                if ($refend_from_pending) {
                    $leader->pending_balance =
                        +$leader->pending_balance - $total_leader_benefit; // remove pending value
                } else {
                    $leader->pending_balance =
                        +$leader->pending_balance + $total_leader_benefit; // add pending value
                }
                $leader->save();
            }
        }
        // ? creator
        if (!empty($creator)) {
            $creator->refresh();

            if ($creator->role === "support") {
                $total_creator_benefit = 5;
            }
            if ($creator->role === "pagesCoordinator") {
                $total_creator_benefit = 10;
            }

            if ($pay_to_active) {
                $creator->active_balance =
                    +$creator->active_balance +
                    ($total_creator_benefit - $discount);
            }
            if ($refend_from_pending) {
                $creator->pending_balance =
                    +$creator->pending_balance -
                    ($total_creator_benefit - $discount);
            } else {
                $creator->pending_balance =
                    +$creator->pending_balance +
                    ($total_creator_benefit - $discount); // add pending value
            }

            $creator->save();
        }
    }
    public function update_status(Request $request, $orders, $status)
    {
        $allowed_status = [
            "new",
            "pending",
            "delay",
            "confirmed",
            "prepared",
            "delivery",
            "delivered",
            "cancelled",
        ];

        if (!in_array($status, $allowed_status)) {
            return;
        }

        $orders = explode(",", $orders);

        foreach ($orders as $order) {
            $order = Order::find($order);
            if (!$order) {
                continue;
            }
            $role = Auth::user()->role;
            if ($role == "admin") {
                if (
                    $order->status != "delivered" &&
                    $order->status != "cancelled"
                ) {
                    if ($status == "delivered") {
                        $this->payBenefit($request, $order, 1);
                    }
                    if ($status == "cancelled") {
                        // ? not needed when item destroy the benefits removed also
                        $this->payBenefit($request, $order, 0);
                        // foreach ($order->items as $_item) {
                        //     ItemController::destroy($request, $_item);
                        // }
                    }
                }
                $order->update([
                    "updated_by" => Auth::user()->id,
                    "status" => $status,
                ]);
            }
            if (
                $role == "support" &&
                !in_array($order->status, ["delivery", "delivered"])
            ) {
                $order->update([
                    "updated_by" => Auth::user()->id,

                    "status" => $status,
                ]);
            }
            if (
                in_array($order->status, ["new", "cancelled"]) &&
                Auth::user()->id == $order->created_by
            ) {
                $this->payBenefit($request, $order, 0);

                $order->update([
                    "updated_by" => Auth::user()->id,

                    "status" => $status,
                ]);
            }
            // status actions
            $order->refresh();
            $new_status = $order->status;
            $s = StatusAction::where([
                "name" => $new_status,
                "status" => "1",
            ])->first();
            if ($s) {
                $w = new Whatsapp();
                $w->to = json_decode($order->phone);
                if (is_null($w->to)) {
                    $w->to = $order->phone;
                }
                $w->send_message($s->message);
                if (filter_var($s->invoice, FILTER_VALIDATE_BOOLEAN) == true) {
                    $w->send_invoice("invoice_order_{$order->id}.pdf", $order);
                }
            }
        }
    }
    public function update_Shipping_status(Request $request, $orders, $status)
    {
        $allowed_status = [
            "delivery",
            "delivered",
            "Partially delivered",
            "Refused to receive",
            "Delayed",
            "returned product",
        ];
        $note = $request->note ? $request->note : "";
        if (!in_array($status, $allowed_status)) {
            return;
        }
        $orders = explode(",", $orders);

        foreach ($orders as $order) {
            $order = Order::find($order);
            if (!$order) {
                continue;
            }
            $role = Auth::user()->role;
            if (
                $role == "Shippingcompany" &&
                !in_array($order->status, ["cancelled", "delivered"])
            ) {
                $order->update([
                    "Shipping_status" => $status,
                    "Shipping_note" => $note,
                    "updated_by" => Auth::user()->id,
                ]);
            }
            if ($role == "admin") {
                $order->update([
                    "Shipping_status" => $status,
                    "Shipping_note" => $note,
                    "updated_by" => Auth::user()->id,
                ]);
            }
        }
    }
    public function add_ShippingCompany(Request $request)
    {
        $ids = explode(",", $request->ids);
        $companyId = $request->companyId;

        foreach ($ids as $key => $id) {
            $order = Order::find($id);
            $order->update([
                "Shipping_company" => $companyId,
                "Shipping_status" => "delivery",
                "updated_by" => Auth::user()->id,
            ]);
        }

        return [
            "success" => true,
        ];
    }
    public function companies()
    {
        $companys = User::where("role", "Shippingcompany")->get();
        $data = [];
        foreach ($companys as $key => $c) {
            $data[$c->id] = $c->name;
        }
        return $data;
    }
    public function invoice(Request $request)
    {
        $ids = explode(",", $request->ids);
        $orders = Order::whereIn("id", $ids)->get();
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
        return view("invoice", [
            "orders" => $orders,
        ]);
    }
    public function isEmpty($arr)
    {
        $empty = false;
        foreach ($arr as $str) {
            $str = preg_replace("/\s+/", " ", $str);
            if (strlen($str) == 0) {
                $empty = true;
                break;
            }
        }
        return $empty;
    }
    public function checkout(Request $request)
    {
        $request->validate([
            "name" => ["required_without:add_for"],
            "address" => ["required"],
            "phone" => ["required"],
            "Shipping_to" => ["required"],
            "orders" => ["required", "array"],
            "orders.*.neededPrice" => ["required", "integer"],
            "orders.*.neededQuantity" => ["required", "integer"],
            "orders.*.productId" => ["required", "integer"],
            "orders.*.sizeId" => ["required", "integer"],
        ]);
        DB::transaction(function () use ($request) {
            abort_if(
                empty($request->add_for) &&
                    ($this->isEmpty($request->phone) ||
                        $this->isEmpty($request->address) ||
                        sizeof($request->address) == 0 ||
                        sizeof($request->phone) == 0),
                500
            );
            if (empty($request->add_for)) {
                $main_order = Order::create([
                    "name" => $request->name,
                    "phone" => json_encode(
                        $request->phone,
                        JSON_UNESCAPED_UNICODE
                    ),
                    "address" => json_encode(
                        $request->address,
                        JSON_UNESCAPED_UNICODE
                    ),
                    "discount" => 0,
                    "created_by" => Auth::user()->id,
                    "status" => "new",
                    "note" => $request->note,
                    "Shipping_to" => $request->Shipping_to,
                    "Shipping_location" => Shipping::find($request->Shipping_to)
                        ->location,
                    "Shipping_fees" => Shipping::find($request->Shipping_to)
                        ->{Auth::user()->role},
                    "Shipping_company" => "",
                    "Shipping_status" => "awaiting",
                    "updated_by" => Auth::user()->id,
                    "discount_type" => "",
                ]);
            } else {
                $main_order = Order::find($request->add_for);
                if (empty($main_order)) {
                    abort(500);
                }
                // refund
                $this->payBenefit($request, $main_order, 0);
                $request->session()->remove("unlocked_orders");
            }
            if (!empty($request->note)) {
                Note::create([
                    "sender" => Auth::user()->id,
                    "order_id" => $main_order->id,
                    "note" => $request->note,
                ]);
            }

            foreach ($request->orders as $key => $order) {
                $order = (object) $order;
                $benefits = 0;
                $product = products::find($order->productId);
                $color_id = $order->sizeId;
                $color = colors::find($color_id);
                if ($color->products_id != $product->id) {
                    // info complex, that mean there bad problem send from front end
                    abort(500);
                }
                $min_price = $product->min_price;
                if (
                    Auth::user()->role === "support" ||
                    Auth::user()->role === "pagesCoordinator"
                ) {
                    if (Auth::user()->role === "support") {
                        $benefits = 5 / sizeof($request->orders);
                    } else {
                        $benefits = 10 / sizeof($request->orders);
                    }
                } else {
                    $benefits =
                        $order->neededQuantity * $order->neededPrice -
                        $order->neededQuantity * $min_price;
                }
                if (!($color->available >= $order->neededQuantity)) {
                    // this items not available
                    // ? reset the order
                    Item::where("order_id", $main_order->id)->delete();
                    $main_order->delete();

                    return [
                        "success" => false,
                        "msg" =>
                            "This item not available now: " .
                            $product->name .
                            ".",
                    ];
                }
                // update color available
                $color->available =
                    (int) $color->available - $order->neededQuantity;
                $color->save();
                // color or size are same in id code
                Item::create([
                    "color_id" => $color_id,
                    "min_price" => $min_price,
                    "price" => $product->price,
                    "max_price" => $product->max_price,
                    "product_created_by" => $product->created_by,
                    "color" => $color->color,
                    "size" => $color->size,
                    "needed_price" => $order->neededPrice,
                    "needed" => $order->neededQuantity,
                    "order_id" => $main_order->id,
                    "benefits" => $benefits,
                    "product_name" => $product->name,
                    "product_image" => $product->icon,
                    "product_id" => $product->id,
                ]);
            }

            // pay
            $this->payBenefit($request, $main_order, false, false);
            // status actions
            $main_order->refresh();
            $new_status = $main_order->status;
            $s = StatusAction::where([
                "name" => $new_status,
                "status" => "1",
            ])->first();
            if ($s) {
                $w = new Whatsapp();
                $w->to = json_decode($main_order->phone);
                if (is_null($w->to)) {
                    $w->to = $main_order->phone;
                }
                $w->send_message($s->message);
                if (filter_var($s->invoice, FILTER_VALIDATE_BOOLEAN) == true) {
                    $w->send_invoice(
                        "invoice_order_{$main_order->id}.pdf",
                        $main_order
                    );
                }
            }
        });

        return [
            "success" => true,
            "msg" => null,
        ];
    }

    public function show(Order $order)
    {
        return View("orders");
    }
    public static function orders(Request $request)
    {
        $filters =
            $request->filter == "1"
                ? [
                    "new",
                    "pending",
                    "confirmed",
                    "delay",
                    "prepared",
                    "delivery",
                    "delivered",
                    "cancelled",
                ]
                : [$request->filter];
        $orders = [];
        /*
            admin
            support
            pagesCoordinator
            leader
            marketer
            seller
            Shippingcompany
        */
        $role = Auth::user()->role;
        switch ($role) {
            case "admin":
                if (empty($request->size)) {
                    $orders = Order::whereIn("status", $filters)->get();
                } else {
                    $orders = Order::whereIn("status", $filters)->get(["id"]);
                }
                break;
            case "support":
                if (empty($request->size)) {
                    $orders = Order::whereIn("status", $filters)->get();
                } else {
                    $orders = Order::whereIn("status", $filters)->get(["id"]);
                }
                break;
            case "pagesCoordinator":
                if (empty($request->size)) {
                    $orders = Order::whereIn("status", $filters)->get();
                } else {
                    $orders = Order::whereIn("status", $filters)->get(["id"]);
                }

                break;
            case "leader":
                $leader_members = User::where(
                    "leader_id",
                    Auth::user()->id
                )->get(["id"]);
                $leader_members_ids = [];
                foreach ($leader_members as $key => $value) {
                    array_push($leader_members_ids, $value->id);
                }
                // add leader id also
                array_push($leader_members_ids, Auth::user()->id);
                if (empty($request->size)) {
                    $orders = Order::whereIn("created_by", $leader_members_ids)
                        ->whereIn("status", $filters)
                        ->get();
                } else {
                    $orders = Order::whereIn("created_by", $leader_members_ids)
                        ->whereIn("status", $filters)
                        ->get(["id"]);
                }
                break;
            case "marketer":
                $hasLeader = Auth::user()->leader_id;

                if ($hasLeader) {
                    $leader_members = User::where(
                        "leader_id",
                        Auth::user()->leader_id
                    )->get("id");
                    $leader_members_ids = [];
                    foreach ($leader_members as $key => $value) {
                        array_push($leader_members_ids, $value->id);
                    }
                    // add leader id also
                    array_push($leader_members_ids, Auth::user()->id);
                    if (empty($request->size)) {
                        $orders = Order::whereIn(
                            "created_by",
                            $leader_members_ids
                        )
                            ->whereIn("status", $filters)
                            ->get();
                    } else {
                        $orders = Order::whereIn(
                            "created_by",
                            $leader_members_ids
                        )
                            ->whereIn("status", $filters)
                            ->get(["id"]);
                    }
                } else {
                    if (empty($request->size)) {
                        $orders = Order::where([
                            ["created_by", "=", Auth::user()->id],
                        ])
                            ->whereIn("status", $filters)
                            ->get();
                    } else {
                        $orders = Order::where([
                            ["created_by", "=", Auth::user()->id],
                        ])
                            ->whereIn("status", $filters)
                            ->get(["id"]);
                    }
                }
                break;
            case "seller":
                if (empty($request->size)) {
                    $orders = Order::where([
                        ["created_by", "=", Auth::user()->id],
                    ])
                        ->whereIn("status", $filters)
                        ->get();
                } else {
                    $orders = Order::where([
                        ["created_by", "=", Auth::user()->id],
                    ])
                        ->whereIn("status", $filters)
                        ->get(["id"]);
                }
                break;
            case "Shippingcompany":
                if (empty($request->size)) {
                    $orders = Order::where([
                        ["Shipping_company", "=", Auth::user()->id],
                    ])
                        ->whereIn("status", $filters)
                        ->get();
                } else {
                    $orders = Order::where([
                        ["Shipping_company", "=", Auth::user()->id],
                    ])
                        ->whereIn("status", $filters)
                        ->get(["id"]);
                }
                break;
        }
        if (empty($request->size)) {
            foreach ($orders as $key => $order) {
                $order->items = $order->items;
                $order->Shipping_to = $order->Shipping_location;
                $order->total = 0;
                $order->totalWithoutShipping = 0;
                foreach ($order->items as $key => $item) {
                    $order->total += $item->needed_price * $item->needed;
                    $order->totalWithoutShipping +=
                        $item->needed_price * $item->needed;
                }
                $order->Shipping_company = $order->Shipping_company
                    ? (User::find($order->Shipping_company)
                        ? User::find($order->Shipping_company)
                        : "لا يوجد اسم")
                    : $order->Shipping_company;
                $_id = (int) $order->created_by;
                $order->created_by = User::find($_id)
                    ? User::find($_id)->name
                    : "";
                $order->updated_by_name = User::find($order->updated_by)
                    ? User::find($order->updated_by)->name
                    : "";
                $order->created_by_email = User::find($_id)
                    ? User::find($_id)->email
                    : "";
                $order->created_id = $_id;
                $order->total += $order->Shipping_fees;
                $order->role = Auth::user()->role;
            }
        }
        return [
            "data" => $orders,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $role = Auth::user()->role;
        $order->items = $order->items;
        foreach ($order->items as $item) {
            $pId = colors::find($item->color_id);
            if ($pId) {
                $pId = $pId->products_id;
                $item->product = products::find($pId);
            }
        }

        $order->notes = $order->notes;
        foreach ($order->notes as $note) {
            $note->sender = User::find($note->sender)
                ? User::find($note->sender)->name
                : "لا يوجد اسم";
        }
        if ($order->Shipping_company) {
            $order->Shipping_company = User::find($order->Shipping_company)
                ? User::find($order->Shipping_company)->name
                : "لا يوجد اسم";
        }
        if (
            $role === "admin" ||
            $role === "support" ||
            Auth::user()->id == $order->created_by
        ) {
            return View("order-edit", [
                "order" => $order,
                "Shippings" => Shipping::all(),
                "role" => $role,
            ]);
        } else {
            abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $role = Auth::user()->role;
        if (
            $role === "admin" ||
            $role === "support" ||
            (Auth::user()->id == $order->created_by && $order->status == "new")
        ) {
            $data = $request->validate([
                "address" => "required",
                "name" => "required",
                "phone" => "required",
                "Shipping_to" => ["required", "integer"],
            ]);
            $discount_role =
                !empty($request->discount) ||
                $order->discount_type === "marketer";
            // refund all money to recreate with new discound change
            if ($discount_role) {
                $this->payBenefit($request, $order, 0);
            }
            $order->update([
                "address" => $data["address"],
                "discount" =>
                    !empty($request->discount) || $request->discount == "0"
                        ? $request->discount
                        : $order->discount,
                "discount_type" => $request->discount_type
                    ? $request->discount_type
                    : $order->discount_type,
                "name" => $data["name"],
                "phone" => $data["phone"],
                // "Shipping_to" => $data["Shipping_to"],
                "updated_by" => Auth::user()->id,
            ]);
            $creator = User::find($order->created_by);
            $s = Shipping::find($request->Shipping_to);

            $fees = $s ? $s->{$creator->role} : "0.00";
            $order->update([
                "Shipping_to" => $request->Shipping_to,
                "Shipping_status" => "delivery",
                "Shipping_location" => $s ? $s->location : "غير محدد",
                "Shipping_fees" => $fees,
                "updated_by" => Auth::user()->id,
            ]);
            // refund all money to recreate with new discound change
            $order->refresh();
            if ($discount_role) {
                $this->payBenefit($request, $order, 0, 0);
            }

            if ($request->note) {
                Note::create([
                    "sender" => Auth::user()->id,
                    "order_id" => $order->id,
                    "note" => $request->note,
                ]);
            }
            return $order->refresh();
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function unlock(Request $request, Order $order)
    {
        abort_if(empty($order), 500);
        if (
            (Auth::user()->id == $order->created_by &&
                $order->status == "new") ||
            Auth::user()->role == "admin" ||
            Auth::user()->role == "support"
        ) {
            $list = json_decode(
                $request->session()->get("unlocked_orders", "[]")
            );
            if (!in_array($order->id, $list)) {
                array_unshift($list, $order->id);
            }
            $request->session()->put("unlocked_orders", json_encode($list));
        }
    }
}
