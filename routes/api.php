<?php
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStatusController;
use App\Http\Controllers\WorkerAuthController;
use App\Http\Controllers\WorkerReviewController;
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

Route::prefix('auth')->group(function () {
    Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
        Route::post('/login', 'login')->name('admin.login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
    Route::prefix('worker')->group(function () {
        Route::post('/login', [WorkerAuthController::class, 'login'])->name('worker.login');
        Route::post('/register', [WorkerAuthController::class, 'register']);
        Route::post('/logout', [WorkerAuthController::class, 'logout']);
        Route::post('/refresh', [WorkerAuthController::class, 'refresh']);
        Route::get('/user-profile', );

        Route::get('/verify/{token}', [WorkerAuthController::class, 'Verify']);
        // Route::get('/verify/{token}', function(){
        //     dd(1);
        // });

    });
    Route::prefix('client')->group(function () {
        Route::post('/login', [ClientAuthController::class, 'login'])->name('client.login');
        Route::post('/register', [ClientAuthController::class, 'register']);
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::post('/refresh', [ClientAuthController::class, 'refresh']);
        // Route::get('/user-profile', );
        route::get('/verify/{token}', [ClientAuthController::class, 'Verify']);
        // route::get('/verify/{token}', function () {
        //     dd(123);
        // });
    });
    Route::controller(PostController::class)->group(function () {
        Route::post('post/store', 'store')->middleware("auth:worker");
        Route::get('/show', 'show');
    });
    Route::controller(AdminNotificationController::class)->prefix('admin')->group(function () {
        // Route::post('/login', 'login')->name('AdminNotificationController.login');
        Route::get('/notification', 'index');
        Route::get('/notification/{id}', 'show');
        Route::get('/unreadNotification', 'unreadAll');
        Route::post('/markAsReadNotification/{id}', 'markAsRead');
        Route::post('/markAsReadAllNotification', 'markAsReadAll');
        Route::delete('/notification/delete/{id}', 'delete');
        Route::delete('/notification/deleteAll', 'deleteAll');
    });
    Route::controller(PostStatusController::class)->prefix('admin/post')->middleware('auth:admin')->group(function () {
        Route::get('/pending', 'showPending');
        Route::post('/changeStatus', 'changeStatus');
    });
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('/showAll', 'getAllOrders');
        Route::get('/show/{id}', 'getOrderById');
        Route::get('/show/client/{id}', 'getOrdersByClientId');
        Route::post('/store', 'addOrder');
        Route::post('/update/{orderId}', 'update');
        Route::delete('/delete/{orderId}', 'delete');
        Route::delete('/deleteAll', 'deleteAll');
        Route::post('/is_completed/{id}', 'isCompleted')->middleware('auth:admin');
        Route::post('/is_paid/{id}', 'isPaid')->middleware('auth:admin');
        Route::post('/is_cancelled/{id}', 'is_cancelled')->middleware('auth:admin');
    });
    Route::controller(WorkerReviewController::class)->prefix('review')->group(function () {
        Route::get('/showAll', 'getAllReviews');
        Route::get('/show/{id}', 'getReviewById');
        Route::get('/show/client/{id}', 'getReviewsByClientId');
        Route::post('/store', 'addReview')->middleware('auth:client');
        Route::post('/update/{reviewId}', 'update');
        Route::delete('/delete/{reviewId}', 'delete');
        Route::delete('/delete', 'deleteAll');
    });
});
Route::group([

    'prefix' => 'auth',

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::get('/unauthorized', function () {
    return response()->json([
        "message" => "Unauthorized",
    ], 401);
})->name('login');


    