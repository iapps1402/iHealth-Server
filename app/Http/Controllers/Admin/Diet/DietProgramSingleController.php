<?php

namespace App\Http\Controllers\Admin\Diet;

use App\Helpers\HelperDietProgram;
use App\Http\Controllers\Controller;
use App\Models\DietProgramDay;
use App\Models\DietProgramSupplement;
use App\Models\Food;
use App\Models\DietProgram;
use App\Models\DietProgramDayMeal;
use App\Models\DietProgramDayMealItem;
use App\Models\FoodCategory;
use App\Models\FoodUnit;
use App\Models\User;
use App\Models\UserDateRelation;
use App\Models\UserDietProgram;
use App\Models\UserWeight;
use App\Notifications\UserDietProgramSentNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class DietProgramSingleController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن برنامه تغذیه جدید',
                'description' => 'افزودن برنامه تغذیه جدید'
            ];

            $meals = FoodCategory::all();

            return view('admin.nutrition.single', compact('page', 'meals'));
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|not_in:0',
            'meals' => 'required|json',
            'supplements' => 'nullable|json',
            'protein' => 'required|numeric|not_in:0',
            'carbs' => 'required|numeric|not_in:0',
            'fat' => 'required|numeric|not_in:0',
            'note' => 'nullable|string',
            'decrease_or_increase_coefficient' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        return $this->createDiet($request);
    }

    public function details($id, Request $request)
    {
        $program = DietProgram::with(['user', 'days.meals.items.food', 'days.meals.items.unit', 'writer', 'supplements.supplement', 'supplements.unit'])->findOrFail($id);

        $page = [
            'title' => 'برنامه تمرینی شماره #' . $id,
            'description' => 'برنامه تمرینی شماره #' . $id
        ];

        return view('admin.nutrition.details', compact('program', 'page'));
    }

    public function action($action, Request $request)
    {
        switch ($action) {
            case 'food-search':
                if (!$request->has('q'))
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parameters'
                    ]);

                $foods = Food::where(function ($q) use ($request) {
                    $q->where('name_fa', 'like', '%' . $request->q . '%')
                        ->orWhere('name_en', 'like', '%' . $request->q . '%');
                })->take(15)->get();


                return response()->json([
                    'success' => true,
                    'items' => $foods
                ]);

            case 'check-food':
                if (!$request->has('id'))
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parameters'
                    ]);

                $user = Food::with(['units'])->findOrFail($request->id);

                return response()->json([
                    'success' => true,
                    'food' => $user
                ]);

            case 'user-search':
                if (!$request->has('q'))
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parameters'
                    ]);

                $users = User::whereRaw("CONCAT(`first_name`, ' ', `last_name`) like '%$request->q%'")
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNotNull('phone_number')
                            ->where('phone_number', 'like', '%' . $request->q . '%');
                    })
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNotNull('email')
                            ->where('email', 'like', '%' . $request->q . '%');
                    })
                    ->take(15)->get();

                foreach ($users as $user)
                    $user->append(['contact']);

                return response()->json([
                    'success' => true,
                    'items' => $users
                ]);

            case 'tags':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                    'calorie' => 'nullable|numeric',
                    'protein' => 'nullable|numeric',
                    'fat' => 'nullable|numeric',
                    'carbs' => 'nullable|numeric',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message<br>')
                    ]);

                $foods = Food::whereHas('categoryRelations', function ($q) use ($request) {
                    $q->where('category_id', $request->id);
                })->take(30)
                    ->whereHas('units', function ($q) {
                        $q->where('default', 1);
                    })
                    ->inRandomOrder()
                    ->get();

                foreach ($foods as $user)
                    $user->append(['tag_fa', 'default_unit']);

                if (!empty($request->calorie)) {
                    foreach ($foods as $food) {
                        $unitValue = $this->getUnitValue($food->default_unit, $request->calorie, $request->protein, $request->carbs, $request->fat);;

                        if ($unitValue > 0)
                            $food->unit_value = $unitValue;
                    }
                }

                return response()->json([
                    'success' => true,
                    'foods' => $foods
                ]);

            case 'check-user':
                if (!$request->has('id'))
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parameters'
                    ]);

                $user = User::findOrFail($request->id);
                $user->append(['profile_completed', 'age', 'activity_fa', 'gender_fa', 'bmi', 'bmr', 'activity_ratio', 'contact']);

                $expiresMessage = null;

                if ($user->diet_program_expires_at != null && now()->format('Y-m-d') > $user->diet_program_expires_at)
                    $expiresMessage = 'این کاربر تا تاریخ ' . Jalalian::fromDateTime($user->diet_program_expires_at)->format('%Y/%m/%d') . ' اعتبار دریافت برنامه تغذیه داشته است.';

                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'expires_message' => $expiresMessage
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid Action.'
        ]);
    }

    private function createDay($program, Collection $meals, $dayOfWeek): void
    {
        $items = $meals->filter(function ($meal) use ($dayOfWeek) {
            return $meal['day'] == $dayOfWeek;
        })->values();

        if (!$items->count())
            return;

        $programDay = DietProgramDay::create([
            'day' => $dayOfWeek,
            'program_id' => $program->id,
        ]);

        foreach ($items as $index => $meal) {
            switch ($meal['meal']) {
                case 'breakfast':
                default:
                    $icon = 'fa_cheese_solid';
                    break;

                case 'lunch':
                    $icon = 'fa_utensils_solid';
                    break;

                case 'dinner':
                    $icon = 'fa_pizza_slice_solid';
                    break;

                case 'snack':
                    $icon = 'fa_hamburger_solid';
                    break;

                case 'before-workout':
                    $icon = 'fa_bacon_solid';
                    break;

                case 'after-workout':
                    $icon = 'fa_egg_solid';
                    break;

            }

            $programDayMeal = DietProgramDayMeal::create([
                'day_id' => $programDay->id,
                'name_en' => $meal['name_en'],
                'name_fa' => $meal['name_fa'],
                'icon' => $icon
            ]);

            foreach ($items[$index]['items'] as $item)
                DietProgramDayMealItem::create([
                    'meal_id' => $programDayMeal->id,
                    'food_id' => $item['food_id'],
                    'unit_id' => $item['unit_id'],
                    'value' => $item['value']
                ]);
        }
    }

    public function edit($id, Request $request)
    {
        $program = DietProgram::with(['user', 'days.meals.items.food', 'days.meals.items.unit', 'supplements.supplement', 'supplements.unit'])->findOrFail($id);

        $program->user->append(['contact']);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'برنامه تغذیه ' . $program->user->full_name,
                'description' => 'برنامه تغذیه ' . $program->user->full_name
            ];

            $meals = FoodCategory::all();

            return view('admin.nutrition.single', compact('page', 'meals', 'program'));
        }

        $validator = Validator::make($request->all(), [
            'meals' => 'required|json',
            'supplements' => 'nullable|json',
            'protein' => 'required|numeric|not_in:0',
            'carbs' => 'required|numeric|not_in:0',
            'fat' => 'required|numeric|not_in:0',
            'note' => 'nullable|string',
            'decrease_or_increase_coefficient' => 'required|numeric',
            'user_id' => 'required|numeric|not_in:0',
            'send_again' => 'required|boolean'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $user = User::find($request->user_id);

        if ($user == null)
            return response()->json([
                'success' => false,
                'message' => 'کاربر انتخاب شده وجود ندارد.'
            ]);

        if ($request->send_again)
            return $this->createDiet($request);

        $meals = collect(json_decode($request->meals, true));
        $supplements = collect(json_decode($request->supplements, true));

        try {
            DB::beginTransaction();

            $program->update([
                'note' => $request->note,
                'protein' => $request->protein,
                'carbs' => $request->carbs,
                'fat' => $request->fat,
                'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
            ]);

            DietProgramDay::where('program_id', $program->id)->delete();
            DietProgramSupplement::where('program_id', $program->id)->delete();

            foreach ($supplements as $supplement) {
                DietProgramSupplement::create([
                    'program_id' => $program->id,
                    'unit_id' => empty($supplement['value']) ? null : $supplement['unit_id'],
                    'value' => empty($supplement['value']) ? null : $supplement['value'],
                    'unit_text' => empty($supplement['unit_text']) ? null : $supplement['unit_text'],
                    'text' => $supplement['text'],
                    'supplement_id' => $supplement['supplement_id']
                ]);
            }


            $this->createDay($program, $meals, 'monday');
            $this->createDay($program, $meals, 'tuesday');
            $this->createDay($program, $meals, 'wednesday');
            $this->createDay($program, $meals, 'thursday');
            $this->createDay($program, $meals, 'friday');
            $this->createDay($program, $meals, 'saturday');
            $this->createDay($program, $meals, 'sunday');

            $user->update([
                'protein_ratio' => $request->protein,
                'fat_ratio' => $request->fat,
                'calorie_ratio' => 4 *  $request->protein + 9 * $request->fat + 4 *  $request->carbs,
                'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
            ]);

            $relation = UserDateRelation::where('user_id', $user->id)
                ->whereDate('date', now())
                ->first();

            if ($relation != null)
                $relation->update([
                    'calorie_ratio' => $user->calorie_ratio,
                    'protein_ratio' => $user->protein_ratio,
                    'fat_ratio' => $user->fat_ratio,
                    'fiber_ratio' => $user->fiber_ratio,
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا رخ داد.'
            ]);
        }

        $program = $program->fresh(['user', 'days.meals.items.food', 'days.meals.items.unit', 'supplements.supplement', 'supplements.unit']);
        UserDietProgram::where('diet_id', $program->id)->delete();
        HelperDietProgram::copy($program);

        return response()->json([
            'success' => true,
            'message' => 'برنامه با موفقیت ویرایش شد.',
            'redirect_url' => route('admin_diet_program_edit', ['id' => $program->id])
        ]);
    }

    private function getUnitValue(FoodUnit $unit, $calorie, $protein, $carbs, $fat): float
    {
        if (empty($calorie) || empty($unit->real_calorie))
            return 0;

        $calorieValue = $calorie / $unit->real_calorie;
        $proteinValue = empty($protein) || empty($unit->real_protein) ? PHP_INT_MAX : $protein / $unit->real_protein;
        $carbsValue = empty($carbs) || empty($unit->real_carbs) ? PHP_INT_MAX : $carbs / $unit->real_carbs;
        $fatValue = empty($fat) || empty($unit->real_fat) ? PHP_INT_MAX : $fat / $unit->real_fat;

        $min = min($calorieValue, $proteinValue);
        $min = min($min, $carbsValue);
        $min = min($min, $fatValue);

        return round($min, 1);
    }

    public function pdf($id, Request $request)
    {
        $program = DietProgram::with(['days.meals.items.unit', 'days.meals.items.food.cooking.instructions', 'days.meals.items.food.cooking.ingredients', 'user', 'supplements.supplement', 'supplements.unit'])->findOrFail($id);
        $themePrefix = '/themes/zinzer/assets/';

        $axisX = array();
        $calorieAxisY = array();
        $carbsAxisY = array();
        $proteinAxisY = array();
        $fatAxisY = array();
        $weightAxisX = array();
        $weightAxisY = array();

        $i = 0;
        $lastDate = null;
        while ($lastDate == null || $program->created_at < $lastDate) {
            $lastDate = $program->created_at->addDays($program->user->diet_program_period - $i);
            array_push($axisX, Jalalian::fromCarbon($lastDate)->format('Y-m-d'));

            $relation = UserDateRelation::where('user_id', $program->user->id)
                ->whereDate('date', $lastDate)
                ->first();

            array_push($calorieAxisY, $relation == null ? 0 : $relation->food_calories);
            array_push($carbsAxisY, $relation == null ? 0 : $relation->food_carbs);
            array_push($proteinAxisY, $relation == null ? 0 : $relation->food_protein);
            array_push($fatAxisY, $relation == null ? 0 : $relation->food_fat);
            $i++;
        }

        $i = 0;
        $weightLastDate = null;
        while ($weightLastDate == null || $program->created_at < $weightLastDate) {
            $weightLastDate = $program->created_at->addDays($program->user->diet_program_period - $i);
            array_push($weightAxisX, Jalalian::fromCarbon($weightLastDate)->format('Y-m-d'));

            $weight = UserWeight::where('user_id', $program->user->id)
                ->whereBetween('date', [$weightLastDate->format('Y-m-d'), $weightLastDate->addDays(7)->format('Y-m-d')])
                ->avg('weight');

            if ($weight == null)
                $weight = 0;

            array_push($weightAxisY, $weight);
            $i += 7;
        }

        $extra = [
            'calorie' => [
                'axis_x' => $axisX,
                'axis_y' => $calorieAxisY
            ],
            'carbs' => [
                'axis_x' => $axisX,
                'axis_y' => $carbsAxisY
            ],
            'protein' => [
                'axis_x' => $axisX,
                'axis_y' => $proteinAxisY
            ],
            'fat' => [
                'axis_x' => $axisX,
                'axis_y' => $fatAxisY
            ],
            'weight' => [
                'axis_x' => $weightAxisX,
                'axis_y' => $weightAxisY
            ],
        ];

        $cooks = [];
        foreach ($program->days as $day) {
            foreach ($day->meals as $meal) {
                foreach ($meal->items as $item) {
                    if ($item->food->cooking != null && !$this->inArray($cooks, $item->food->cooking) && (count($item->food->cooking->ingredients) || count($item->food->cooking->instructions)))
                        array_push($cooks, $item->food->cooking);
                }
            }
        }

        return view('admin.nutrition.pdf', compact('program', 'themePrefix', 'extra', 'cooks'));
    }

    protected function inArray($arr, $Item)
    {
        foreach ($arr as $item) {
            if ($item == $Item)
                return true;
        }

        return false;
    }

    private function createDiet(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $admin = $request->user('web');

        $meals = collect(json_decode($request->meals, true));
        $supplements = collect(json_decode($request->supplements, true));

        try {
            DB::beginTransaction();

            $program = DietProgram::create([
                'note' => $request->note,
                'protein' => $request->protein,
                'carbs' => $request->carbs,
                'fat' => $request->fat,
                'user_id' => $request->user_id,
                'program_period' => $user->diet_program_period,
                'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
                'writer_id' => $admin->id
            ]);

            foreach ($supplements as $supplement)
                DietProgramSupplement::create([
                    'program_id' => $program->id,
                    'unit_id' => empty($supplement['value']) ? null : $supplement['unit_id'],
                    'value' => empty($supplement['value']) ? null : $supplement['value'],
                    'unit_text' => empty($supplement['unit_text']) ? null : $supplement['unit_text'],
                    'text' => $supplement['text'],
                    'supplement_id' => $supplement['supplement_id']
                ]);

            $this->createDay($program, $meals, 'monday');
            $this->createDay($program, $meals, 'tuesday');
            $this->createDay($program, $meals, 'wednesday');
            $this->createDay($program, $meals, 'thursday');
            $this->createDay($program, $meals, 'friday');
            $this->createDay($program, $meals, 'saturday');
            $this->createDay($program, $meals, 'sunday');

            $user->update([
                'protein_ratio' => $request->protein,
                'fat_ratio' => $request->fat,
                'calorie_ratio' => 4 *  $request->protein + 9 * $request->fat + 4 *  $request->carbs,
                'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
            ]);

            $relation = UserDateRelation::where('user_id', $user->id)
                ->whereDate('date', now())
                ->first();

            if ($relation != null)
                $relation->update([
                    'calorie_ratio' => $user->calorie_ratio,
                    'protein_ratio' => $user->protein_ratio,
                    'fat_ratio' => $user->fat_ratio,
                    'fiber_ratio' => $user->fiber_ratio,
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا رخ داد.'
            ]);
        }

        $program = $program->fresh(['user', 'days.meals.items']);

        $userProgram = HelperDietProgram::copy($program);

        Notification::send($userProgram->user, new UserDietProgramSentNotification($userProgram));

        return response()->json([
            'success' => true,
            'message' => 'برنامه تغذیه با موفقیت ارسال شد.',
            'redirect_url' => route('admin_diet_program_edit', ['id' => $program->id])
        ]);
    }
}
