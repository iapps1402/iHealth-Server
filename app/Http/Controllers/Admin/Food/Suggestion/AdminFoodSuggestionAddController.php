<?php

namespace App\Http\Controllers\Admin\Food\Suggestion;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodSuggestion;
use App\Models\FoodUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFoodSuggestionAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $foods = Food::orderByDesc('created_at')
                ->whereDoesntHave('suggestions')
                ->get();

            $page = [
                'title' => 'افزودن پیشنهاد غذا',
                'description' => 'افزودن پیشنهاد غذا',
            ];

            return view('admin.food.suggestion.add', compact('foods', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|numeric|not_in:0',
            'food_id' => 'nullable|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        if (FoodSuggestion::where('food_id', $request->food_id)->exists())
            return response()->json([
                'success' => false,
                'message' => 'این غذا قبلا ثبت شده است.'
            ]);

        if (!FoodUnit::where('food_id', $request->food_id)
            ->where('id', $request->unit_id)->exists())
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'message' => 'غذا یافت نشد.'
                ]);

        FoodSuggestion::create([
            'unit_id' => $request->unit_id,
            'food_id' => $request->food_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'غذا با موفقیت افزوده شد.',
            'redirect_url' => route('admin_food_suggestion_manage')
        ]);
    }
}
