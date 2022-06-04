<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Services\UserService;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->method() == 'POST' && auth()->user()->HasPermission('create') || 
               $this->method() == 'PATCH' && auth()->user()->HasPermission('update') || 
               $this->method() == 'PUT';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userService = new UserService(auth()->user() ,request());
        return $userService->rules($this->method());
    }
}
