<?php

use App\Http\Controllers\JSON\Store\RoleController as RoleJsonController;
use App\Http\Controllers\Store\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    \App\Http\Middleware\MustLoginMiddleware::class,
    \App\Http\Middleware\CheckJWTSession::class,
])->group(function () {
    Route::prefix('store/roles')->name('store.roles.')->middleware((env("CSP_ENABLED") ? [Spatie\Csp\AddCspHeaders::class . ':' . \App\Services\ContentSecurityPolicies\UnsafeEvalPolicy::class] : []))->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware(
            'api-gateway.any:STORE.ROLES_VIEW,STORE.USERS_CREATE,STORE.USERS_EDIT',
        );

        Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware(
            'api-gateway.can:STORE.ROLES_CREATE',
        );

        Route::get('/{id}', [RoleController::class, 'show'])->name('show')->middleware(
            'api-gateway.can:STORE.ROLES_VIEW',
        );

        Route::post('/', [RoleController::class, 'store'])->name('store')->middleware(
            'api-gateway.can:STORE.ROLES_CREATE',
        );

        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit')->middleware(
            'api-gateway.can:STORE.ROLES_EDIT',
        );

        Route::put('/{id}', [RoleController::class, 'update'])->name('update')->middleware(
            'api-gateway.can:STORE.ROLES_EDIT',
        );
    });

    Route::prefix('json/store')->name('json.store.')->group(function() {
        Route::get('/roles', [RoleJsonController::class, 'index'])->name('roles.index')->middleware(
            'api-gateway.any:STORE.ROLES_VIEW,STORE.USERS_CREATE,STORE.USERS_EDIT',
        );
    });
});
