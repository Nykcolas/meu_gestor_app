<?php

namespace App\Repository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormRepository extends Controller
{
    protected $storeRequest;
    protected $updateRequest;
    protected $model;
    public $pagination;

    public function __construct($storeRequest, $model, $updateRequest)
    {
        $this->storeRequest = $storeRequest;
        $this->updateRequest = $updateRequest;
        $this->model = $model;
    }

    public function SetPagination($nrPaginas) {
        $this->pagination = $nrPaginas;
    }

    public function index()
    {
        $pagination = $this->pagination ?? 5;
        return response()->json($this->model->paginate($pagination), 200);
    }

    public function show($id)
    {
        $register = $this->model->find($id);
        return response()->json($register, 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->storeRequest->rules());

        $register = $this->model->create($request->all());
        return response()->json($register, 201);
    }

    public function destroy($id)
    {
        $this->model->find($id)->delete();
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->updateRequest->rules());
        $register = $this->model->find($id);

        if($register === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $register->fill($request->all());
        $register->save();

        return response()->json($register, 200);

    }
}

?>