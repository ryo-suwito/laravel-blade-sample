<?php

use App\Http\Controllers\JSON\Core\BeneficiaryController as BeneficiaryJsonController;
use App\Http\Controllers\JSON\Core\MerchantBranchController as MerchantBranchJsonController;
use App\Http\Controllers\JSON\Core\PartnerController as PartnerJsonController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    \App\Http\Middleware\MustLoginMiddleware::class,
    \App\Http\Middleware\CheckJWTSession::class,
])->group(function () {
    Route::prefix('json/core')->name('json.core.')->group(function() {
        Route::get('/beneficiaries', [BeneficiaryJsonController::class, 'index'])->name('beneficiaries.index')->middleware(
            'api-gateway.any:STORE.USERS_CREATE,STORE.USERS_EDIT',
        );

        Route::get('/merchant-branches', [MerchantBranchJsonController::class, 'index'])->name('merchant_branches.index')->middleware(
            'api-gateway.any:STORE.USERS_CREATE,STORE.USERS_EDIT',
        );

        Route::get('/partners', [PartnerJsonController::class, 'index'])->name('partners.index')->middleware(
            'api-gateway.any:STORE.USERS_CREATE,STORE.USERS_EDIT',
        );
    });
});
