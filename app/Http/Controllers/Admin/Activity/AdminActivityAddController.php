<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminActivityAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن فعالیت',
                'description' => 'افزودن فعالیت'
            ];

            return view('admin.activity.add', compact('page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'icon' => 'required|string|max:191',
            'met' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $activity = Activity::create([
            'name_fa' => $request->name_fa,
            'name_en' => $request->name_en,
            'icon' => $request->icon,
            'met' => $request->met,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'فعالیت با موفقیت افزوده شد.',
            'redirect_url' => route('admin_activity_edit', ['id' => $activity->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $activity = Activity::findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن فعالیت',
                'description' => 'ویرایش فعالیت'
            ];

            return view('admin.activity.add', compact('activity', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'icon' => 'required|string|max:191',
            'met' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $activity = Activity::findOrFail($request->id);

        $activity->update([
            'name_fa' => $request->name_fa,
            'name_en' => $request->name_en,
            'icon' => $request->icon,
            'met' => $request->met,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'فعالیت با موفقیت ویرایش شد.'
        ]);
    }
}
