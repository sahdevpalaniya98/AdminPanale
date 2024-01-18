<?php

use App\Http\Controllers\Admin\BranchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PhoneDamageController;
use App\Http\Controllers\Admin\PaymentModeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ImeiController;
use App\Http\Controllers\Admin\PhoneSeriesController;
use App\Http\Controllers\Admin\PhoneModelController;
use App\Http\Controllers\Admin\PayWorkerController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VariantController;


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

Route::group(['prefix' => 'commands'], function () {
    Route::get('/optimize-clear', function () {
        Artisan::call('optimize:clear');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/migrate', function () {
        Artisan::call('migrate');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/clear-compiled', function () {
        Artisan::call('clear-compiled');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/activitylog-clean', function () {
        Artisan::call('activitylog:clean');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/migrate-fresh', function () {
        Artisan::call('migrate:fresh');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/seed', function () {
        Artisan::call('db:seed');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/db-wipe', function () {
        Artisan::call('db:wipe');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/migrate-rollback', function () {
        Artisan::call('migrate:rollback');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });

    Route::get('/permission-cache-reset', function () {
        Artisan::call('permission:cache-reset');
        echo '<pre>' . str_replace('\n', "\n", Artisan::output()) . '</pre>';
    });
});


// Route::get('/test', function () {
//     dd(\Request::is('test'));
// });

Route::get('/', [LoginController::class, 'showLoginForm']);

