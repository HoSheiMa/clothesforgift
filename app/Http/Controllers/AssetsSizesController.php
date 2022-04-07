<?php

namespace App\Http\Controllers;

use App\Models\assets_sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetsSizesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "size" => "required",
        ]);
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            assets_sizes::create($data);
        }
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
            return View("sizes", [
                "sizes" => assets_sizes::all(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(assets_sizes $size)
    {
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            $size->delete();
        }
    }
}
