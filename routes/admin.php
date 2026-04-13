<?php

use App\Http\Controllers\Admin\AccountStatementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BillerProfileController;
use App\Http\Controllers\Admin\BusinessUserController;
use App\Http\Controllers\Admin\ChatCategoryController;
use App\Http\Controllers\Admin\CompanyMessageController;
use App\Http\Controllers\Admin\CompanyTypeController;
use App\Http\Controllers\Admin\CreditNoteController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\FinancialYearController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\KnowledgeBaseController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NoteTypeController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PlannerDocumentController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\WorkOrderController;
use App\Http\Controllers\Admin\WorkPlanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/get-subcategories/{category}', [SearchController::class, 'getSubCategories']);
Route::get('/get-locations/{state}', [SearchController::class, 'getLocations']);
Route::get('/companies', [SearchController::class, 'companies']);
Route::get('/planners', [SearchController::class, 'planners']);
Route::get('/total-groups', [SearchController::class, 'totalGroups']);
Route::get(
    '/quotations/{quotation}/details',
    [SearchController::class, 'details']
)->name('quotations.details');


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('quotations/{quotation}/approvals', [QuotationController::class, 'approvals'])->name('quotations.approvals');
    Route::post('quotations/approvals/update', [QuotationController::class, 'updateApprovals'])->name('quotations.approvals.update');
    Route::get(
        'quotations/{quotation}/pdf',
        [QuotationController::class, 'downloadPdf']
    )->name('quotations.pdf');
    Route::post('quotations/export', [QuotationController::class, 'export'])->name('quotations.export');
    Route::post('quotations/accept', [QuotationController::class, 'accept'])
        ->name('quotations.accept');
    Route::get('/quotations/status/view', [QuotationController::class, 'statusView'])->name('quotations.status.view');
    Route::post('/quotations/status', [QuotationController::class, 'status'])->name('quotations.status');
    Route::resource('quotations', QuotationController::class);

    Route::get('invoices/{invoice}/approvals', [InvoiceController::class, 'approvals'])->name('invoices.approvals');
    Route::post('invoices/approvals/update', [InvoiceController::class, 'updateApprovals'])->name('invoices.approvals.update');
    Route::get(
        'invoices/{invoice}/pdf',
        [InvoiceController::class, 'downloadPdf']
    )->name('invoices.pdf');
    Route::post('invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::get('/invoices/status/view', [InvoiceController::class, 'statusView'])->name('invoices.status.view');
    Route::post('/invoices/status', [InvoiceController::class, 'status'])->name('invoices.status');
    Route::resource('invoices', InvoiceController::class);

    Route::get(
        'receipts/{receipt}/pdf',
        [ReceiptController::class, 'downloadPdf']
    )->name('receipts.pdf');
    Route::resource('receipts', ReceiptController::class);


    Route::post('/customers/status', [CustomerController::class, 'status'])->name('customers.status');
    Route::resource('customers', CustomerController::class);

    Route::post('documents/export', [DocumentController::class, 'export'])->name('documents.export');
    Route::get('documents/company', [DocumentController::class, 'company'])->name('documents.company');
    Route::post('documents/export/company', [DocumentController::class, 'exportCompany'])->name('documents.export.company');
    Route::resource('documents', DocumentController::class);

    Route::post('/project-categories/status', [ProjectCategoryController::class, 'status'])->name('project-categories.status');
    Route::resource('project-categories', ProjectCategoryController::class);

    Route::get('/projects/status/view', [ProjectController::class, 'statusView'])->name('projects.status.view');
    Route::post('/projects/status', [ProjectController::class, 'status'])->name('projects.status');
    Route::resource('projects', ProjectController::class);

    Route::post('/chat-categories/status', [ChatCategoryController::class, 'status'])->name('chat-categories.status');
    Route::resource('chat-categories', ChatCategoryController::class);

    Route::get('/knowledge-bases/status/view', [KnowledgeBaseController::class, 'statusView'])->name('knowledge-bases.status.view');
    Route::post('/knowledge-bases/status', [KnowledgeBaseController::class, 'status'])->name('knowledge-bases.status');
    Route::resource('knowledge-bases', KnowledgeBaseController::class);

    Route::post('payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments', [PaymentController::class, 'index'])
        ->name('payments.index');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity.index');
    Route::post('activity-log/bulk-delete', [ActivityLogController::class, 'bulkDelete'])
        ->name('activity.bulk-delete');

    Route::post('/business-users/status', [BusinessUserController::class, 'status'])->name('business-users.status');
    Route::post('business-users/export', [BusinessUserController::class, 'export'])->name('business-users.export');
    Route::post('/business-users/send-credentials', [BusinessUserController::class, 'sendCredentials'])->name('business-users.send-credentials');
    Route::resource('business-users', BusinessUserController::class);

    Route::post('planner-documents/export', [PlannerDocumentController::class, 'export'])->name('planner-documents.export');
    Route::get('/planner-documents/status/view', [PlannerDocumentController::class, 'statusView'])->name('planner-documents.status.view');
    Route::post('/planner-documents/status', [PlannerDocumentController::class, 'status'])->name('planner-documents.status');
    Route::resource('planner-documents', PlannerDocumentController::class);

    Route::get('/messages/conversation/{id}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::get('/messages/recipients', [MessageController::class, 'recipients'])->name('messages.recipients');
    Route::get('/messages/{message}/conversations', [MessageController::class, 'fetchConversations'])
        ->name('messages.conversations');
    Route::post('/messages/{message}/conversations', [MessageController::class, 'sendConversation'])
        ->name('messages.conversations.send');
    Route::post('/messages/conversations/read/{id}', [MessageController::class, 'markAsRead'])
        ->name('messages.conversations.read');
    Route::resource('messages', MessageController::class);

    Route::resource('company-messages', CompanyMessageController::class);

    Route::resource('announcements', AnnouncementController::class);

    Route::post('/financial-years/status', [FinancialYearController::class, 'status'])->name('financial-years.status');
    Route::resource('financial-years', FinancialYearController::class);

    Route::post('/currencies/status', [CurrencyController::class, 'status'])->name('currencies.status');
    Route::resource('currencies', CurrencyController::class);

    Route::post('/company-types/status', [CompanyTypeController::class, 'status'])->name('company-types.status');
    Route::resource('company-types', CompanyTypeController::class);

    Route::post('/note-types/status', [NoteTypeController::class, 'status'])->name('note-types.status');
    Route::resource('note-types', NoteTypeController::class);

    Route::post('/document-types/status', [DocumentTypeController::class, 'status'])->name('document-types.status');
    Route::resource('document-types', DocumentTypeController::class);


    Route::resource('biller-profiles', BillerProfileController::class);

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    Route::get('/work-orders/status/view', [WorkPlanController::class, 'statusView'])->name('work-orders.status.view');
    Route::post('/work-orders/status', [WorkPlanController::class, 'status'])->name('work-orders.status');
    Route::get('/work-orders/details/{work_order}', [WorkPlanController::class, 'details'])->name('work-orders.details');
    Route::post('/work-orders/{id}/close', [WorkPlanController::class, 'close'])
        ->name('work-orders.close');
    Route::post('/work-orders/{id}/reject', [WorkPlanController::class, 'reject'])
        ->name('work-orders.reject');
    Route::get('work-orders/generate-code', [WorkPlanController::class, 'generateCode'])
        ->name('work-orders.generate-code');
    Route::post('/work-orders/{workOrder}/attachments', [WorkPlanController::class, 'storeAttachment'])
        ->name('work-orders.attachments.store');
    Route::post('/work-orders/{workOrder}/notes', [WorkPlanController::class, 'storeNote'])
        ->name('work-orders.notes.store');
    Route::post('/work-orders/{workOrder}/api/attachments', [WorkPlanController::class, 'storeApiAttachment'])
        ->name('work-orders.attachments.store.api');
    Route::post('/work-orders/{workOrder}/api/notes', [WorkPlanController::class, 'storeApiNote'])
        ->name('work-orders.notes.store.api');
    Route::post('notes/{note}/update-status', [WorkPlanController::class, 'updateStatus'])
        ->name('notes.update-status');
    Route::get(
        'work-orders/{workOrder}/rejection-reason',
        [WorkPlanController::class, 'rejectionReason']
    )->name('work-orders.rejection-reason');
    Route::get('/work-orders/planner/payout-view', [WorkPlanController::class, 'plannerPayoutView'])->name('work-orders.planner.payout-view');
    Route::post('/work-orders/planner/payout/store', [WorkPlanController::class, 'storePlannerPayout'])->name('work-orders.planner.payout.store');

    Route::get('/work-orders/production/payout-view', [WorkPlanController::class, 'productionPayoutView'])->name('work-orders.production.payout-view');
    Route::post('/work-orders/production/payout/store', [WorkPlanController::class, 'storeProductionPayout'])->name('work-orders.production.payout.store');
    Route::get('/work-orders/completed', [WorkPlanController::class, 'completedList'])->name('work-orders.completed.list');


    Route::resource('work-orders', WorkPlanController::class);

    // Route::resource('work-orders', WorkOrderController::class);

    Route::resource('credit-notes', CreditNoteController::class);

    Route::get('/account-statements/details/{id}', [AccountStatementController::class, 'details'])->name('account-statements.details');

    Route::get('/account-statements/work-orders', [AccountStatementController::class, 'workOrders'])->name('account-statements.workOrders');
    Route::post('account-statements/work-orders/export-pdf', [AccountStatementController::class, 'exportPdf'])
        ->name('invoices.export.pdf');

    Route::get('/account-statements/invoice', [AccountStatementController::class, 'invoice'])->name('account-statements.invoice');
    Route::post('account-statements/invoice-export-pdf', [AccountStatementController::class, 'invoiceExportPdf'])
        ->name('invoice.export.pdf');

    Route::get('/account-statements/original-receipts', [AccountStatementController::class, 'originalReceipt'])->name('account-statements.original-receipts');
    Route::post('account-statements/or-export-pdf', [AccountStatementController::class, 'orExportPdf'])
        ->name('or.export.pdf');

    Route::get('/account-statements/credit-notes', [AccountStatementController::class, 'creditNote'])->name('account-statements.credit-notes');
    Route::post('account-statements/cr-export-pdf', [AccountStatementController::class, 'crExportPdf'])
        ->name('cr.export.pdf');

    Route::get('/account-statements/planner-commission', [AccountStatementController::class, 'plannerCommission'])->name('account-statements.planner-commission');
    Route::get(
        '/account-statements/planner-commission/export',
        [AccountStatementController::class, 'plannerCommissionExport']
    )->name('account-statements.planner-commission.export');

    Route::get('/account-statements/production-commission', [AccountStatementController::class, 'productionCommission'])->name('account-statements.production-commission');
    Route::get(
        '/account-statements/production-commission/export',
        [AccountStatementController::class, 'productionCommissionExport']
    )->name('account-statements.production-commission.export');

    Route::get('/account-statements/total-group', [AccountStatementController::class, 'totalGroup'])->name('account-statements.total-group');
    Route::get(
        'account-statements/total-group/export',
        [AccountStatementController::class, 'exportTotalGroupPdf']
    )->name('account-statements.total-group.export');

    Route::get('/account-statements/consolidated', [AccountStatementController::class, 'consolidated'])->name('account-statements.consolidated');
    Route::post('account-statements/consolidated-export-pdf', [AccountStatementController::class, 'consolidatedExportPdf'])
        ->name('consolidated.export.pdf');

    Route::get('/account-statements/monthly-summary', [AccountStatementController::class, 'monthlySummary'])->name('account-statements.monthly-summary');
    Route::get('monthly-summary/pdf', [AccountStatementController::class, 'monthlySummaryPdf'])
        ->name('account-statements.monthly-summary.pdf');

    Route::get('/account-statements/outstanding-report', [AccountStatementController::class, 'outstandingReport'])->name('account-statements.outstanding-report');
    Route::get('/account-statements/planner-monthly-report', [AccountStatementController::class, 'plannerMonthlyReport'])->name('account-statements.planner-monthly-report');
    Route::get('planner-monthly-invoices', [AccountStatementController::class, 'getPlannerMonthlyInvoices'])
        ->name('planner.monthly-invoices');
    Route::get(
        'account-statements/outstanding-export',
        [AccountStatementController::class, 'outstandingExport']
    )->name('account-statements.outstanding-export');
    Route::get(
        'account-statements/outstanding-pdf',
        [AccountStatementController::class, 'outstandingPdf']
    )->name('account-statements.outstanding-pdf');
    Route::get(
        'planner-monthly-export',
        [AccountStatementController::class, 'plannerMonthlyExport']
    )->name('planner.monthly-export');
    Route::get(
        'planner-monthly-pdf',
        [AccountStatementController::class, 'plannerMonthlyPdf']
    )->name('planner.monthly-pdf');

    Route::get('account-statements/monthly-invoices-details', [AccountStatementController::class, 'monthlyInvoicesDetails'])
        ->name('account-statements.monthly-invoices-details');

    //Production Staff Report
    Route::get('/account-statements/ps-monthly-report', [AccountStatementController::class, 'psMonthlyReport'])->name('account-statements.ps-monthly-report');
    Route::get('ps-monthly-invoices', [AccountStatementController::class, 'getpsMonthlyInvoices'])
        ->name('ps.monthly-invoices');
    Route::get(
        'ps-monthly-export',
        [AccountStatementController::class, 'psMonthlyExport']
    )->name('ps.monthly-export');
    Route::get(
        'ps-monthly-pdf',
        [AccountStatementController::class, 'psMonthlyPdf']
    )->name('ps.monthly-pdf');

    Route::get('account-statements/ps/monthly-invoices-details', [AccountStatementController::class, 'monthlyPSInvoicesDetails'])
        ->name('account-statements.ps.monthly-invoices-details');
    //Production Staff Report Ends

    Route::resource('account-statements', AccountStatementController::class);

    // web.php
    Route::post('users/{user}/lock', [UserManagementController::class, 'lock'])->name('admin.user.lock');
    Route::post('users/{user}/unlock', [UserManagementController::class, 'unlock'])->name('admin.user.unlock');
});
































Route::get('system/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return redirect()->back()->with('success', 'All cache cleared successfully!');
})->name('system.cache-clear');

// Create storage link
Route::get('system/storage-link', function () {
    Artisan::call('storage:link');
    return redirect()->back()->with('success', 'Storage link created successfully!');
})->name('system.storage-link');

// Migrate fresh and seed
Route::get('system/migrate-fresh', function () {
    Artisan::call('migrate:fresh', ['--seed' => true]);
    return redirect()->back()->with('success', 'Database migrated fresh and seeded successfully!');
})->name('system.migrate-fresh');

Route::get('system/migrate/{file}', function ($file) {

    $path = database_path('migrations/' . $file);

    // Check if file exists
    if (!File::exists($path)) {
        return back()->with('error', 'Migration file not found');
    }

    Artisan::call('migrate', [
        '--path' => 'database/migrations/' . $file
    ]);

    return back()->with('success', 'Migration executed successfully');
});
