<?php

namespace App\Http\Controllers\Admin\Application\Version\Change;

use App\Http\Controllers\Controller;
use App\Models\ApplicationVersionChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VersionChangeManageController extends Controller
{
    private function generateItems($applicationId, $versionId, Request $request)
    {
        return ApplicationVersionChange::where('version_id', $versionId)
            ->where('version_id', $versionId)
            ->orderByDesc('id')
            ->paginate();
    }

    public function index($applicationId, $versionId, Request $request)
    {
        if ($request->isMethod('GET')) {
            $changes = $this->generateItems($applicationId, $versionId, $request);

            $page = [
                'title' => 'مدیریت تغییرات',
                'description' => 'مدیریت تغییرات'
            ];

            return view('admin.application.version.change.manage', compact('changes', 'page', 'applicationId', 'versionId'));
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

                    ApplicationVersionChange::findOrFail($request->id)->delete();

                    $changes = $this->generateItems($applicationId, $versionId, $request);

                    return response()->json([
                        'success' => true,
                        'message' => 'تغییر با موفقیت حذف شد.',
                        'view' => view('admin.application.version.change.components.manage_table', compact('changes'))->render(),
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد action ارسال نشده است.'
                    ]);
            }
        }
    }
}
