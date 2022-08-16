<?php

namespace App\Http\Controllers\Api\User\Activity;

use App\Helpers\HelperUser;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\UserActivity;
use App\Models\UserDateRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class UserActivityController extends Controller
{
    public function list(Request $request)
    {
        $activities = Activity::orderByDesc('created_at');

        if ($request->has('q'))
            $activities->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $activities->paginate();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0',
            'date' => 'required|date',
            'minutes' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        if (!$user->profile_completed)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.complete_your_profile')
            ]);

        $activity = Activity::find($request->id);

        if ($activity == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        $relation = UserDateRelation::where('date', $request->date)
            ->where('user_id', $user->id)->first();

        if ($relation == null)
            $relation = UserDateRelation::create([
                'user_id' => $user->id,
                'calorie_ratio' => $user->calorie_ratio,
                'protein_ratio' => $user->protein_ratio,
                'fiber_ratio' => $user->fiber_ratio,
                'fat_ratio' => $user->fat_ratio,
                'date' => $request->date,
            ]);

        $userActivity = UserActivity::create([
            'relation_id' => $relation->id,
            'name_fa' => $activity->name_fa,
            'name_en' => $activity->name_en,
            'minutes' => $request->minutes,
            'calorie_ratio' => HelperUser::getActivityCalorieRatio($activity->met, $user->weight, $request->minutes)
        ]);

        $relation = $relation->fresh();

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.added_successfully'),
            'activity' => $userActivity,
            'relation' => $relation
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        $activity = UserActivity::whereHas('relation', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($request->id);

        $relation = $activity->relation;

        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.deleted_successfully'),
            'relation' => $relation
        ]);
    }
}
