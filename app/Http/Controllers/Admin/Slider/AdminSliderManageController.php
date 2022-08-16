<?php

namespace App\Http\Controllers\Admin\Slider;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminSliderManageController extends Controller
{
    private function generateItems(Request $request)
    {
        return Slider::orderByDesc('id')->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            $sliders = $this->generateItems($request);

            $page = [
                'title' => 'مدیریت اسلایدشو',
                'description' => 'مدیریت اسلایدشو'
            ];

            return view('admin.slider.manage', compact('sliders', 'page'));
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

                $slider = Slider::find($request->id);
                if ($slider == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'اسلایدر یافت نشد.'
                    ]);

                try {
                    DB::beginTransaction();
                    HelperMedia::delete($slider->media_id);
                    $slider->delete();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'خطایی به هنگام انجام عملیات رخ داد.'
                    ]);
                }

                $sliders = $this->generateItems($request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.slider.components.manage_table', compact('sliders'))->render(),
                    'message' => 'اسلایدر با موفقیت حذف شد.'
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);
        }
    }
}
