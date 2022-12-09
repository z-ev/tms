<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|min:3|max:30',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required',
            'merchantId' => 'uuid',
            'statusId'   => 'numeric',
            'roles'      => 'nullable',
            'roles.*'    => 'string',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name'     => 'Введите имя пользователя',
            'name.min' => 'Минимальное имя 3 символа',
            'name.max' => 'Максимальное имя 10 символов',
            'password' => 'Введите пароль (от 8 символов)',
        ];
    }
}
