<?php

namespace App\Http\Controllers\Api\User\Main;

use App\Http\Controllers\Controller;
use App\Models\DietProgram;
use App\Models\Slider;
use App\Models\UserDateRelation;
use App\Models\UserFood;
use App\Models\UserWeight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class MainBaseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');
        $user->append(['age', 'profile_completed', 'can_be_invited', 'has_food_suggestion']);
        return response()->json([
            'success' => true,
            'current_date' => now()->format('Y-m-d'),
            'user' => $user,
            'profile_badge' => DietProgram::whereNull('user_read_at')->where('user_id', $user->id)->count()
        ]);
    }

    public function home(Request $request)
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

        $notice = null;
        if (Carbon::now()->isSaturday()) {
            $consumedCalorie = UserFood::whereHas('relation', function ($q) use ($user) {
                $q->whereBetween('date', [now()->addDays(-8)->format('Y-m-d'), now()->addDays(-1)->format('Y-m-d')])
                    ->where('user_id', $user->id);
            })->avg(DB::raw('protein*4 + fat*9 + carbs*4'));

            $calorieDiff = 0;

            if ($consumedCalorie > 0)
                $calorieDiff = (abs($user->calorie_ratio - $consumedCalorie) / (double)$user->calorie_ratio) * 100;

            //losing fat
            if ($consumedCalorie == 0 && now()->addDays(-7) >= $user->created_at)
                $notice = Lang::get('messages.notice_no_data_has_been_added');
            elseif ($user->decrease_or_increase_coefficient < 0) {
                if ($calorieDiff < 5)
                    $notice = Lang::get('messages.notice_nice_consume');
                elseif ($user->calorie_ratio > $consumedCalorie)
                    $notice = Lang::get('messages.notice_lose_fat_loss_consume');
                else
                    $notice = Lang::get('messages.notice_over_consume');
            } else if ($user->decrease_or_increase_coefficient == 0) {
                if ($calorieDiff < 5)
                    $notice = Lang::get('messages.notice_nice_consume');
                elseif ($user->calorie_ratio > $consumedCalorie)
                    $notice = Lang::get('messages.notice_maintain_the_same_weight_loss_consume');
                else
                    $notice = Lang::get('messages.notice_over_consume');
            } else {
                if ($calorieDiff < 5)
                    $notice = Lang::get('messages.notice_nice_consume');
                elseif ($user->calorie_ratio > $consumedCalorie)
                    $notice = Lang::get('messages.notice_gain_muscle_loss_consume');
                else if ($calorieDiff < 10)
                    $notice = Lang::get('messages.notice_gain_muscle_over_consume');
                else
                    $notice = Lang::get('messages.notice_over_consume');
            }

            $notice = str_replace(':calorie_ratio', number_format($user->calorie_ratio), $notice);
            $notice = str_replace(':consumed_calories', number_format($consumedCalorie), $notice);
        }

        $user = $request->user('api');
        $user->append(['age', 'profile_completed', 'can_be_invited', 'has_food_suggestion']);

        return response()->json([
            'success' => true,
            'notice' => $notice,
            'sliders' => Slider::with(['picture.thumbnail'])->orderByDesc('created_at')->get(),
            'user' => $user
        ]);
    }
}
