<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\UserDateRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodListController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        $relation = UserDateRelation::with(['foods.food.categories', 'foods.data.unit', 'foods.food.units.materials', 'activities'])
            ->whereDate('date', $request->date)
            ->where('user_id', $user->id)
            ->first();

        if ($relation != null)
            foreach ($relation->foods as $food)
                $food->append(['can_be_edited']);

        return response()->json([
            'success' => true,
            'relation' => $relation,
        ]);
    }
}
