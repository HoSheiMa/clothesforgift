<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
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
    public function create(Request $request)
    {
        $data = $request->validate([
            "location" => "required",
            "admin" => "required",
            "support" => "required",
            "pagesCoordinator" => "required",
            "leader" => "required",
            "marketer" => "required",
            "seller" => "required",
        ]);
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            Shipping::create($data);
        }
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
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            return View("Shipping", [
                "places" => Shipping::all(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $Shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $Shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $Shipping)
    {
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            $Shipping->delete();
        }
    }
}
