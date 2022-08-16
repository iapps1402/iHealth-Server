<?php

namespace App\Http\Controllers\Admin\Application\Version;

use App\Http\Controllers\Controller;
use App\Models\ApplicationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationVersionManageController extends Controller
{
    private function generateItems($applicationId, Request $request)
    {
        $versions = ApplicationVersion::withCount('changes')
            ->where('application_id', $applicationId)
            ->orderByDesc('id');

        if ($request->has('q'))
            $versions->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('number', 'like', '%' . $request->q . '%');
            });

        return $versions->paginate();
    }

    public function index($applicationId, Request $request)
    {
        if ($request->isMethod('GET')) {
            $versions = $this->generateItems($applicationId, $request);

            $page = [
                'title' => 'مدیریت ورژن ها',
                'description' => 'مدیریت ورژن ها'
            ];

            return view('admin.application.version.manage', compact('versions', 'page', 'applicationId'));
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

                    ApplicationVersion::findOrFail($request->id)->delete();

                    $versions = $this->generateItems($applicationId, $request);

                    return response()->json([
                        'success' => true,
                        'message' => 'ورژن با موفقیت حذف شد.',
                        'view' => view('admin.application.version.components.manage_table', compact('versions', 'applicationId'))->render(),
                    ]);

                case 'current_version':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    $version = ApplicationVersion::findOrFail($request->id);

                    $version->application->update([
                        'current_id' => $version->id
                    ]);

                    $versions = $this->generateItems($applicationId, $request);

                    return response()->json([
                        'success' => true,
                        'view' => view('admin.application.version.components.manage_table', compact('versions', 'applicationId'))->render(),
                        'message' => 'تغییرات اعمال شد.'
                    ]);

                case 'min_version':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    $version = ApplicationVersion::findOrFail($request->id);
                    $version->application->update([
                        'min_id' => $version->id
                    ]);

                    $versions = $this->generateItems($applicationId, $request);

                    return response()->json([
                        'success' => true,
                        'view' => view('admin.application.version.components.manage_table', compact('versions', 'applicationId'))->render(),
                        'message' => 'تغییرات اعمال شد.'
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
