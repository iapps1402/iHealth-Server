<?php

namespace App\Http\Controllers\Admin\Application\Version;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationVersionSingleController extends Controller
{
    public function add($applicationId, Request $request)
    {
        Application::findOrFail($applicationId);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن ورژن',
                'description' => 'افزودن ورژن'
            ];

            return view('admin.application.version.single', compact('page', 'applicationId'));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'number' => 'required|numeric|not_in:0',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $version = ApplicationVersion::create([
            'number' => $request->number,
            'name' => $request->name,
            'application_id' => $applicationId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ورژن با موفقیت افزوده شد.',
            'redirect_url' => route('admin_application_version_edit', ['id' => $version->id, 'application_id' => $applicationId])
        ]);
    }

    public function edit($applicationId, $id, Request $request)
    {
        $version = ApplicationVersion::where('application_id', $applicationId)
            ->findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش ورژن',
                'description' => 'ویرایش ورژن'
            ];

            return view('admin.application.version.single', compact('version', 'page', 'applicationId'));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'number' => 'required|numeric|not_in:0',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $version = ApplicationVersion::findOrFail($request->id);

        $version->update([
            'name' => $request->name,
            'number' => $request->number
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ورژن با موفقیت ویرایش شد.'
        ]);
    }
}
