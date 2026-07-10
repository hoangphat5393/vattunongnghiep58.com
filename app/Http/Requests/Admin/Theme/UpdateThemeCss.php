<?php

namespace App\Http\Requests\Admin\Theme;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdateThemeCss extends FormRequest
{
    /**
     * @var array<int, string>
     */
    private const BLOCKED_PATTERNS = [
        '<script',
        '</script',
        'javascript:',
        'expression(',
        'behavior:',
        '<iframe',
        'data:text/html',
        '@import url(',
    ];

    public function authorize(): bool
    {
        $user = Auth::guard('admin')->user();

        return $user !== null && method_exists($user, 'isAdministrator') && $user->isAdministrator();
    }

    protected function prepareForValidation(): void
    {
        $content = $this->input('css_content');

        if (is_string($content)) {
            $this->merge([
                'css_content' => str_replace("\0", '', $content),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'css_content' => ['required', 'string', 'max:512000'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $content = (string) $this->input('css_content', '');

                foreach (self::BLOCKED_PATTERNS as $pattern) {
                    if (stripos($content, $pattern) !== false) {
                        $validator->errors()->add(
                            'css_content',
                            'CSS content contains disallowed patterns.'
                        );

                        return;
                    }
                }
            },
        ];
    }
}
