<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Models\Asset;
use App\Models\Transfer;
use App\Models\TransferAsset;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function store(TransferRequest $request) {
        $data = $request->validated();
        $a = (int)$data['user_a_id'];
        $b = (int)$data['user_b_id'];
        $allowedStatus = $data['allowed_status'] ?? null;

        return DB::transaction(function () use ($request, $a, $b, $allowedStatus, $data) {
            $assetsA = Asset::whereIn('id', $data['assets_from_a'])->lockForUpdate()->get();
            $assetsB = Asset::whereIn('id', $data['assets_from_b'])->lockForUpdate()->get();

            foreach ($assetsA as $it) {
                if ($it->user_id !== $a) {
                    abort(422, "Ativo {$it->id} não pertence ao usuário A");
                }
                if ($allowedStatus && $it->status !== $allowedStatus) {
                    abort(422, "Ativo {$it->id} não está no status permitido");
                }
            }
            foreach ($assetsB as $it) {
                if ($it->user_id !== $b) {
                    abort(422, "Ativo {$it->id} não pertence ao usuário B");
                }
                if ($allowedStatus && $it->status !== $allowedStatus) {
                    abort(422, "Ativo {$it->id} não está no status permitido");
                }
            }

            $sumA = $assetsA->sum('book_value');
            $sumB = $assetsB->sum('book_value');
            if (bccomp((string)$sumA, (string)$sumB, 2) !== 0) {
                abort(422, "Somatório dos valores contábeis deve ser equivalente");
            }

            $transfer = Transfer::create([
                'user_a_id' => $a,
                'user_b_id' => $b,
                'initiated_by_user_id' => $request->user()->id,
                'total_value_a' => $sumA,
                'total_value_b' => $sumB,
            ]);

            foreach ($assetsA as $it) {
                TransferAsset::create([
                    'transfer_id' => $transfer->id,
                    'asset_id' => $it->id,
                    'from_user_id' => $a,
                    'to_user_id' => $b,
                    'book_value_snapshot' => $it->book_value,
                ]);
                $it->update(['user_id' => $b]);
            }
            foreach ($assetsB as $it) {
                TransferAsset::create([
                    'transfer_id' => $transfer->id,
                    'asset_id' => $it->id,
                    'from_user_id' => $b,
                    'to_user_id' => $a,
                    'book_value_snapshot' => $it->book_value,
                ]);
                $it->update(['user_id' => $a]);
            }

            return response()->json($transfer->load('items'), 201);
        });
    }
}

