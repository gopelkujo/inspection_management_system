<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
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
        return [
            'status' => 'sometimes|string',
            'type' => 'sometimes|string',
            'metadata' => 'nullable|array',
            'items' => 'sometimes|array|min:1',
            'items.*.lot_no' => 'required_with:items|string',
            'items.*.qty_required' => 'required_with:items|integer|min:1',
            'items.*.allocation' => 'nullable|string',
            'items.*.owner' => 'nullable|string',
            'items.*.condition' => 'nullable|string',
            'items.*.qty_available' => 'nullable|integer',
        ];
    }
}
