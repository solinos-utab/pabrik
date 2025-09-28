<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AttendanceController,
    PieceworkController,
    InventoryController,
    ShiftController,
    RoleController,
    UserController
};

// Authentication routes
Auth::routes();

// Dashboard
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// HR/Attendance Routes
Route::prefix('hr')->middleware(['auth'])->group(function () {
    // Attendance
    Route::middleware(['role:attendance.view'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('hr.attendance.index');
        Route::get('/attendance/scan', [AttendanceController::class, 'scanForm'])->name('hr.attendance.scan');
        Route::post('/attendance/scan', [AttendanceController::class, 'processScan'])->name('hr.attendance.process-scan');
        Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('hr.attendance.report');
    });

    // Shifts
    Route::middleware(['role:shift.manage'])->group(function () {
        Route::resource('shifts', ShiftController::class);
    });
});

// Production/Piecework Routes
Route::prefix('production')->middleware(['auth'])->group(function () {
    Route::middleware(['role:production.view'])->group(function () {
        Route::get('/piecework', [PieceworkController::class, 'index'])->name('production.piecework.index');
        Route::get('/piecework/create', [PieceworkController::class, 'create'])->name('production.piecework.create');
        Route::post('/piecework', [PieceworkController::class, 'store'])->name('production.piecework.store');
        Route::get('/piecework/report', [PieceworkController::class, 'report'])->name('production.piecework.report');
    });

    // Piecework Rates Management
    Route::middleware(['role:production.manage'])->group(function () {
        Route::resource('piecework-rates', PieceworkRateController::class);
    });
});

// Inventory Routes
Route::prefix('inventory')->middleware(['auth'])->group(function () {
    Route::middleware(['role:inventory.view'])->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/stock-card/{item}', [InventoryController::class, 'stockCard'])->name('inventory.stock-card');
        
        // Transactions
        Route::get('/in', [InventoryController::class, 'createIn'])->name('inventory.in.create');
        Route::post('/in', [InventoryController::class, 'storeIn'])->name('inventory.in.store');
        Route::get('/out', [InventoryController::class, 'createOut'])->name('inventory.out.create');
        Route::post('/out', [InventoryController::class, 'storeOut'])->name('inventory.out.store');
        Route::get('/transfer', [InventoryController::class, 'createTransfer'])->name('inventory.transfer.create');
        Route::post('/transfer', [InventoryController::class, 'storeTransfer'])->name('inventory.transfer.store');
    });

    // Master Data Management
    Route::middleware(['role:inventory.manage'])->group(function () {
        Route::resource('items', ItemController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('uoms', UOMController::class);
    });
});

// User Management Routes
Route::middleware(['auth', 'role:user.manage'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});