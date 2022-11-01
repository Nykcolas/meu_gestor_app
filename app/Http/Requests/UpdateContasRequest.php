<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nome_conta" => "required",
            "valor_pago_mensal" => "required|numeric",
            "data_inicio_conta" => "required|date",
            "valor_pago" => "numeric",
            "dia_vencimento" => "numeric",
        ];
    }
}
