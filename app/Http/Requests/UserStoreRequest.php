<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // не рабочая регулярка - |regex:/^(\+7|\+37)\d{9,11}$/i
        if (request()->isMethod('post')) {
            return [
                'name' => 'required|string|min:3|max:40',
                'surname' => 'required|string|min:3|max:40',
                'number' => 'required|string',
                'image' => 'required|image|mimes:jpg,png|max:2048',
            ];
        } else {
            return [
                'name' => 'required|string|min:3|max:40',
                'surname' => 'required|string|min:3|max:40',
                'number' => 'required|string',
                'image' => 'required|image|mimes:jpg,png|max:2048',
            ];
        }
    }

    public function messages()
    {
        if (request()->isMethod('post')) {
            return [
                'name.required' => 'Имя должно быть заполнено!',
                'surname.required' => 'Фамилия должна быть заполнена!',
                'number.required' => 'Укажите номер телефона!',
                'image.required' => 'Прикрепите аватар!',
            ];
        } else {
            return [
                'name.required' => 'Имя должно быть заполнено!',
                'surname.required' => 'Фамилия должна быть заполнена!',
                'number.required' => 'Укажите номер телефона!',
//                'image.required' => 'Прикрепите аватар!',
            ];
        }
    }
}

//         'name',
//        'surname',
//        'number',
//        'image',
