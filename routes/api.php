<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\MailerController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Organized by feature/module. Middleware groups are used where needed.
| Each resource is grouped using Route::prefix for clarity.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/load_menu', [MenusController::class, 'index']);
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
    });


    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
    Route::get('/notifications/stream', [NotificationController::class, 'stream']);


    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/settings', [UserController::class, 'getUserSettings']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::patch('/save/{id}', [UserController::class, 'save_info']);
        Route::patch('/deactivate/{id}', [UserController::class, 'deactivate']);
        Route::patch('/reactivate/{id}', [UserController::class, 'reactivate']);
    });



    Route::post('/send-mail', [MailerController::class, 'send']);


    Route::prefix('nav_menus')->group(function () {
        Route::get('/list', [MenusController::class, 'menulist']);
        Route::post('/', [MenusController::class, 'store']);
        Route::put('/{id}', [MenusController::class, 'update']);
        Route::delete('/{id}', [MenusController::class, 'destroy']);
        Route::post('/swap', [MenusController::class, 'swapMenuOrder']);
    });




    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::post('/test-api', function (Request $request) {
        Log::info('Test API triggered', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'API successfully triggered!',
        ]);
    });

    require __DIR__ . '/api_maintenance.php';
});
