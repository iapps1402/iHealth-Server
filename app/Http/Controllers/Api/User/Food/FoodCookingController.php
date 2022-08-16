<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCooking;
use Illuminate\Http\Request;

class FoodCookingController extends Controller
{
    public function index($id, Request $request)
    {
        $cooking = FoodCooking::with(['food.picture.thumbnail', 'instructions', 'ingredients'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'cooking' => $cooking
        ]);
    }

    public function second($id, Request $request)
    {
        $cooking = FoodCooking::with(['food.picture.thumbnail', 'instructions', 'ingredients'])
            ->whereHas('food', function ($q) use ($id) {
                $q->where('id', $id);
            })
            ->first();

        if ($cooking == null)
            return abort(404);

        return response()->json([
            'success' => true,
            'cooking' => $cooking
        ]);
    }
}
