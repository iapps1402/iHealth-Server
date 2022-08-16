<?php

namespace App\Http\Controllers\Admin\Food\Unit\Material;

use App\Http\Controllers\Controller;
use App\Models\BlogPostCategory;
use App\Models\FoodUnit;
use App\Models\FoodUnitMaterial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminFoodUnitMaterialController extends Controller
{
    private function getItems($unitId, Request $request)
    {
        $materials = FoodUnitMaterial::orderByDesc('id')
            ->where('unit_id', $unitId);

        if ($request->has('q'))
            $materials->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $materials->paginate();
    }

    public function index($foodId, $unitId, Request $request)
    {
        $unit = FoodUnit::with(['food'])->findOrFail($unitId);
        $materials = $this->getItems($unitId, $request);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'مدیریت مخلفات ' . $unit->name_fa . ' ' . $unit->food->name_fa,
                'description' => 'مدیریت مخلفات ' . $unit->name_fa . ' ' . $unit->food->name_fa
            ];

            return view('admin.food.unit.material.manage', compact('materials', 'unit', 'page'));
        }

        switch ($request->action) {
            case 'delete':
                $material = FoodUnitMaterial::findOrFail($request->id);

                try {
                    $material->delete();
                } catch (Exception $exception) {
                    return response()->json([
                        'success' => false,
                        'message' => 'این مخلفات قابل حذف نیست.'
                    ]);
                }

                $materials = $this->getItems($unitId, $request);

                return response()->json([
                    'success' => true,
                    'materials' => $materials,
                    'view' => view('admin.food.unit.material.component.manage_table', compact('materials', 'unit'))->render(),
                    'message' => 'مخلفات با موفقیت حذف شد.'
                ]);

            case 'add':
                $validator = Validator::make($request->all(), [
                    'name_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                    'value' => 'required|numeric|not_in:0',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                FoodUnitMaterial::create([
                    'unit_id' => $unitId,
                    'name_en' => $request->name_en,
                    'name_fa' => $request->name_fa,
                    'value' => $request->value,
                ]);

                $materials = $this->getItems($unitId, $request);

                return response()->json([
                    'success' => true,
                    'message' => 'مخلفات با موفقیت افزوده شد.',
                    'view' => view('admin.food.unit.material.component.manage_table', compact('materials', 'unit'))->render()
                ]);

            case 'edit':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                    'name_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                    'value' => 'required|numeric|not_in:0',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $material = FoodUnitMaterial::where('unit_id', $unitId)->findOrFail($request->id);

                $material->update([
                    'name_en' => $request->name_en,
                    'name_fa' => $request->name_fa,
                    'value' => $request->value,
                ]);

                $materials = $this->getItems($unitId, $request);

                return response()->json([
                    'success' => true,
                    'message' => 'مخلفات با موفقیت ویرایش شد.',
                    'view' => view('admin.food.unit.material.component.manage_table', compact('materials', 'unit'))->render()
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فیلد action به درستی ارسال نشده است.'
        ]);
    }
}
