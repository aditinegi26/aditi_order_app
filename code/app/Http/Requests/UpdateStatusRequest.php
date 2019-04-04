<?php

namespace App\Http\Requests;

use App\http\Models\Order; //Model
use App\Rules\OrderStatusValidation;

class UpdateStatusRequest extends AbstractFormRequest
{
    /**
     * Validation rules
     *
     * @return array
     */
    public function rules()
    {

        return [
            'status' => [
                'required',
                'string',
                function ($attr, $value, $fail) {
                    if ($value !== Order::ASSIGNED_ORDER_STATUS) {
                        $fail('status_is_invalid');
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
            'status.required' => 'status_is_invalid',
            'status.string'   => 'status_is_invalid',
        ];
    }
}
