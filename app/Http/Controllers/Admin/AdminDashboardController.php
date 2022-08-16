<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\DietProgram;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\User;
use App\Models\UserAppRating;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $blogPostsCount = BlogPost::where('status', 'published')->count();

        $lastBlogPost = BlogPost::orderByDesc('created_at')->first();
        if ($lastBlogPost != null)
            $lastBlogPost = Jalalian::fromCarbon($lastBlogPost->created_at)->ago();
        $usersCount = User::count();
        $onlineUsersCount = User::whereBetween('online_at', [now()->addMinutes(-10), now()])->count();

        $foodsCount = Food::count();
        $foodCategoriesCount = FoodCategory::count();

        $dietProgramsCount = DietProgram::count();

        $lastDietProgram = DietProgram::orderByDesc('created_at')->first();

        if ($lastDietProgram != null)
            $lastDietProgram = Jalalian::fromCarbon($lastDietProgram->first()->created_at)->ago();

        $page = [
            'title' => 'داشبورد',
            'description' => 'داشبورد'
        ];

        $appRatingsCount = UserAppRating::count();
        $appRatingsAvg = UserAppRating::avg('rating');

        return view('admin.dashboard', compact(['blogPostsCount', 'usersCount', 'onlineUsersCount', 'page', 'lastBlogPost', 'foodsCount', 'foodCategoriesCount', 'dietProgramsCount', 'lastDietProgram', 'appRatingsCount', 'appRatingsAvg']));
    }
}
