<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'id_products' => 'required',
            'qty' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_products' => 'Products is required!',
            'qty' => 'Qty is required!',
        ];
    }
}
