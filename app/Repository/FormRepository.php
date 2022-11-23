<?php

namespace App\Repository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormRepository extends Controller
{
    protected $storeRequest;
    protected $updateRequest;
    protected $model;
    protected $nomeClass;
    protected $pagination;
    protected $arrayFillable;

    public function __construct($nomeClass)
    {
        $this->nomeClass = ucfirst($nomeClass);
        $this->SetFormModel();
        $this->SetFormUpdateRequest();
        $this->SetFormStoreRequest();
    }

    protected function SetFormModel() {
        $model = "App\\Models\\".$this->nomeClass;
        $this->model = new $model();
    }

    protected function SetFormUpdateRequest()
    {
        $updateRequest = "App\\Http\\Requests\\Update".$this->nomeClass."Request";
        $this->updateRequest = new $updateRequest();
    }

    protected function SetFormStoreRequest()
    {
        $storeRequest = "App\\Http\\Requests\\Store".$this->nomeClass."Request";
        $this->storeRequest = new $storeRequest();
    }

    public function SetPagination($nrPaginas)
    {
        $this->pagination = $nrPaginas;
    }

    public function index()
    {
        $pagination = $this->pagination ?? 5;
        $bool = null;
        $list = $this->model->paginate($pagination);
        foreach ($this->storeRequest->rules() as $key => $value) {
            if (strpos($value, 'boolean')) {
                $bool[] = $key;
            }
        }
        if ($bool != null) {
            foreach ($list as $key => $value) {
                foreach ($bool as $key => $colum) {
                    if ($value->$colum == 1) {
                        $value->$colum = "Sim";
                    } elseif ($value->$colum == 0) {
                        $value->$colum = "Não";
                    }
                }
            }
        }
        

        return response()->json($list, 200);
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