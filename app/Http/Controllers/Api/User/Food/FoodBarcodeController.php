<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class FoodBarcodeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string',
        ]);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $food = Food::with(['units.materials', 'categories', 'picture.thumbnail'])
            ->where('barcode', $request->barcode)->first();

        if ($food == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        return response()->json([
            'success' => true,
            'food' => $food
        ]);
    }
}
