<?php

namespace App\Http\Controllers;

use App\Models\Bones;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return View("withdraw");
    }
    public function getAll(Request $request)
    {
        $role = Auth::user()->role;

        if ($role === "admin" || $role === "support") {
            if ($request->filter == "pending") {
                return [
                    "data" => Withdraw::where("status", "await")->get(),
                ];
            }
            return [
                "data" => Withdraw::all(),
            ];
        } else {
            return [
                "data" => Withdraw::where("receiver", Auth::user()->id)->get(),
            ];
        }
    }
    public function addWithdraw(Request $request)
    {
        return View("add-withdraw", [
            "withdraw_limit" => Setting::where(
                "name",
                "withdraw_limit"
            )->first(),
        ]);
    }
    public function addWithdrawRequest(Request $request)
    {
        $withdraw = $request->validate([
            "money_needed" => ["required"],
            "type" => ["required"],
            "receiver_details" => ["required", "string"],
        ]);
        $withdraw_limit = (int) Setting::where(
            "name",
            "withdraw_limit"
        )->first()->value;
        $user = User::find(Auth::user()->id);
        $withdraw["money_needed"] = (int) $withdraw["money_needed"];
        if (
            $user->active_balance >= $withdraw["money_needed"] &&
            $withdraw["money_needed"] >= $withdraw_limit
        ) {
            $withdraw["receiver"] = $user->id;
            $withdraw["receiver_name"] = $user->name;
            $withdraw["status"] = "await";
            $user->active_balance =
                $user->active_balance - $withdraw["money_needed"];
            $user->withdraw_balance =
                $user->withdraw_balance + $withdraw["money_needed"];
            $user->save();
            Withdraw::Create($withdraw);
        } else {
            abort(500);
        }
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
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }
    public function GetBonus(User $user)
    {
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw, $status)
    {
        $role = Auth::user()->role;
        $user = User::find($withdraw->receiver);

        if ($role == "admin" || $role == "support") {
            if (
                $withdraw->status != "delivered" &&
                $withdraw->status != "cancelled"
            ) {
                if ($status == "delivered") {
                    $leader = User::find($user->leader_id);
                    if ($leader) {
                        $leader_bones = Bones::where("type", "leader")->get();
                        $leader->leader_balance += $withdraw->money_needed;
                        $leader->save();
                        $leader->refresh();
                        $id = $leader->id;
                        foreach ($leader_bones as $b) {
                            $achievers_list = json_decode($b->achievers)
                                ? json_decode($b->achievers)
                                : [];

                            if (
                                !in_array($id, $achievers_list) &&
                                (int) $leader->leader_balance >=
                                    (int) $b->target
                            ) {
                                array_push($achievers_list, $id);
                                $b->achievers = json_encode($achievers_list);
                                $leader->active_balance += (int) $b->bones;
                                $leader->save();
                                $leader->refresh();
                                $b->save();
                            }
                        }
                    }
                    $user->withdraw_balance =
                        $user->withdraw_balance - $withdraw->money_needed;
                    $user->withdraw_done_balance += $withdraw->money_needed;
                    $user->save();
                    $user->refresh();
                    $bones = Bones::where("type", "normal")->get();
                    $id = $user->id;
                    foreach ($bones as $b) {
                        $achievers_list = json_decode($b->achievers)
                            ? json_decode($b->achievers)
                            : [];

                        if (
                            !in_array($id, $achievers_list) &&
                            (int) $user->withdraw_done_balance >=
                                (int) $b->target
                        ) {
                            array_push($achievers_list, $id);
                            $b->achievers = json_encode($achievers_list);
                            $user->active_balance += (int) $b->bones;
                            $user->save();
                            $user->refresh();
                            $b->save();
                        }
                    }

                    // $this->GetBonus($user->id);
                }
                if ($status == "cancelled") {
                    $user->withdraw_balance =
                        $user->withdraw_balance - $withdraw->money_needed;
                    $user->active_balance =
                        $user->active_balance + $withdraw->money_needed;
                    $user->save();
                }
            }
            $withdraw->update([
                "status" => $status,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        $withdraw->delete();
    }

    // setting
    public function showSetting(Withdraw $withdraw)
    {
        return View("withdraw-setting", [
            "withdraw_limit" => Setting::where(
                "name",
                "withdraw_limit"
            )->first(),
        ]);
    }
    public function updateWithDrawSetting(Request $request)
    {
        $data = (object) $request->validate([
            "withdraw_limit" => "required",
        ]);

        Setting::where("name", "withdraw_limit")->update([
            "value" => $data->withdraw_limit,
        ]);
        return redirect()->back();
    }
}