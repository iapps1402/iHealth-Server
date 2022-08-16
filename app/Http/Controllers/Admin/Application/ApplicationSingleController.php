<?php

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationSingleController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن اپلیکیشن',
                'description' => 'افزودن اپلیکیشن'
            ];

            return view('admin.application.single', compact('page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $application = Application::create([
            'name_fa' => $request->name_fa,
            'name_en' => $request->name_en,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'اپلیکیشن با موفقیت افزوده شد.',
            'redirect_url' => route('admin_application_edit', ['id' => $application->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $application = Application::findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش اپلیکیشن',
                'description' => 'ویرایش اپلیکیشن'
            ];

            return view('admin.application.single', compact('application', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $applications = Application::findOrFail($request->id);

        $applications->update([
            'name_fa' => $request->name_fa,
            'name_en' => $request->name_en,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'اپلیکیشن با موفقیت ویرایش شد.'
        ]);
    }
}
