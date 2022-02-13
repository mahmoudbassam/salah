<?php

use App\Http\Controllers\CP\AidsController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CP\LoginController;
use \App\Http\Controllers\CP\BeneficiaryController;
use \App\Http\Controllers\CP\RegionsController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    \App\Models\User::query()->create([
        'name' => 'محمود بسام',
        'email' => 'admin@admin.com',
        'password' => bcrypt(123456),
    ]);
});
Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'from_login'])->name('login');
    Route::post('login', [LoginController::class, 'login_action'])->name('admin.login');

    //////////////////////////admin dashboard//////////////////////////////////
    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [LoginController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

        /////Beneficiary///
        Route::prefix('beneficiaries')->name('beneficiaries')->group(function () {
            Route::get('', [BeneficiaryController::class, 'index']);
            Route::get('show_form/{id?}', [BeneficiaryController::class, 'show_form'])->name('.show_form');
            Route::post('add_edit', [BeneficiaryController::class, 'add_edit'])->name('.add_edit');
            Route::get('list', [BeneficiaryController::class, 'list'])->name('.list');

        });
        /////Regions///
        Route::prefix('regions')->name('regions')->group(function () {
            Route::get('', [RegionsController::class, 'index']);
            Route::get('list', [RegionsController::class, 'list'])->name('.list');
            Route::get('show_form/{id?}', [RegionsController::class, 'show_form'])->name('.show_form');
            Route::post('add_edit', [RegionsController::class, 'add_edit'])->name('.add_edit');
        });
        ////aids
        Route::prefix('aids')->name('aids')->group(function () {
            Route::get('', [AidsController::class, 'index']);
            Route::get('list', [AidsController::class, 'list'])->name('.list');
            Route::get('show_form/{id?}', [AidsController::class, 'show_form'])->name('.show_form');
            Route::post('add_edit', [AidsController::class, 'add_edit'])->name('.add_edit');
            Route::post('enabled', [AidsController::class, 'enabled'])->name('.enabled');
        });

    });

});


