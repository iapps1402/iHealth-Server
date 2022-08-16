<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\UserFood;
use Illuminate\Http\Request;

class FoodDetailsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');

        $categories = FoodCategory::with(['picture'])->withCount('foods')
            ->where('in_app', 1)
            ->get();

        $primaryUserFoods = UserFood::whereHas('relation', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereNotNull('food_id')
            ->distinct('food_id')
            ->take(10)
            ->pluck('food_id');

        $secondaryUserFoods = UserFood::whereHas('relation', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->orderByDesc('created_at')
            ->whereNull('food_id')
            ->take(10)
            ->get();

        $primaryFoods = Food::with(['units.materials', 'categories', 'picture.thumbnail'])
            ->whereIn('foods.id', $primaryUserFoods)
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'primary_foods' => $primaryFoods,
            'secondary_foods' => $secondaryUserFoods
        ]);
    }
}
