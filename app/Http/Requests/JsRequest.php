<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->headers->add(['X-Api-Key' => $this->route('api_key')]);
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
            'data' => 'required|array'
        ];
    }
}
