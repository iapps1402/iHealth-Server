<?php

namespace App\Http\Controllers\Admin\Application\Version\Change;

use App\Http\Controllers\Controller;
use App\Models\ApplicationVersion;
use App\Models\ApplicationVersionChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VersionChangeSingleController extends Controller
{
    public function add($applicationId, $versionId, Request $request)
    {
        ApplicationVersion::findOrFail($versionId);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن تغییر جدید',
                'description' => 'تغییر ورژن'
            ];

            return view('admin.application.version.change.single', compact('page', 'applicationId', 'versionId'));
        }

        $validator = Validator::make($request->all(), [
            'text_fa' => 'required|string|max:191',
            'text_en' => 'required|string|max:191',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $version = ApplicationVersionChange::create([
            'text_fa' => $request->text_fa,
            'text_en' => $request->text_en,
            'version_id' => $versionId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تغییر با موفقیت افزوده شد.',
            'redirect_url' => route('admin_application_version_change_edit', ['id' => $version->id, 'application_id' => $applicationId, 'version_id' => $versionId])
        ]);
    }

    public function edit($applicationId, $versionId, $id, Request $request)
    {
        $change = ApplicationVersionChange::where('version_id', $versionId)
            ->findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش تغییر',
                'description' => 'ویرایش تغییر'
            ];

            return view('admin.application.version.change.single', compact('change', 'page', 'applicationId', 'versionId'));
        }

        $validator = Validator::make($request->all(), [
            'text_fa' => 'required|string|max:191',
            'text_en' => 'required|string|max:191',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $change = ApplicationVersionChange::findOrFail($request->id);

        $change->update([
            'text_fa' => $request->text_fa,
            'text_en' => $request->text_en,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تغییر با موفقیت ویرایش شد.'
        ]);
    }
}
