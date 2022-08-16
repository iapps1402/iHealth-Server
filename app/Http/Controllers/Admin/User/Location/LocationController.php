<?php

namespace App\Http\Controllers\Admin\User\Location;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    private function generateItems($userId, Request $request)
    {
        return Location::orderByDesc('id')
            ->where('user_id', $userId)->paginate();
    }

    public function index($userId, Request $request)
    {
        if ($request->isMethod('GET')) {
            $locations = $this->generateItems($userId, $request);

            $page = [
                'title' => 'مدیریت لوکیشن های دریافت شده',
                'description' => 'مدیریت لوکیشن های دریافت شده'
            ];

            return view('admin.user.location', compact('locations', 'page'));
        } else {
            switch ($request->action) {
                case 'delete':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    Location::findOrFail($request->id)->delete();

                    $locations = $this->generateItems($userId, $request);

                    return response()->json([
                        'success' => true,
                        'message' => 'سلفی با موفقیت حذف شد.',
                        'view' => view('admin.user.components.location_table', compact('locations'))->render(),
                    ]);

                case 'refresh':
                    $locations = $this->generateItems($userId, $request);

                    return response()->json([
                        'success' => true,
                        'view' => view('admin.user.components.location_table', compact('locations'))->render(),
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد action ارسال نشده است.'
                    ]);
            }
        }
    }

    public function details($id, $latitude, $longitude)
    {
        return view('admin.user.location_details', compact(['latitude', 'longitude']));
    }
}
