<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;

class AssetController extends Controller
{
    public function store(StoreAssetRequest $request) {
        $data = $request->validated();
        if ($request->user()->id !== (int)$data['collaborator_id']) {
            return response()->json(['message' => 'Só é permitido criar ativos para si próprio'], 403);
        }
        $asset = Asset::create([
            'name' => $data['name'],
            'book_value' => $data['book_value'],
            'distribution_lat' => $data['distribution_lat'],
            'distribution_lng' => $data['distribution_lng'],
            'status' => $data['status'] ?? 'NOVO',
            'user_id' => $data['collaborator_id'],
        ]);
        return response()->json($asset, 201);
    }
}

