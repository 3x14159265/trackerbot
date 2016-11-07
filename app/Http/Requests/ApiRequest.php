<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\App;

class ApiRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth = $this->header('Authorization');
        $auth = base64_decode(trim(str_replace('Basic', '', $auth)));
        $auth = explode(':', $auth);
        if(count($auth) != 2)
            return false;

        $key = trim($auth[0]);
        $secret = trim($auth[1]);

        $this->headers->add(['X-Api-Key' => $key]);
        $this->headers->add(['X-Api-Secret' => $secret]);

        return App::where('api_key', '=', $key)
            ->where('api_secret', '=', $secret)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'array|required'
        ];
    }
}
