<?php

namespace App\Http\Controllers;

use App\Models\Notif;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Mockery\Matcher\Not;

class NotifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View("notif");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "type" => "required",
            "for" => "required",
            "message" => "required",
        ]);
        Notif::create($data);
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
     * @param  \App\Models\Notif  $notif
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return ["data" => Notif::all()];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notif  $notif
     * @return \Illuminate\Http\Response
     */
    public function edit(Notif $notif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notif  $notif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notif $notif, $status)
    {
        $notif->update([
            "status" => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notif  $notif
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notif $notif)
    {
        return $notif->delete();
    }
}