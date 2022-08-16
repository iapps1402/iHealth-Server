<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class UserManageController extends Controller
{
    private function getItems(Request $request)
    {
        $users = User::orderByDesc('created_at');

        if ($request->has('q'))
            $users->where(function ($q) use ($request) {
                $q->whereRaw("CONCAT(`first_name`, ' ', `last_name`) like '%$request->q%'")
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNotNull('phone_number')
                            ->where('phone_number', 'like', '%' . $request->q . '%');
                    });
            });

        return $users->paginate();
    }

    public function index(Request $request)
    {
        $admin = $request->user('web')->admin;

        if ($request->isMethod('GET')) {

            $users = $this->getItems($request);

            $page = [
                'title' => 'مدیریت کاربران',
                'description' => 'مدیریت کاربران'
            ];

            return view('admin.user.manage', compact('users', 'page', 'admin'));
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


                    try {
                        User::findOrFail($request->id)->delete();
                    } catch (Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'خطایی به هنگام انجام عملیات رخ داد.'
                        ]);
                    }

                    $users = $this->getItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'کاربر با موفقیت حذف شد.',
                        'view' => view('admin.user.components.manage_table', compact('users', 'admin'))->render()
                    ]);

                case 'customer':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    $user = User::findOrFail($request->id);

                    $user->update([
                        'customer' => !$user->customer
                    ]);

                    $users = $this->getItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'تغییرات با موفقیت اعمال شد.',
                        'view' => view('admin.user.components.manage_table', compact('users', 'admin'))->render()
                    ]);

                case 'contact':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    $user = User::findOrFail($request->id);

                    $user->update([
                        'is_contact' => !$user->is_contact
                    ]);

                    $users = $this->getItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'تغییرات با موفقیت اعمال شد.',
                        'view' => view('admin.user.components.manage_table', compact('users', 'admin'))->render()
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
