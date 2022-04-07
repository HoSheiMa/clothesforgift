<?php

namespace App\Http\Controllers;

use App\Models\StatusAction;
use Illuminate\Http\Request;

class StatusActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("status_action", ["status" => StatusAction::all()]);
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
     * @param  \App\Models\StatusAction  $statusAction
     * @return \Illuminate\Http\Response
     */
    public function show(StatusAction $statusAction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StatusAction  $statusAction
     * @return \Illuminate\Http\Response
     */
    public function edit(StatusAction $statusAction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StatusAction  $statusAction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatusAction $statusAction)
    {
        $statusAction->update([
            "message" => empty($request->message) ? "اهلا" : $request->message,
            "status" => empty($request->status)
                ? "false"
                : filter_var($request->status, FILTER_VALIDATE_BOOLEAN),
            "invoice" => empty($request->invoice)
                ? "false"
                : filter_var($request->invoice, FILTER_VALIDATE_BOOLEAN),
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatusAction  $statusAction
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusAction $statusAction)
    {
        //
    }
}