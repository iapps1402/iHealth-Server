<?php


namespace App\Http\View;


use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
class ViewComposerAdmin
{
    public function compose(View $view)
    {
        $admin = Admin::where('user_id', Auth::guard('web')->id())->first();
        $view->with([
            'admin' => $admin,
            'themePrefix' => '/themes/zinzer/assets/'
        ]);
    }
}
