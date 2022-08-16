<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodCompareController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primary_id' => 'required|numeric|not_in:0',
            'secondary_id' => 'required|numeric|not_in:0',
        ]);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        if($request->primary_id == $request->secondary_id)
            return response()->json([
                'success' => false,
                'message' => 'Invalid data.'
            ]);

        return response()->json([
            'success' => true,
            'primary_food' => Food::with(['cooking.ingredients', 'units', 'picture.thumbnail'])->findOrFail($request->primary_id),
            'secondary_food' => Food::with(['cooking.ingredients', 'units', 'picture.thumbnail'])->findOrFail($request->secondary_id),
        ]);
    }
}
