<?php

namespace App\Http\Controllers;

use App\Models\Contas;
use App\Http\Requests\UpdateContasRequest;
use App\Http\Requests\StoreContasRequest;
use App\Repository\FormRepository;

class ContasController extends FormRepository
{

    public function __construct()
    {
        $StoreContasRequest = new StoreContasRequest;
        $Contas = new Contas;
        $UpdateContasRequest = new UpdateContasRequest;
        parent::__construct($StoreContasRequest, $Contas, $UpdateContasRequest);
    }
}
