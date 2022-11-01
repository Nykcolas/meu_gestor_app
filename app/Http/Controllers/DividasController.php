<?php

namespace App\Http\Controllers;

use App\Models\Dividas;
use App\Http\Requests\StoreDividasRequest;
use App\Http\Requests\UpdateDividasRequest;

class DividasController extends Controller
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
     * @param  \App\Http\Requests\StoreDividasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDividasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dividas  $dividas
     * @return \Illuminate\Http\Response
     */
    public function show(Dividas $dividas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dividas  $dividas
     * @return \Illuminate\Http\Response
     */
    public function edit(Dividas $dividas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDividasRequest  $request
     * @param  \App\Models\Dividas  $dividas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDividasRequest $request, Dividas $dividas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dividas  $dividas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dividas $dividas)
    {
        //
    }
}
