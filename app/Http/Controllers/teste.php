<?php

namespace App\Http\Controllers;

use App\Repository\FormRepository;

class ContasController extends FormRepository
{
    public function __construct()
    {
        parent::__construct("Contas");
    }
}
