<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BeneficiaryRequest extends FormRequest
{

    public function rules()
    {
        return [
            'first_name'=>[
                'required',
            ],
            'id_number'=>[
                'required',
                Rule::unique('beneficiaries','id_number'),
                'digits:9'
            ],
            'second_name'=>[
                'required',


            ],'last_name'=>[
                'required',
            ],
            'region_id'=>[
                'required',
                Rule::exists('regions','id')
            ],
        ];
    }
}

