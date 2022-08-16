<?php

namespace App\Http\Controllers\Admin\Food\Suggestion;

use App\Http\Controllers\Controller;
use App\Models\FoodSuggestion;
use App\Models\FoodUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFoodSuggestionManageController extends Controller
{
    private function getItems(Request $request)
    {
        $suggestions = FoodSuggestion::with(['food', 'unit'])->orderByDesc('id');

        if ($request->has('q'))
            $suggestions->whereHas('food', function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $suggestions->paginate();
    }

    public function index(Request $request)
    {
        if (Request()->isMethod('GET')) {
            $suggestions = $this->getItems($request);

            $page = [
                'title' => 'مدیریت پیشنهاد غذا',
                'description' => 'مدیریت پیشنهاد غذا'
            ];

            return view('admin.food.suggestion.manage', compact('suggestions', 'page'));
        }

        switch ($request->action) {
            case 'delete':
                if (Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0'
                ])->fails())
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد id ارسال نشده است.'
                    ]);

                FoodSuggestion::findOrFail($request->id)->delete();

                $suggestions = $this->getItems($request);

                return response()->json([
                    'success' => true,
                    'message' => 'پیشنهاد با موفقیت حذف شد.',
                    'view' => view('admin.food.suggestion.components.manage_table', compact('suggestions'))->render()
                ]);

            case 'units':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|string|max:191',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                return response()->json([
                    'success' => true,
                    'units' => FoodUnit::where('food_id', $request->id)->get()
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);
        }
    }
}
