<?php

namespace App\Http\Requests\Api\V1\Document;

use App\Enums\User\Roles;
use App\Http\Requests\FormRequestBase;
use Illuminate\Validation\Rule;

class AssignDocumentRequest extends FormRequestBase
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {

        return [
            'user_id' => 'required|numeric|exists:users,id',
            'document_id' => 'required|numeric|exists:documents,id',
            'role_type' => 'required|string|' . Rule::in(array_column(Roles::cases(), 'value')),
            'appointment_end_time' => 'required|string|date_format:Y/m/d|after:today' ,
        ];
    }
}
