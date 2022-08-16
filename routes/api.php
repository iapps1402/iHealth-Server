<?php

use App\Http\Controllers\Api\ApiRedirectController;
use App\Http\Controllers\Api\User\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
    Route::any('/splash', 'ApiSplashController@index');
    Route::prefix('user')->namespace('User')->group(function () {

        Route::prefix('sign-up')->namespace('SignUp')->group(function () {
            Route::post('step1', 'UserSignUpStep1Controller@index');
            Route::post('step2', 'UserSignUpStep2Controller@index');
            Route::post('google', 'UserSignUpGoogleController@index');
        });

        Route::prefix('sign-up-own')->namespace('SignUpOwn')->group(function () {
            Route::post('step1', 'UserSignUpStep1Controller@index');
            Route::post('step2', 'UserSignUpStep2Controller@index');
            Route::post('google', 'UserSignUpGoogleController@index');
        });

        Route::middleware('user_auth')->group(function () {

            Route::prefix('main')->namespace('Main')->group(function () {
                Route::post('', 'MainBaseController@index');
                Route::post('home', 'MainBaseController@home');
                Route::post('version/{os}/{version_name}', 'MainVersionController@index');
                Route::post('block1/details', 'MainBlockController@block1');
                Route::post('block2/details', 'MainBlockController@block2');
                Route::post('block3/details', 'MainBlockController@block3');
            });

            Route::prefix('support')->namespace('Support')->group(function () {
                Route::post('list', 'SupportListController@index');
            });

            Route::prefix('payment')->namespace('Payment')->group(function () {
                Route::post('details', 'PaymentDetailsController@index');
                Route::post('add', 'PaymentController@add');
            });

            Route::prefix('feedback')->namespace('Feedback')->group(function () {
                Route::post('send', 'FeedbackMainController@index');
            });

            Route::prefix('diet')->namespace('Diet')->group(function () {
                Route::post('list', 'DietListController@index');
                Route::prefix('{id}')->group(function () {
                    Route::post('', 'DietDetailsController@index');
                    Route::post('chart', 'DietChartController@index');
                    Route::prefix('item')->group(function () {
                        Route::post('change', 'DietMealItemController@changeFood');
                        Route::post('value', 'DietMealItemController@changeValue');
                        Route::post('remove', 'DietMealItemController@remove');
                        Route::post('add', 'DietMealItemController@add');
                    });
                });

            });

            Route::prefix('profile')->group(function () {
                Route::post('details', 'UserProfileController@details');
                Route::post('send', 'UserProfileController@send');
            });

            Route::prefix('settings')->group(function () {
                Route::post('details', 'UserSettingsController@details');
                Route::post('lang', 'UserSettingsController@lang');
            });

            Route::prefix('suggestion')->namespace('Suggestion')->group(function () {
                Route::post('food', 'SuggestionFoodController@index');
            });

            Route::prefix('food')->namespace('Food')->group(function () {
                Route::post('search', 'FoodSearchController@index');

                Route::post('add', 'FoodSingleController@add');
                Route::post('edit', 'FoodSingleController@edit');
                Route::post('delete', 'FoodSingleController@delete');

                Route::post('add/custom', 'FoodCustomController@add');
                Route::prefix('custom')->group(function () {
                    Route::post('add', 'FoodCustomController@add');
                    Route::post('edit', 'FoodCustomController@edit');
                });

                Route::post('compare', 'FoodCompareController@index');
                Route::post('barcode', 'FoodBarcodeController@index');
                Route::post('list', 'FoodListController@index');
                Route::post('details', 'FoodDetailsController@index');
                Route::post('{id}/similarities', 'FoodSingleController@similarities');
                Route::post('cooking/{id}', 'FoodCookingController@index');
            });

            Route::prefix('invitation')->group(function () {
                Route::post('details', 'InvitationController@details');
                Route::post('send', 'InvitationController@send');
            });

            Route::prefix('firebase')->namespace('Firebase')->group(function () {
                Route::post('token', 'FirebaseController@getToken');
            });

            Route::prefix('post')->namespace('Post')->group(function () {
                Route::post('list', 'PostListController@index');
                Route::any('{id}', 'PostSingleController@index')->name('post_api_url');
            });

            Route::prefix('weight')->namespace('Weight')->group(function () {
                Route::post('add', 'WeightIndexController@add');
                Route::post('settings/send', 'WeightSettingsController@send');
                Route::post('settings/details', 'WeightSettingsController@details');
                Route::post('chart', 'WeightChartController@index');
            });

            Route::prefix('activity')->namespace('Activity')->group(function () {
                Route::post('list', 'UserActivityController@list');
                Route::post('add', 'UserActivityController@add');
                Route::post('delete', 'UserActivityController@delete');
            });

            Route::prefix('chat')->namespace('Chat2')->group(function () {
                Route::post('send', 'ChatSendController@index');
             //   Route::post('upload', 'ChatSendController@upload');
                Route::post('{id}/messages', 'ChatController@pv');
                Route::post('messages', 'ChatController@all');

                Route::post('contacts', 'ContactController@index');
            });

            Route::prefix('chat')->namespace('Chat')->group(function () {
                Route::post('check', 'ChatUserCheckController@index');

            });

        });
    });
});
Route::get('payment/redirect/{client}/{id}/{token}', [PaymentController::class, 'pay']);
Route::any('callback/{client}', [PaymentController::class, 'callback'])->name('payment_callback');
Route::any('redirect/{page}', [ApiRedirectController::class, 'index']);
