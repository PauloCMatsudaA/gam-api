<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request) {
        $min = (float)($request->query('min_value', 0));
        $avg = Asset::avg('book_value');
        $countAbove = Asset::where('book_value', '>', $min)->count();
        return response()->json([
            'average_book_value' => (float)($avg ?? 0),
            'count_above' => $countAbove,
            'min_value' => $min
        ]);
    }
}

