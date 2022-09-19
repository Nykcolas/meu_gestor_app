<?php

namespace App\Http\Controllers;

use App\Models\Contas;
use App\Http\Requests\StoreContasRequest;
use App\Http\Requests\UpdateContasRequest;

class ContasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contas = new Contas;
        return response()->json($contas->paginate(5), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContasRequest  $request
     * @param  \App\Models\Contas  $contas
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContasRequest $request, Contas $contas)
    {
        $conta = $contas->create($request->all());
        return response()->json($conta, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContasRequest $request, $id)
    {
        $contas = Contas::find($id);
        if($contas === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $contas->fill($request->all());
        $contas->save();

        return response()->json($contas, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contas  $contas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $contas = Contas::find($id);
        return response()->json($contas, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contas::find($id)->delete();
    }
}
