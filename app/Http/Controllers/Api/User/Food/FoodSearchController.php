<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FoodSearchController extends Controller
{
    public function index(Request $request)
    {
        if (Validator::make($request->all(), [
            'q' => 'nullable|string|max:100',
            'category_id' => 'nullable|numeric|not_in:0',
            'take' => 'nullable|numeric|not_in:0',
            'ignore' => 'nullable|numeric|not_in:0'
        ])->fails())
            return response()->json([
                'success' => false,
                'message' => 'invalid input'
            ]);

        $foods = Food::with(['units.materials', 'categories', 'picture.thumbnail']);

        if (!empty($request->q))
        $foods->where(function ($q) use ($request) {
            $q->where('name_fa', 'like', '%' . $request->q . '%')
                ->orWhere('name_en', 'like', '%' . $request->q . '%');
        });

        if (!empty($request->ignore))
            $foods->where('id', '<>', $request->ignore);

        if (!empty($request->category_id))
            $foods->whereHas('categoryRelations', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });

        $foods = $foods->take(empty($request->take) ? 15 : $request->take)->paginate();

        foreach ($foods->items() as $food) {
            $food->append(['default_unit', 'show_cooking']);
        }

        return $foods;
    }
}
