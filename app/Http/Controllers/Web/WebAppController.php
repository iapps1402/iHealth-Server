<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class WebAppController extends Controller
{
    public function update($lang)
    {
        return redirect('https://dopaminefit.ir/app-update/');
        if ($lang != 'fa' && $lang != 'en')
            return abort(404);

        $title = $lang == 'fa' ? 'به روز رسانی نرم افزار' : 'Update App';

        return view('web.app.update', compact('title', 'lang'));
    }
}
