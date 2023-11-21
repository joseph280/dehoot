<?php

namespace Domain\Asset\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StakeRequest extends FormRequest
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
            'asset_id' => 'required|string',
            'template_id' => 'required|string',
            'land' => 'required|string',
            'position_x' => 'required|numeric|between:1,12',
            'position_y' => 'required|numeric|between:1,12',
        ];
    }
}
