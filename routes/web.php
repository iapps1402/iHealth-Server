<?php

use App\Notifications\UserDietProgramSentNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', 'UpdateAppController@index');

Route::namespace('Web')->group(function () {
    Route::get('/{lang}/update', 'WebAppController@update');
    Route::get('/', 'WebIndexController@index');
    Route::get('/update', 'UpdateController@index');
});

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::match(['post', 'get'], '', 'AdminAuthController@login')->name('admin_login');
    Route::match(['post', 'get'], 'logout', 'AdminAuthController@logout')->name('admin_logout');

    Route::middleware('admin_auth')->group(function () {
        Route::get('dashboard', 'AdminDashboardController@index')->name('admin_dashboard');

        Route::prefix('upload')->namespace('Upload')->group(function () {
            Route::match(['get', 'post'], '/manage', 'AdminUploadManageController@index')->name('admin_upload_manage');
        });

        Route::prefix('slider')->namespace('Slider')->group(function () {
            Route::match(['get', 'post'], '', 'AdminSliderManageController@index')->name('admin_slider_manage');
            Route::match(['get', 'post'], 'add', 'AdminSliderAddController@add')->name('admin_slider_add');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminSliderAddController@edit')->name('admin_slider_edit');
        });

        Route::prefix('post')->namespace('Post')->group(function () {
            Route::match(['get', 'post'], 'add', 'AdminBlogPostAddController@add')->name('admin_blog_post_add');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminBlogPostAddController@edit')->name('admin_blog_post_edit');
            Route::match(['get', 'post'], 'manage', 'AdminBlogPostManageController@index')->name('admin_blog_post_manage');
            Route::match(['get', 'post'], 'categories/{id}', 'AdminBlogCategoryController@index')->name('admin_blog_post_category');
        });

        Route::prefix('activity')->namespace('Activity')->group(function () {
            Route::match(['get', 'post'], 'add', 'AdminActivityAddController@add')->name('admin_activity_add');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminActivityAddController@edit')->name('admin_activity_edit');
            Route::match(['get', 'post'], 'manage', 'AdminActivityManageController@index')->name('admin_activity_manage');
        });

        Route::prefix('diet')->namespace('Diet')->group(function () {
            Route::match(['get', 'post'], 'list', 'DietProgramManageController@index')->name('admin_diet_program_manage');
            Route::match(['get', 'post'], 'add', 'DietProgramSingleController@add')->name('admin_diet_program_add');
            Route::match(['get', 'post'], 'edit/{id}', 'DietProgramSingleController@edit')->name('admin_diet_program_edit');
            Route::match(['get', 'post'], 'pdf/{id}', 'DietProgramSingleController@pdf')->name('admin_diet_program_pdf_output');
            Route::get( 'details/{id}', 'DietProgramSingleController@details')->name('admin_diet_program_details');
            Route::post( 'action/{action}', 'DietProgramSingleController@action')->name('admin_diet_program_action');
        });

        Route::prefix('user')->namespace('User')->group(function () {
            Route::match(['get', 'post'], 'add', 'UserSingleController@add')->name('admin_user_add');
            Route::match(['get', 'post'], 'list', 'UserManageController@index')->name('admin_user_manage');
            Route::match(['get', 'post'], 'edit/{id}', 'UserSingleController@edit')->name('admin_user_edit');
        });

        Route::prefix('application')->namespace('Application')->group(function () {
            Route::match(['get', 'post'], 'add', 'ApplicationSingleController@add')->name('admin_application_add');
            Route::match(['get', 'post'], 'edit/{id}', 'ApplicationSingleController@edit')->name('admin_application_edit');
            Route::match(['get', 'post'], 'list', 'ApplicationManageController@index')->name('admin_application_manage');

            Route::prefix('{application_id}/version')->namespace('Version')->group(function () {
                Route::match(['get', 'post'], 'add', 'ApplicationVersionSingleController@add')->name('admin_application_version_add');
                Route::match(['get', 'post'], 'edit/{id}', 'ApplicationVersionSingleController@edit')->name('admin_application_version_edit');
                Route::match(['get', 'post'], 'list', 'ApplicationVersionManageController@index')->name('admin_application_version_manage');

                Route::prefix('{version_id}/change')->namespace('Change')->group(function () {
                    Route::match(['get', 'post'], 'add', 'VersionChangeSingleController@add')->name('admin_application_version_change_add');
                    Route::match(['get', 'post'], 'edit/{id}', 'VersionChangeSingleController@edit')->name('admin_application_version_change_edit');
                    Route::match(['get', 'post'], 'list', 'VersionChangeManageController@index')->name('admin_application_version_change_manage');
                });

            });
        });

        Route::prefix('support')->namespace('Support')->group(function () {
            Route::match(['get', 'post'], 'list', 'AdminSupportManageController@index')->name('admin_support_manage');
            Route::match(['get', 'post'], 'add', 'AdminSupportSingleController@add')->name('admin_support_add');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminSupportSingleController@edit')->name('admin_support_edit');
        });

        Route::prefix('food')->namespace('Food')->group(function () {
            Route::match(['get', 'post'], 'add', 'AdminFoodAddController@add')->name('admin_food_add');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminFoodAddController@edit')->name('admin_food_edit');
            Route::match(['get', 'post'], 'list', 'AdminFoodManageController@index')->name('admin_food_manage');

            Route::prefix('{food_id}/unit')->namespace('Unit')->group(function () {
                Route::match(['get', 'post'], 'list', 'AdminFoodUnitManageController@index')->name('admin_food_unit_manage');
                Route::match(['get', 'post'], 'add', 'AdminFoodUnitAddController@add')->name('admin_food_unit_add');
                Route::match(['get', 'post'], 'edit/{id}', 'AdminFoodUnitAddController@edit')->name('admin_food_unit_edit');

                Route::prefix('{unit_id}/material')->namespace('Material')->group(function () {
                    Route::match(['get', 'post'], 'list', 'AdminFoodUnitMaterialController@index')->name('admin_food_unit_material_manage');
                    Route::match(['get', 'post'], 'add', 'AdminFoodUnitMaterialAddController@index')->name('admin_food_unit_material_add');
                });
            });

            Route::prefix('suggestion')->namespace('Suggestion')->group(function () {
                Route::match(['get', 'post'], 'list', 'AdminFoodSuggestionManageController@index')->name('admin_food_suggestion_manage');
                Route::match(['get', 'post'], 'add', 'AdminFoodSuggestionAddController@add')->name('admin_food_suggestion_add');
            });

            Route::prefix('{food_id}/cooking')->namespace('Cooking')->group(function () {
                Route::match(['get', 'post'], 'ingredient', 'CookingIngredientController@index')->name('admin_cooking_ingredient_manage');
                Route::match(['get', 'post'], 'instruction', 'CookingInstructionController@index')->name('admin_cooking_instruction_manage');
            });

            Route::prefix('category')->namespace('Category')->group(function () {
                Route::match(['get', 'post'], 'list', 'AdminFoodCategoryManageController@index')->name('admin_food_category_manage');
                Route::match(['get', 'post'], 'add', 'AdminFoodCategoryAddController@add')->name('admin_food_category_add');
                Route::match(['get', 'post'], 'edit/{id}', 'AdminFoodCategoryAddController@edit')->name('admin_food_category_edit');
            });
        });
    });


});

