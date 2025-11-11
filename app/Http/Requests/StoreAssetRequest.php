<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name' => 'required|string|max:150',
            'book_value' => 'required|numeric|min:0',
            'distribution_lat' => 'required|numeric|between:-90,90',
            'distribution_lng' => 'required|numeric|between:-180,180',
            'collaborator_id' => 'required|integer|exists:users,id',
            'status' => 'in:NOVO,EM_USO,MANUTENCAO',
        ];
    }
}

