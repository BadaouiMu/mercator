<?php

namespace App\Http\Requests;

use App\Entity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateEntityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'          => [
                'min:3',
                'max:32',
                'required',
                //'unique:entities,name,' . request()->route('entity')->id,
                'unique:entities,name,'.request()->route('entity')->id.',id,deleted_at,NULL',
            ],
            'seurity_level' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
