<?php

namespace App\Http\Controllers;

use App\Models\Bones;
use Illuminate\Http\Request;

class BonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View("Bones", [
            "Bones" => Bones::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "target" => "required",
            "bones" => "required",
        ]);
        $options = ["type" => "normal"];
        if ($request->boolean("for_leader")) {
            $options = ["type" => "leader"];
        }
        Bones::create(array_merge($data, $options));
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
     * @param  \App\Models\Bones  $bones
     * @return \Illuminate\Http\Response
     */
    public function show(Bones $bones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bones  $bones
     * @return \Illuminate\Http\Response
     */
    public function edit(Bones $bones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bones  $bones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bones $bones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bones  $bones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bones $bones)
    {
        return $bones->delete();
    }
}