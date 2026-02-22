<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
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
            'type' => 'required|string',
            'metadata' => 'nullable|array',
            'items' => 'required|array|min:1',
            'items.*.lot_no' => 'required|string',
            'items.*.qty_required' => 'required|integer|min:1',
            'items.*.allocation' => 'nullable|string',
            'items.*.owner' => 'nullable|string',
            'items.*.condition' => 'nullable|string',
            'items.*.qty_available' => 'nullable|integer',
        ];
    }
}
