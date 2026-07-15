<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\MailerController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ListOfValueController;
use App\Http\Controllers\CrmLeadController;
use App\Http\Controllers\CrmStatusController;
use App\Http\Controllers\CrmActivityController;
use App\Http\Controllers\CrmNoteController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LovController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ClientMasterController;
use App\Http\Controllers\ClientContractController;
use App\Http\Controllers\ClientProposalController;


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




    Route::get('/roles', fn() => DB::table('setting_role')->get());

    Route::post('/test-api', function (Request $request) {
        Log::info('Test API triggered', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'API successfully triggered!',
        ]);
    });

    require __DIR__ . '/api_maintenance.php';
});
