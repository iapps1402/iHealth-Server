<?php

namespace App\Http\Controllers\Api\User\Weight;

use App\Http\Controllers\Controller;
use App\Models\UserDateRelation;
use App\Models\UserWeight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class WeightChartController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'months' => 'required|in:1,3,6,12'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = Auth::guard('api')->user();

        $days = (($request->months * 30) / 7);

        $axisX = array();
        $axisY = array();
        $oldWeight = 0;
        for ($i = 0; $i < 7; $i++) {
            array_push($axisX, Jalalian::fromCarbon(now())->addDays(-$i * $days)->format('y/m/d'));
            $weight = UserWeight::where('user_id', $user->id)
                ->whereDate('date', '>=', Carbon::parse(now())->addDays(-$i * $days))
                ->whereDate('date', '<', Carbon::parse(now())->addDays((-$i * $days) + 7))
                ->avg('weight');

            array_push($axisY, $weight == null ? $oldWeight : $weight);
            if ($weight != null)
                $oldWeight = $weight;
        }
        return response()->json([
            'success' => true,
            'axis_y' => $axisY,
            'axis_x' => $axisX,
        ]);
    }
}
