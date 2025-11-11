<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLocationRequest;
use App\Models\LocationHistory;
use App\Models\User;

class CollaboratorController extends Controller
{
    public function show($id) {
        $user = User::with('assets')->findOrFail($id);
        return response()->json($user);
    }

    public function history($id) {
        $user = User::findOrFail($id);
        $history = $user->locationHistories()->orderByDesc('recorded_at')->get();
        return response()->json($history);
    }

    public function updateLocation(UpdateLocationRequest $request, $id) {
        $authId = $request->user()->id;
        if ((int)$authId !== (int)$id) {
            return response()->json(['message' => 'Você só pode atualizar sua própria localização'], 403);
        }
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->update(['current_lat' => $data['lat'], 'current_lng' => $data['lng']]);
        LocationHistory::create(['user_id' => $user->id, 'lat' => $data['lat'], 'lng' => $data['lng']]);
        return response()->json(['message' => 'Localização atualizada']);
    }
}

