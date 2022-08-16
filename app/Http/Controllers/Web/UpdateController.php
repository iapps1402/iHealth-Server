<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCooking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function index(Request $request)
    {
        return \Illuminate\Support\Facades\Hash::make('Sa.kh7675');
        if (empty($request->token) || $request->token != 'hossein123@')
            return null;

        foreach (Food::all() as $food) {
            try {
                DB::beginTransaction();
                $cooking = FoodCooking::create([
                    'title' => null,
                    'time' => null,
                    'calorie' => null,
                ]);

                $food->update([
                    'cooking_id' => $cooking->id
                ]);
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }

        return response()->json([
            'success'=> true,
            'message' => 'Successful!'
        ]);
    }
}
