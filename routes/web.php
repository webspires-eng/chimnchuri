<?php

use App\Http\Controllers\Admin\AddonItemController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Addon\CategoryController as AddonCategoryController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\Category\CategoryMediaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ProductMediaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SmtpController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, "index"])->name('admin.dashboard')->middleware('admin');
// ADMIN 
Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.post');

    Route::middleware('admin')->group(function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');



        Route::resource("/products", ProductController::class);
        Route::get('/products/{product}/assign-addons', [ProductController::class, 'assignAddons'])
            ->name('products.assign-addons');
        Route::post('/products/{product}/assign-addons', [ProductController::class, 'storeAssignedAddons'])
            ->name('products.assign-addons.store');
        Route::post('/products/{product}/media', [ProductMediaController::class, 'store'])
            ->name('products.media.store');
        Route::delete('/products/media/{media}', [ProductMediaController::class, 'destroy'])
            ->name('products.media.destroy');

        Route::resource("/category", CategoryController::class);
        Route::post('/category/{category}/media', [CategoryMediaController::class, 'store'])
            ->name('category.media.store');
        Route::delete('/category/media/{media}', [CategoryMediaController::class, 'destroy'])
            ->name('category.media.destroy');

        Route::resource("/addon-categories", AddonCategoryController::class)->names("admin.addon-categories");

        Route::resource("/addon-items", AddonItemController::class)->names("admin.addon-items");

        Route::resource("/users", UserController::class)->names("admin.users");

        Route::resource("/smtp", SmtpController::class)->names("admin.smtp");

        Route::resource('/payment-gateways', PaymentGatewayController::class)->names("payment-gateways");

        // ORDER
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');


        // VOUCHERS
        Route::resource('/vouchers', VoucherController::class)->names("vouchers");

        // SETTINGS
        Route::get("/general-settings", [SettingController::class, "index"])->name("admin.general-settings");
        Route::post("/general-settings", [SettingController::class, "update"])->name("admin.general-settings.update");

        // WORKING HOURS
        Route::get("/working-hours", [SettingController::class, "workingHours"])->name("admin.working-hours");
        Route::post("/working-hours", [SettingController::class, "updateWorkingHours"])->name("admin.working-hours.update");

        // OFFERS
        Route::resource("offers", OfferController::class);

        Route::get("/data", [DashboardController::class, "getPerformanceData"])->name("admin.data");
        Route::get("/data-12m", [DashboardController::class, "last12MonthSales"])->name("admin.data.12m");

        // BRANCHES
        Route::resource("branches", BranchController::class)->names("admin.branches");

        // TIME SLOTS
        Route::get('/time-slots', [TimeSlotController::class, 'index'])->name('admin.time-slots.index');
        Route::get('/time-slots/create', [TimeSlotController::class, 'create'])->name('admin.time-slots.create');
        Route::post('/time-slots/store', [TimeSlotController::class, 'store'])->name('admin.time-slots.store');
        Route::get("/time-slots/{timeSlot}/edit", [TimeSlotController::class, "edit"])->name("admin.time-slots.edit");
        Route::post("/time-slots/{timeSlot}/update", [TimeSlotController::class, "update"])->name("admin.time-slots.update");
        Route::delete("/time-slots/{timeSlot}/delete", [TimeSlotController::class, "destroy"])->name("admin.time-slots.destroy");
    });
});

Route::post("/webhook/stripe", [PaymentController::class, "handleWebhook"])->name("stripe.webhook")->withoutMiddleware([VerifyCsrfToken::class]);
