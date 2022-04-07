<?php

namespace App\Http\Controllers;

use App\Models\assets_colors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetsColorsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "color" => "required",
        ]);
        $data["color_code"] = "";
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            assets_colors::create($data);
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
            return View("colors", [
                "colors" => assets_colors::all(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $Shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(assets_colors $color)
    {
        $role = Auth::user()->role;
        if ($role === "admin" || $role === "support") {
            $color->delete();
        }
    }
}