Auth::routes(['verify' => true, 'logout' => false]);

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::group(['as' => 'admin.'], function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.submit');
        Route::post('/password/update', [ProfileController::class, 'passwordUpdate'])->name('password.submit');

        Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
            Route::post('/customer-list', [AjaxController::class, 'customer_list'])->name('customer.list');
            Route::post('/phone-damage-list', [AjaxController::class, 'phone_damage_list'])->name('phone_damages.list');
            Route::post('/get-sickw-data', [AjaxController::class, 'get_sickw'])->name('get_sickw');
            Route::post('/payment-mode-list', [AjaxController::class, 'payment_mode_list'])->name('payment_mode.list');
            Route::post('/employee-list', [AjaxController::class, 'employee_list'])->name('employee.list');
            Route::post('/buyer-list', [AjaxController::class, 'buyer_list'])->name('buyer.list');
            Route::post('/pay-worker-list', [AjaxController::class, 'pay_worker_list'])->name('pay_worker.list');
            Route::post('/inventory-list', [AjaxController::class, 'inventory_list'])->name('inventory.list');
            Route::post('/damage-list', [AjaxController::class, 'damage_list'])->name('damage.list');
            Route::post('/all-damages-list', [AjaxController::class, 'all_damages_list'])->name('all.damages.list');
            Route::post('/phone-grade-list', [AjaxController::class, 'phone_grade_list'])->name('phone.grade.list');
            Route::post('/phone-brand-list', [AjaxController::class, 'brand_list'])->name('phone.brand.list');
            Route::post('/phone-series-list', [AjaxController::class, 'series_list'])->name('phone.series.list');
            Route::post('/phone-model-list', [AjaxController::class, 'model_list'])->name('phone.model.list');
            Route::post('/expense-list', [AjaxController::class, 'expense_list'])->name('expense.list');
            Route::post('/category-list', [AjaxController::class, 'category_list'])->name('category.list');
            Route::post('/variant-list', [AjaxController::class, 'variant_list'])->name('variant.list');
            Route::post('/model-variant-list', [AjaxController::class, 'model_variant_list'])->name('model.variant.list');
            Route::post('/branch', [AjaxController::class, 'branch'])->name('branch.list');
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/datatable', [UserController::class, 'datatable'])->name('data');
            Route::get('/add', [UserController::class, 'create'])->name('add');
            Route::post('/exists', [UserController::class, 'exists'])->name('exists');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/view/{id}', [UserController::class, 'show'])->name('view');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('/destroy', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/status/change', [UserController::class, 'statusChange'])->name('status.change');
            Route::get('/history/{id}', [UserController::class, 'history'])->name('history');
            Route::post('/history-datatable/{id}', [UserController::class, 'history_datatable'])->name('history.data');
        });

        Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/datatable', [RoleController::class, 'datatable'])->name('data');
            Route::get('/add', [RoleController::class, 'create'])->name('add');
            Route::post('/exists', [RoleController::class, 'exists'])->name('exists');
            Route::post('/store', [RoleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::post('/destroy', [RoleController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'permission', 'as' => 'permission.'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::post('/datatable', [PermissionController::class, 'datatable'])->name('data');
            Route::get('/add', [PermissionController::class, 'create'])->name('add');
            Route::post('/exists', [PermissionController::class, 'exists'])->name('exists');
            Route::post('/store', [PermissionController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
            Route::post('/destroy', [PermissionController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'activity', 'as' => 'activity.'], function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::post('/datatable', [ActivityController::class, 'datatable'])->name('data');
        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::post('/datatable', [CustomerController::class, 'datatable'])->name('data');
            Route::get('/add', [CustomerController::class, 'create'])->name('add');
            Route::post('/exists', [CustomerController::class, 'exists'])->name('exists');
            Route::post('/store', [CustomerController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::post('/destroy', [CustomerController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'buyer', 'as' => 'buyer.', 'controller' => BuyerController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            // Route::post('/exists', 'exists')->name('exists');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
            Route::get('/history/{id}', 'history')->name('history');
            Route::post('/history-datatable/{id}', 'history_datatable')->name('history.data');
        });

        Route::group(['prefix' => 'expense', 'as' => 'expense.', 'controller' => ExpenseController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            // Route::post('/exists', 'exists')->name('exists');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
            Route::get('/order/history/{id}', 'order_count')->name('order_count');
        });

        Route::group(['prefix' => 'phone/damage', 'as' => 'phone.damage.', 'controller' => PhoneDamageController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            // Route::post('/exists', 'exists')->name('exists');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'payment/mode', 'as' => 'payment.mode.', 'controller' => PaymentModeController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            // Route::post('/exists', 'exists')->name('exists');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.', 'controller' => BrandController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.', 'controller' => CategoryController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'phone/series', 'as' => 'phone.series.', 'controller' => PhoneSeriesController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'phone/model', 'as' => 'phone.model.', 'controller' => PhoneModelController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'imei-details', 'as' => 'imei_details.', 'controller' => ImeiController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            // Route::post('/exists', 'exists')->name('exists');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });

        Route::group(['prefix' => 'pay/worker', 'as' => 'pay.worker.', 'controller' => PayWorkerController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/view/{id}', 'view')->name('view');
            Route::post('/pay_worker_datatable', 'pay_worker_datatable')->name('data.view');
            Route::post('/destroy', 'destroy')->name('destroy');
            Route::get('/wallet/{id}', 'wallet')->name('wallet');
            Route::post('/wallet/store', 'wallet_store')->name('wallet_store');
            Route::post('/wallet/datatable', 'wallet_datatable')->name('wallet_table');
        });

        Route::group(['prefix' => 'inventory', 'as' => 'inventory.', 'controller' => InventoryController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/view/{id}', 'view')->name('view');
            Route::post('/destroy', 'destroy')->name('destroy');
            Route::get('/order-create/{id}', 'order_create')->name('order.create');
            Route::post('/order-store', 'order_store')->name('order.store');
        });

        Route::group(['prefix' => 'order', 'as' => 'order.', 'controller' => OrderController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/view/{id}', 'view')->name('view');
            Route::get('/invenroty/iew/{id}', 'invenroty_view')->name('invenroty_view');
            Route::post('/destroy', 'destroy')->name('destroy');
            Route::post('/mark_as_complete', 'mark_as_complete')->name('mark_as_complete');
            Route::post('/return_order', 'return_order')->name('return_order');
            Route::post('/cancel_order', 'cancel_order')->name('cancel_order');
            Route::get('/invoice-download/{id}', 'invoice_download')->name('invoice.download');
        });

        Route::group(['prefix' => 'phone/variant', 'as' => 'variant.'], function () {
            Route::get('/', [VariantController::class, 'index'])->name('index');
            Route::post('/datatable', [VariantController::class, 'datatable'])->name('data');
            Route::get('/add', [VariantController::class, 'create'])->name('add');
            Route::post('/exists', [VariantController::class, 'exists'])->name('exists');
            Route::post('/store', [VariantController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [VariantController::class, 'edit'])->name('edit');
            Route::post('/destroy', [VariantController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'branch', 'as' => 'branch.', 'controller' => BranchController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('data');
            Route::get('/add', 'create')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/destroy', 'destroy')->name('destroy');
        });
    });
});
