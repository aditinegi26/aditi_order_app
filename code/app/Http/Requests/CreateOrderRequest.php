<?php

namespace App\Http\Requests;

use App\Rules\CoordinateValidation;

class CreateOrderRequest extends AbstractFormRequest
{
    /**
     * Validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'origin'      => [
                'required',
                'array',
                function ($attr, $value, $fail) {
                    if (empty($value) || count($value) !== 2
                        || empty($value[0])
                        || empty($value[1])
                        || ! is_numeric($value[0])
                        || ! is_numeric($value[1])
                    ) {
                        $fail('INVALID_PARAMETERS');
                    }
                },
            ],
            'destination' => [
                'required',
                'array',
                function ($attr, $value, $fail) {
                    if (empty($value) || count($value) !== 2
                        || empty($value[0])
                        || empty($value[1])
                        || ! is_numeric($value[0])
                        || ! is_numeric($value[1])
                    ) {
                        $fail('INVALID_PARAMETERS');
                    }
                },
            ],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */

    public function messages()
    {
        return [
            'origin.required'      => 'INVALID_PARAMETERS',
            'destination.required' => 'INVALID_PARAMETERS',
        ];
    }
}
