<?php

namespace App\Http\Controllers\Admin\Food\Cooking;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCookingInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CookingInstructionController extends Controller
{
    private function generateItems($foodId, Request $request)
    {
        $ingredients = FoodCookingInstruction::whereHas('cooking.food', function ($q) use($foodId) {
            $q->where('id', $foodId);
        })->orderBy('id');

        if ($request->has('q'))
            $ingredients->where('text', 'like', '%' . $request->q . '%');

        return $ingredients->paginate();
    }

    public function index($foodId, Request $request)
    {
        $food = Food::findOrFail($foodId);

        if ($request->isMethod('GET')) {

            $instructions = $this->generateItems($foodId, $request);

            $page = [
                'title' => 'مدیریت دستور پخت',
                'description' => 'مدیریت دستور پخت'
            ];

            return view('admin.food.cooking.instruction.manage', [
                'page' => $page,
                'instructions' => $instructions
            ]);
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

                FoodCookingInstruction::findOrFail($request->id)->delete();
                $instructions = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.food.cooking.instruction.components.manage_table', compact('instructions'))->render(),
                    'message' => 'مرحله با موفقیت حذف شد.'
                ]);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);

            case 'add':
                $validator = Validator::make($request->all(), [
                    'text_fa' => 'required|string|max:191',
                    'text_en' => 'required|string|max:191'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $instruction = FoodCookingInstruction::create([
                    'text_fa' => $request->text_fa,
                    'text_en' => $request->text_en,
                    'cooking_id' => $food->cooking_id
                ]);

                $instructions = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'category' => $instruction,
                    'view' => view('admin.food.cooking.instruction.components.manage_table', compact('instructions'))->render(),
                    'message' => 'مرحله با موفقیت افزوده شد.'
                ]);

            case 'edit':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                    'text_fa' => 'required|string|max:191',
                    'text_en' => 'required|string|max:191'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $instruction = FoodCookingInstruction::findOrFail($request->id);

                $instruction->update([
                    'text_fa' => $request->text_fa,
                    'text_en' => $request->text_en
                ]);

                $instructions = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.food.cooking.instruction.components.manage_table', compact('instructions'))->render(),
                    'message' => 'مرحله با موفقیت ویرایش شد.'
                ]);
        }
    }
}
