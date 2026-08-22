<?php

namespace App\Http\Requests\Admin\BedAndBathRoom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateBedAndBathRoom extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.bed-and-bath-room.edit', $this->bedAndBathRoom);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'number' => ['sometimes', 'integer'],

            'type' => ['sometimes', 'string'],

            'slug' => ['sometimes', Rule::unique('bed_and_bath_rooms', 'slug')->ignore($this->bedAndBathRoom->getKey(), $this->bedAndBathRoom->getKeyName()), 'string'],

            'status' => ['sometimes', 'boolean'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
