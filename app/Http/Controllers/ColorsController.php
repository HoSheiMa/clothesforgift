<?php

namespace App\Http\Controllers;

use App\Models\colors;
use App\Models\Item;
use Illuminate\Http\Request;

class ColorsController extends Controller
{
    public function getColorsByItemId(Request $request, Item $item)
    {
        if (!$item) {
            return [];
        }
        $color = colors::where(
            "products_id",
            colors::find($item->color_id)->products_id
        )->get();

        return [
            "colors" => $color,
            "item" => $item,
        ];
    }
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
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function show(colors $colors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function edit(colors $colors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, colors $colors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function destroy(colors $colors)
    {
        //
    }
}