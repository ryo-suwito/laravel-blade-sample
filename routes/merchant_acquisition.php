<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    \App\Http\Middleware\MustLoginMiddleware::class,
    \App\Http\Middleware\CheckJWTSession::class,
])->group(function () {
    Route::prefix('json/merchant-acquisition')->name('json.merchant-acquisition.')->group(function () {
        Route::get("companies", [\App\Http\Controllers\JSON\MerchantAcquisition\CompanyController::class, 'index'])
            ->name('options.companies')
            ->middleware('api-gateway.any:MASTER_DATA.COMPANY.VIEW,MASTER_DATA.COMPANY.UPDATE');
        Route::get("merchants", [\App\Http\Controllers\JSON\MerchantAcquisition\MerchantController::class, 'index'])
            ->name('options.merchants')
            ->middleware('api-gateway.any:MASTER_DATA.MERCHANT.VIEW,MASTER_DATA.MERCHANT.UPDATE');
        Route::get("merchant_branches", [\App\Http\Controllers\JSON\MerchantAcquisition\MerchantBranchController::class, 'index'])
            ->name('options.branches')
            ->middleware('api-gateway.any:MASTER_DATA.MERCHANT_BRANCH.VIEW,MASTER_DATA.MERCHANT_BRANCH.UPDATE');
        Route::get("customers", [\App\Http\Controllers\JSON\MerchantAcquisition\CustomerController::class, 'index'])
            ->name('options.customers')
            ->middleware('api-gateway.any:MASTER_DATA.BENEFICIARY.VIEW,MASTER_DATA.BENEFICIARY.UPDATE');
    });
});
