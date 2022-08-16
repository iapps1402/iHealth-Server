<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiRedirectController extends Controller
{
    public function index($page)
    {
        if($page == 'about')
            return redirect('https://dopaminefit.ir/%d8%af%d8%b1%d8%a8%d8%a7%d8%b1%d9%87-%d9%85%d8%a7/');

        if($page == 'contact')
            return redirect('https://dopaminefit.ir/contact-us/');

        if($page == 'program')
            return redirect('https://dopaminefit.ir/barname');

        if($page == 'donate')
            return redirect('https://dopaminefit.ir/donate');

        if($page == 'get_advice')
            return redirect('https://dopaminefit.ir/get_advice');

        try {
            return abort(404);
        } catch (NotFoundHttpException $e) {
        } catch (HttpException $e) {
            return null;
        }
    }
}
