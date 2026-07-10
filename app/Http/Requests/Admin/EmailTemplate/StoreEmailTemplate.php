<?php

namespace App\Http\Requests\Admin\EmailTemplate;

use App\Support\EmailTemplateCodes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailTemplate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => EmailTemplateCodes::normalize((string) $this->input('code', '')),
            'text' => is_string($this->input('text')) ? $this->input('text') : '',
            'status' => $this->input('status', 1),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('email_templates', 'code'),
            ],
            'text' => ['required', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nhập tiêu đề mail (dùng làm subject khi gửi).',
            'name.max' => 'Tiêu đề tối đa 50 ký tự.',
            'code.required' => 'Nhập mã code template.',
            'code.regex' => 'Mã code chỉ gồm chữ thưường, số và dấu gạch dưới (vd: new_register).',
            'code.unique' => 'Mã code đã tồn tại.',
            'text.required' => 'Nhập nội dung mail.',
        ];
    }
}
