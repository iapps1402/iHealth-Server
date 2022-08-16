<?php

namespace App\Http\Controllers\Admin\Slider;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminSliderAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن اسلایدشو',
                'description' => 'افزودن اسلایدشو'
            ];

            return view('admin.slider.add', compact('page'));
        }

        $validator = Validator::make($request->all(), [
            'picture' => 'required|mimes:jpeg,jpg,png|max:20000',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $slider = Slider::create([
            'picture_id' => HelperMedia::uploadPicture($request->picture, 'slider/', 800, 400),
            'url' => $request->url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'اسلاید با موفقیت افزوده شد.',
            'redirect_url' => route('admin_slider_edit', [
                'id' => $slider->id
            ])
        ]);
    }

    public function edit($id, Request $request)
    {
        $slider = Slider::with(['picture.thumbnail'])->findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش اسلایدشو',
                'description' => 'ویرایش اسلایدشو'
            ];

            return view('admin.slider.add', compact('slider', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'picture' => 'nullable|mimes:jpeg,jpg,png|max:20000',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $slider->update([
            'picture_id' => $request->picture == null ? $slider->picture_id : HelperMedia::uploadPicture($request->picture, 'slider/', 800, 400, false, $slider->media),
            'url' => $request->url
        ]);

        $slider = $slider->fresh(['picture.thumbnail']);

        return response()->json([
            'success' => true,
            'message' => 'اسلاید با موفقیت ویرایش شد.',
            'slider' => $slider
        ]);
    }
}
