<?php

namespace App\Http\Controllers\Admin\Support;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminSupportSingleController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {

            $page = [
                'title' => 'افزودن پشتیبان',
                'description' => 'افزودن پشتیبان',
            ];

            return view('admin.support.single', compact('page'));
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:191',
            'whatsapp_number' => 'required|string',
            'telegram_id' => 'nullable|string',
            'photo' => 'required|mimes:jpeg,jpg,png|max:20000'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $support = Support::create([
            'full_name' => $request->full_name,
            'whatsapp_number' => $request->whatsapp_number,
            'telegram_id' => $request->telegram_id,
            'photo_id' => HelperMedia::uploadPicture($request->file('photo'), 'supports/photos/', 300, 300)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'پشتیبان با موفقیت افزوده شد.',
            'redirect_url' => Route('admin_support_edit', ['id' => $support->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $support = Support::with(['photo.thumbnail'])->findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش پشتیبان',
                'description' => 'ویرایش پشتیبان',
            ];

            return view('admin.support.single', compact('support', 'page'));
        }

        $validator = Validator::make(Request()->all(), [
            'full_name' => 'required|string|max:191',
            'whatsapp_number' => 'required|string',
            'telegram_id' => 'nullable|string',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:20000'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $support->update([
            'full_name' => $request->full_name,
            'whatsapp_number' => $request->whatsapp_number,
            'telegram_id' => $request->telegram_id,
            'photo_id' => empty($request->photo) ? $support->photo_id : HelperMedia::uploadPicture($request->file('photo'), 'supports/photos/', 300, 300, false, $support->photo),

        ]);

        $support = $support->fresh(['photo.thumbnail']);

        return response()->json([
            'success' => true,
            'message' => 'پشتیبان با موفقیت ویرایش شد.',
            'support' => $support
        ]);
    }
}
