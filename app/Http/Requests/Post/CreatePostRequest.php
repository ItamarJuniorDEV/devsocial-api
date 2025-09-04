<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'  => ['required', 'in:text,photo'],
            'body'  => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('type') === 'text' && ! $this->filled('body')) {
                $validator->errors()->add('body', 'O campo body é obrigatório para posts do tipo text.');
            }

            if ($this->input('type') === 'photo' && ! $this->hasFile('photo')) {
                $validator->errors()->add('photo', 'O campo photo é obrigatório para posts do tipo photo.');
            }
        });
    }
}
