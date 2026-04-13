<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\DocumentManagerController;
use App\Http\Controllers\Admin\ItemManagementController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlannerController;
use App\Http\Controllers\Admin\PrefixController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->to(route('admin.login'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_submit'])->name('login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/counts', [DashboardController::class, 'getCounts'])->name('dashboard.counts');


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [AuthController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [AuthController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

        Route::get('/category', [CategoryController::class, 'index'])->name('manage.category');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        Route::post('/category/toggle-status', [CategoryController::class, 'toggleStatus'])->name('category.toggle-status');

        Route::get('/sub-category', [SubCategoryController::class, 'index'])->name('manage.sub.category');
        Route::get('/sub-category/create', [SubCategoryController::class, 'create'])->name('sub.category.create');
        Route::post('/sub-category/store', [SubCategoryController::class, 'store'])->name('sub.category.store');
        Route::get('/sub-category/edit/{id}', [SubCategoryController::class, 'edit'])->name('sub.category.edit');
        Route::post('/sub-category/update/{id}', [SubCategoryController::class, 'update'])->name('sub.category.update');
        Route::delete('/sub-category/destroy/{id}', [SubCategoryController::class, 'destroy'])->name('sub.category.destroy');
        Route::post('/sub-category/toggle-status', [SubCategoryController::class, 'toggleStatus'])->name('sub.category.toggle-status');

        Route::get('/states', [StateController::class, 'index'])->name('manage.states');
        Route::post('/states/store', [StateController::class, 'store'])->name('state.store');
        Route::get('/states/edit/{id}', [StateController::class, 'edit'])->name('state.edit');
        Route::post('/states/update/{id}', [StateController::class, 'update'])->name('state.update');
        Route::delete('/states/delete/{id}', [StateController::class, 'destroy'])->name('state.delete');

        Route::get('/locations', [StateController::class, 'location'])->name('manage.locations');
        Route::post('/locations/store', [StateController::class, 'locations_store'])->name('locations.store');
        Route::get('/locations/edit/{id}', [StateController::class, 'locations_edit'])->name('locations.edit');
        Route::post('/locations/update/{id}', [StateController::class, 'locations_update'])->name('locations.update');
        Route::delete('/locations/delete/{id}', [StateController::class, 'locations_destroy'])->name('locations.delete');


        Route::get('/user_management', [UserManagementController::class, 'index'])->name('manage.user');
        Route::get('/user_management/create', [UserManagementController::class, 'create'])->name('user.create');
        Route::post('/user_management/store', [UserManagementController::class, 'store'])->name('user.store');
        Route::get('/user_management/edit/{id}', [UserManagementController::class, 'edit'])->name('user.edit');
        Route::post('/user_management/update/{id}', [UserManagementController::class, 'update'])->name('user.update');
        Route::delete('/user_management/delete/{id}', [UserManagementController::class, 'destroy'])->name('user.destroy');

        Route::get('/business-owner', [UserManagementController::class, 'owner'])->name('manage.business.owner');
        Route::get('/business-owner/create', [UserManagementController::class, 'owner_create'])->name('business.owner.create');
        Route::post('/business-owner/store', [UserManagementController::class, 'owner_store'])->name('business.owner.store');
        Route::get('/business-owner/edit/{id}', [UserManagementController::class, 'owner_edit'])->name('business.owner.edit');
        Route::post('/business-owner/update/{id}', [UserManagementController::class, 'owner_update'])->name('business.owner.update');
        Route::delete('/business-owner/delete/{id}', [UserManagementController::class, 'owner_destroy'])->name('business.owner.destroy');

        Route::get('/dealer', [DealerController::class, 'index'])->name('manage.dealer');
        Route::get('/dealer/create', [DealerController::class, 'create'])->name('dealer.create');
        Route::post('/dealer/store', [DealerController::class, 'store'])->name('dealer.store');
        Route::get('/dealer/edit/{id}', [DealerController::class, 'edit'])->name('dealer.edit');
        Route::post('/dealer/update/{id}', [DealerController::class, 'update'])->name('dealer.update');
        Route::delete('/dealer/delete/{id}', [DealerController::class, 'destroy'])->name('dealer.destroy');

        Route::get('/planner', [PlannerController::class, 'index'])->name('manage.planner');
        Route::get('/planner/create', [PlannerController::class, 'create'])->name('planner.create');
        Route::post('/planner/store', [PlannerController::class, 'store'])->name('planner.store');
        Route::get('/planner/edit/{id}', [PlannerController::class, 'edit'])->name('planner.edit');
        Route::post('/planner/update/{id}', [PlannerController::class, 'update'])->name('planner.update');
        Route::delete('/planner/delete/{id}', [PlannerController::class, 'destroy'])->name('planner.destroy');

        Route::get('/document_manager', [DocumentManagerController::class, 'index'])->name('manage.document_manager');
        Route::get('/document_manager/create', [DocumentManagerController::class, 'create'])->name('document_manager.create');
        Route::post('/document_manager/store', [DocumentManagerController::class, 'store'])->name('document_manager.store');
        Route::get('/document_manager/edit/{id}', [DocumentManagerController::class, 'edit'])->name('document_manager.edit');
        Route::post('/document_manager/update/{id}', [DocumentManagerController::class, 'update'])->name('document_manager.update');
        Route::delete('/document_manager/delete/{id}', [DocumentManagerController::class, 'destroy'])->name('document_manager.destroy');


        Route::get('roles', [RoleController::class, 'index'])->name('manage.roles');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::post('/roles/{id}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign.permissions');


        // Permissions
        Route::get('permissions', [PermissionController::class, 'index'])->name('manage.permissions');
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::get('/company_management', [CompanyManagementController::class, 'index'])->name('manage.company');
        Route::get('/company_management/create', [CompanyManagementController::class, 'create'])->name('company.create');
        Route::post('/company_management/store', [CompanyManagementController::class, 'store'])->name('company.store');
        Route::get('/company_management/edit/{id}', [CompanyManagementController::class, 'edit'])->name('company.edit');
        Route::put('/company_management/update/{id}', [CompanyManagementController::class, 'update'])->name('company.update');
        Route::delete('/company_management/delete/{id}', [CompanyManagementController::class, 'destroy'])->name('company.destroy');
        Route::get('/company_management/view/{id}', [CompanyManagementController::class, 'view'])->name('company.view');
        Route::post('/company_management/export', [CompanyManagementController::class, 'export'])->name('company.export');
        Route::get('/company_management/status/view', [CompanyManagementController::class, 'statusView'])->name('company.status.view');
        Route::post('/company_management/status', [CompanyManagementController::class, 'status'])->name('company.status');
        Route::get('/company_management/notes', [CompanyManagementController::class, 'notes'])->name('company.notes');


        Route::get('/item_management', [ItemManagementController::class, 'index'])->name('manage.item');
        Route::get('/item_management/create', [ItemManagementController::class, 'create'])->name('item.create');
        Route::post('/item_management/store', [ItemManagementController::class, 'store'])->name('item.store');
        Route::get('/item_management/edit/{id}', [ItemManagementController::class, 'edit'])->name('item.edit');
        Route::post('/item_management/update/{id}', [ItemManagementController::class, 'update'])->name('item.update');
        Route::delete('/item_management/delete/{id}', [ItemManagementController::class, 'destroy'])->name('item.destroy');
        Route::post('/item_management/export', [ItemManagementController::class, 'export'])->name('item.export');

        Route::get('/get-subcategories', [ItemManagementController::class, 'getSubCategories'])->name('get.subcategories');

        Route::resource('prefixes', PrefixController::class);

        Route::get('system-setting', [SystemSettingController::class, 'index'])->name('system-setting.index');
        Route::post('system-setting', [SystemSettingController::class, 'store'])->name('system-setting.store');
    });
});

Route::middleware(['auth', 'role:Dealer'])->group(function () {
    Route::get('/dealer/quotation', fn() => view('dealer.quotation'));
});

Route::middleware(['auth', 'role:Planner'])->group(function () {
    Route::get('/planner/tasks', fn() => view('planner.tasks'));
});

Route::middleware(['auth', 'role:Document Manager'])->group(function () {
    Route::get('/documents', fn() => view('documents.index'));
});



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
