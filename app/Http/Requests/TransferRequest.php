<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'user_a_id' => 'required|integer|exists:users,id|different:user_b_id',
            'user_b_id' => 'required|integer|exists:users,id',
            'assets_from_a' => 'required|array|min:1',
            'assets_from_a.*' => 'integer|exists:assets,id',
            'assets_from_b' => 'required|array|min:1',
            'assets_from_b.*' => 'integer|exists:assets,id',
            'allowed_status' => 'nullable|in:EM_USO,NOVO,MANUTENCAO',
        ];
    }
}

