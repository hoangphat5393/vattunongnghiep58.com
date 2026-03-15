<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.post.edit', $this->post);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'post_title' => ['sometimes', 'string'],
            'post_slug' => ['sometimes', 'string'],
            'post_content' => ['nullable', 'string'],
            'post_excerpt' => ['nullable', 'string'],
            'post_status' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'taxs' => ['nullable', 'array'],
            'action' => ['required'],
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        if( ! isset($sanitized['post_status']) ){
            $sanitized['post_status'] = 0;
        }

        $html = $sanitized['post_content'];
        do {
            $tmp = $html;
            $html = preg_replace(
                '#<([^ >]+)[^>]*>[[:space:]]*</\1>#', '', $html );
        } while ( $html !== $tmp );

        $sanitized['post_content'] = $html ;

        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
