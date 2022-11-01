<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContasRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nome_conta" => "required|unique:contas",
            "valor_pago_mensal" => "required|numeric",
            "data_inicio_conta" => "required|date",
            "valor_pago" => "numeric",
            "dia_vencimento" => "numeric|max:31|min:1",
        ];
    }
}
