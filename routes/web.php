<?php

use App\Http\Controllers\JSON\API\GroupingAccessControlController;
use App\Http\Controllers\Store\ImportUserController;
use App\Http\Controllers\JSON\Store\ImportUserController as ImportUserJsonController;
use App\Http\Controllers\Partners\PaymentGatewayCredentialController;
use App\Http\Controllers\YukkCo\LegalApproval\CompanyController;
use App\Http\Controllers\YukkCo\MerchantOnline\ManualTransferController;
use App\Http\Controllers\YukkCo\MerchantOnline\RetryTransferItemController;
use App\Http\Controllers\YukkCo\PaymentGatewayTechDocController;
use App\Http\Controllers\YukkCo\MerchantOnline\SettlementController;
use App\Http\Controllers\YukkCo\MerchantOnline\TransactionController;
use App\Http\Controllers\YukkCo\MerchantOnline\TransferController;
use App\Http\Controllers\YukkCo\MerchantOnline\TransferItemsController;
use App\Http\Controllers\YukkCo\ActivityLog\RowChangeLogController;
use App\Http\Controllers\YukkCo\MerchantOnline\TransferItemTransactionController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['prevent.back_history', \App\Http\Middleware\AddResponseFrameOptionsHeader::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\IndexController::class, "index"])->name("cms.index");
    Route::get("auth/login", [\App\Http\Controllers\LoginController::class, "index"])->name("cms.login");
});

require_once(__DIR__ . '/otp.php');

//Route::group(["middleware" => "throttle:10,5"], function() {
Route::post("auth/login", [\App\Http\Controllers\LoginController::class, "login"])->name("cms.login.post");
//});

Route::middleware([\App\Http\Middleware\AddResponseFrameOptionsHeader::class])->group(function() {
    Route::get("auth/forgot_password", [\App\Http\Controllers\ForgotPasswordController::class, "index"])->middleware([\App\Http\Middleware\AddResponseFrameOptionsHeader::class])->name("cms.forgot_password.index");
    Route::get("auth/forgot_password_complete", [\App\Http\Controllers\ForgotPasswordController::class, "complete"])->middleware([\App\Http\Middleware\AddResponseFrameOptionsHeader::class])->name("cms.forgot_password.complete_request");
});

Route::group(["middleware" => "throttle:20,10"], function () {
    Route::post("auth/forgot_password", [\App\Http\Controllers\ForgotPasswordController::class, "process"])->name("cms.forgot_password.post");
    Route::get("auth/password_reset", [\App\Http\Controllers\ForgotPasswordController::class, "attempt"])->name("cms.forgot_password.attempt");
    Route::post("auth/password_reset", [\App\Http\Controllers\ForgotPasswordController::class, "reset"])->name("cms.forgot_password.reset");
});

Route::post("auth/env/{token}/{username}", [\App\Http\Controllers\LoginController::class, "env"])
    ->name("cms.env.post");

Route::get('partner/credentials/tech-docs/latest', [PaymentGatewayTechDocController::class, 'latestFile']);
Route::get('partner/tech-docs/token', [PaymentGatewayTechDocController::class, 'getFileToken']);
Route::get('partner/tech-docs/file/{token}', [PaymentGatewayTechDocController::class, 'downloadFile']);

Route::group(['middleware' => ['must.login', 'otp.verified', \App\Http\Middleware\AddResponseFrameOptionsHeader::class]], function () {
    Route::get('/layout_user', function () {
        return view('user.index');
    });
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('cms.dashboard')->middleware('prevent.back_history');
    Route::post("auth/logout", [\App\Http\Controllers\LoginController::class, "logout"])->name("cms.logout");
    Route::post("auth/change_user_role", [\App\Http\Controllers\MyProfileController::class, "changeUserRole"])->name("cms.change_user_role");
    //Route::get("my_profile/change_target", [\App\Http\Controllers\MyProfileController::class, "changeTarget"])->name("cms.change_target");

    Route::get("partner/transaction_payment/list", [\App\Http\Controllers\Partners\TransactionPaymentController::class, "index"])->name("cms.partner.transaction_payment.list");
    Route::get("partner/transaction_payment/item/{transaction_payment_id}", [\App\Http\Controllers\Partners\TransactionPaymentController::class, "show"])->name("cms.partner.transaction_payment.show");

    Route::get("merchant_branch/transaction_payment/list", [\App\Http\Controllers\MerchantBranches\TransactionPaymentController::class, "index"])->name("cms.merchant_branch.transaction_payment.list");
    Route::get("merchant_branch/transaction_payment/item/{transaction_payment_id}", [\App\Http\Controllers\MerchantBranches\TransactionPaymentController::class, "show"])->name("cms.merchant_branch.transaction_payment.show");

    Route::get("customer/transaction_payment/list", [\App\Http\Controllers\Customers\TransactionPaymentController::class, "index"])->name("cms.customer.transaction_payment.list");
    Route::get("customer/transaction_payment/item/{transaction_payment_id}", [\App\Http\Controllers\Customers\TransactionPaymentController::class, "show"])->name("cms.customer.transaction_payment.show");

    Route::get("customer/transaction_pg/list", [\App\Http\Controllers\Customers\TransactionPgController::class, "index"])->name("cms.customer.transaction_pg.list");
    Route::get("customer/transaction_pg/item/{transaction_pg_id}", [\App\Http\Controllers\Customers\TransactionPgController::class, "show"])->name("cms.customer.transaction_pg.show");

    Route::get("yukk_co/jalin_log/inbound/list", [\App\Http\Controllers\YukkCo\JalinRequestLogController::class, "indexInbound"])->name("cms.yukk_co.jalin_log.list_inbound");
    Route::get("yukk_co/jalin_log/inbound/item/{log_id}", [\App\Http\Controllers\YukkCo\JalinRequestLogController::class, "showInbound"])->name("cms.yukk_co.jalin_log.show_inbound");
    Route::get("yukk_co/jalin_log/outbound/list", [\App\Http\Controllers\YukkCo\JalinRequestLogController::class, "indexOutbound"])->name("cms.yukk_co.jalin_log.list_outbound");
    Route::get("yukk_co/jalin_log/outbound/item/{log_id}", [\App\Http\Controllers\YukkCo\JalinRequestLogController::class, "showOutbound"])->name("cms.yukk_co.jalin_log.show_outbound");

    Route::get("yukk_co/qris_re_hit/list", [\App\Http\Controllers\YukkCo\QrisReHitRequestController::class, "index"])->name("cms.yukk_co.qris_re_hit.list");
    Route::get("yukk_co/qris_re_hit/item/{qris_re_hit_id}", [\App\Http\Controllers\YukkCo\QrisReHitRequestController::class, "show"])->name("cms.yukk_co.qris_re_hit.item");
    Route::get("yukk_co/qris_re_hit/create", [\App\Http\Controllers\YukkCo\QrisReHitRequestController::class, "create"])->name("cms.yukk_co.qris_re_hit.create");
    Route::post("yukk_co/qris_re_hit/create", [\App\Http\Controllers\YukkCo\QrisReHitRequestController::class, "store"])->name("cms.yukk_co.qris_re_hit.store");
    Route::post("yukk_co/qris_re_hit/release/{qris_re_hit_id}", [\App\Http\Controllers\YukkCo\QrisReHitRequestController::class, "release"])->name("cms.yukk_co.qris_re_hit.release");


    Route::get("yukk_co/rintis_log/inbound/list", [\App\Http\Controllers\YukkCo\RintisRequestLogController::class, "indexInbound"])->name("cms.yukk_co.rintis_log.list_inbound");
    Route::get("yukk_co/rintis_log/inbound/item/{log_id}", [\App\Http\Controllers\YukkCo\RintisRequestLogController::class, "showInbound"])->name("cms.yukk_co.rintis_log.show_inbound");

    Route::get("yukk_co/qris_re_hit_rintis/list", [\App\Http\Controllers\YukkCo\QrisReHitRintisController::class, "index"])->name("cms.yukk_co.qris_re_hit_rintis.list");
    Route::get("yukk_co/qris_re_hit_rintis/item/{qris_re_hit_id}", [\App\Http\Controllers\YukkCo\QrisReHitRintisController::class, "show"])->name("cms.yukk_co.qris_re_hit_rintis.item");
    Route::get("yukk_co/qris_re_hit_rintis/create", [\App\Http\Controllers\YukkCo\QrisReHitRintisController::class, "create"])->name("cms.yukk_co.qris_re_hit_rintis.create");
    Route::post("yukk_co/qris_re_hit_rintis/create", [\App\Http\Controllers\YukkCo\QrisReHitRintisController::class, "store"])->name("cms.yukk_co.qris_re_hit_rintis.store");
    Route::post("yukk_co/qris_re_hit_rintis/release/{qris_re_hit_id}", [\App\Http\Controllers\YukkCo\QrisReHitRintisController::class, "release"])->name("cms.yukk_co.qris_re_hit_rintis.release");


    Route::get("yukk_co/partner_order/item/{partner_order_id}", [\App\Http\Controllers\YukkCo\PartnerOrderController::class, "show"])->name("cms.yukk_co.partner_order.show");
    Route::get("yukk_co/partner_order/edit/{partner_order_id}", [\App\Http\Controllers\YukkCo\PartnerOrderController::class, "edit"])->name("cms.yukk_co.partner_order.edit");
    Route::post("yukk_co/partner_order/edit/{partner_order_id}", [\App\Http\Controllers\YukkCo\PartnerOrderController::class, "update"])->name("cms.yukk_co.partner_order.update");


    Route::get("yukk_co/settlement_master/list", [\App\Http\Controllers\YukkCo\SettlementMasterController::class, "index"])->name("cms.yukk_co.settlement_master.list");
    Route::get("yukk_co/settlement_master/item/{settlement_master_id}", [\App\Http\Controllers\YukkCo\SettlementMasterController::class, "show"])->name("cms.yukk_co.settlement_master.show");
    Route::post("yukk_co/settlement_master/export_csv_transaction/{settlement_master_id}", [\App\Http\Controllers\YukkCo\SettlementMasterController::class, "exportCsvTransaction"])->name("cms.yukk_co.settlement_master.export_csv_transaction");

    Route::post("yukk_co/settlement_master/action/{settlement_master_id}", [\App\Http\Controllers\YukkCo\SettlementMasterController::class, "action"])->name("cms.yukk_co.settlement_master.action");

    Route::get("yukk_co/settlement_master/list_switching", [\App\Http\Controllers\YukkCo\SettlementMasterController::class, "listSwitching"])->name("cms.yukk_co.settlement_master.list_switching");

    Route::get("customer/settlement_summary/list", [\App\Http\Controllers\Customers\DisbursementController::class, "index"])->name("cms.beneficiary.disbursement.list");


    Route::get("yukk_co/settlement_transfer/list", [\App\Http\Controllers\YukkCo\SettlementTransferController::class, "index"])->name("cms.yukk_co.settlement_transfer.list");
    Route::get("yukk_co/settlement_transfer/show/{settlement_transfer_id}", [\App\Http\Controllers\YukkCo\SettlementTransferController::class, "show"])->name("cms.yukk_co.settlement_transfer.show");
    Route::post("yukk_co/settlement_transfer/action/{settlement_transfer_id}", [\App\Http\Controllers\YukkCo\SettlementTransferController::class, "action"])->name("cms.yukk_co.settlement_transfer.action");

    Route::get("yukk_co/settlement_debt/list", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "index"])->name("cms.yukk_co.settlement_debt.list");
    Route::get("yukk_co/settlement_debt/item/{settlement_debt_id}", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "show"])->name("cms.yukk_co.settlement_debt.show");
    Route::post("yukk_co/settlement_debt/download_transaction_report", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "downloadTransactionReport"])->name("cms.yukk_co.settlement_debt.download_transaction_report");


    Route::get("yukk_co/settlement_debt/input_dispute/form", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputDisputeForm"])->name("cms.yukk_co.settlement_debt.input_dispute.form");
    Route::post("yukk_co/settlement_debt/input_dispute/summary", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputDisputeSummaryXlsx"])->name("cms.yukk_co.settlement_debt.input_dispute.summary");
    Route::post("yukk_co/settlement_debt/input_dispute/submit", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputDisputeSubmit"])->name("cms.yukk_co.settlement_debt.input_dispute.submit");

    Route::get("yukk_co/settlement_debt/input_sharing_profit/form", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputSharingProfitForm"])->name("cms.yukk_co.settlement_debt.input_sharing_profit.form");
    Route::post("yukk_co/settlement_debt/input_sharing_profit/summary", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputSharingProfitSummaryXlsx"])->name("cms.yukk_co.settlement_debt.input_sharing_profit.summary");
    Route::post("yukk_co/settlement_debt/input_sharing_profit/submit", [\App\Http\Controllers\YukkCo\SettlementDebtController::class, "inputSharingProfitSubmit"])->name("cms.yukk_co.settlement_debt.input_sharing_profit.submit");

    Route::get("yukk_co/disbursement_customer/list", [\App\Http\Controllers\YukkCo\DisbursementCustomerController::class, "index"])->name("cms.yukk_co.disbursement_customer.list");
    Route::get("yukk_co/disbursement_customer/item/{disbursement_customer_id}", [\App\Http\Controllers\YukkCo\DisbursementCustomerController::class, "show"])->name("cms.yukk_co.disbursement_customer.item");
    Route::post("yukk_co/disbursement_customer/resend_email", [\App\Http\Controllers\YukkCo\DisbursementCustomerController::class, "resendEmail"])->name("cms.yukk_co.disbursement_customer.resend_email");

    Route::get("customer/disbursement_customer/list", [\App\Http\Controllers\Customers\DisbursementCustomerController::class, "index"])->name("cms.customer.disbursement_customer.list");
    Route::get("customer/disbursement_customer/item/{disbursement_customer_id}", [\App\Http\Controllers\Customers\DisbursementCustomerController::class, "show"])->name("cms.customer.disbursement_customer.item");

    Route::post("yukk_co/disbursement_customer_transfer/action/{settlement_master_id}", [\App\Http\Controllers\YukkCo\DisbursementCustomerTransferController::class, "action"])->name("cms.yukk_co.disbursement_customer_transfer.action");

    Route::get("yukk_co/disbursement_customer_flip/list", [\App\Http\Controllers\YukkCo\DisbursementCustomerFlipController::class, "index"])->name("cms.yukk_co.disbursement_customer_flip.list");

    Route::get("yukk_co/disbursement_customer_transfer_bulk/list", [\App\Http\Controllers\YukkCo\DisbursementCustomerTransferBulkController::class, "index"])->name("cms.yukk_co.disbursement_customer_transfer_bulk.list");
    Route::get("yukk_co/disbursement_customer_transfer_bulk/item/{disbursement_customer_id}", [\App\Http\Controllers\YukkCo\DisbursementCustomerTransferBulkController::class, "show"])->name("cms.yukk_co.disbursement_customer_transfer_bulk.item");
    Route::post("yukk_co/disbursement_customer_transfer_bulk/export_excel/{disbursement_customer_id}", [\App\Http\Controllers\YukkCo\DisbursementCustomerTransferBulkController::class, "exportExcel"])->name("cms.yukk_co.disbursement_customer_transfer_bulk.export_excel");
    Route::post("yukk_co/disbursement_customer_transfer_bulk/action/{disbursement_customer_id}", [\App\Http\Controllers\YukkCo\DisbursementCustomerTransferBulkController::class, "action"])->name("cms.yukk_co.disbursement_customer_transfer_bulk.action");


    Route::post("customer/settlement_master/download_excel", [\App\Http\Controllers\Customers\SettlementMasterController::class, "downloadExcel"])->name("cms.customer.settlement_master.download_excel");
    Route::post("customer/settlement_pg_master/download_excel", [\App\Http\Controllers\Customers\SettlementPgMasterController::class, "downloadExcel"])->name("cms.customer.settlement_pg_master.download_excel");


    Route::get("yukk_co/disbursement_partner/list", [\App\Http\Controllers\YukkCo\DisbursementPartnerController::class, "index"])->name("cms.yukk_co.disbursement_partner.list");
    Route::get("yukk_co/disbursement_partner/item/{disbursement_partner_id}", [\App\Http\Controllers\YukkCo\DisbursementPartnerController::class, "show"])->name("cms.yukk_co.disbursement_partner.item");
    Route::post("yukk_co/disbursement_partner/action/{disbursement_partner_id}", [\App\Http\Controllers\YukkCo\DisbursementPartnerController::class, "action"])->name("cms.yukk_co.disbursement_partner.action");

    Route::get("partner/disbursement_partner/list", [\App\Http\Controllers\Partners\DisbursementPartnerController::class, "index"])->name("cms.partner.disbursement_partner.list");
    Route::get("partner/disbursement_partner/item/{disbursement_partner_id}", [\App\Http\Controllers\Partners\DisbursementPartnerController::class, "show"])->name("cms.partner.disbursement_partner.item");

    Route::get("partner/disbursement_beneficiary/list", [\App\Http\Controllers\Partners\DisbursementCustomerController::class, "index"])->name("cms.partner.disbursement_customer.list");
    Route::get("partner/disbursement_beneficiary/item/{disbursement_customer_id}", [\App\Http\Controllers\Partners\DisbursementCustomerController::class, "show"])->name("cms.partner.disbursement_customer.item");
    Route::post("partner/settlement_master/download_excel", [\App\Http\Controllers\Partners\SettlementMasterController::class, "downloadExcel"])->name("cms.partner.settlement_master.download_excel");

    Route::get("customer/disbursement_edit/item", [\App\Http\Controllers\Customers\DisbursementEditController::class, "index"])->name("cms.customer.disbursement_edit.setting");
    Route::get("customer/beneficiary_edit_request/edit", [\App\Http\Controllers\Customers\DisbursementEditController::class, "detail"])->name("cms.customer.disbursement_edit.detail");

    Route::post("customer/beneficiary_edit_request/create", [\App\Http\Controllers\Customers\DisbursementEditController::class, "edit"])->name("cms.customer.disbursement_edit.edit");
    Route::post("customer/beneficiary_edit_request/request_otp", [\App\Http\Controllers\Customers\DisbursementEditController::class, "requestOtp"])->name("cms.customer.disbursement_edit.request_otp");

    Route::get("yukk_co/transaction_payment/list", [\App\Http\Controllers\YukkCo\TransactionPaymentController::class, "index"])->name("cms.yukk_co.transaction_payment.list");
    Route::get("yukk_co/transaction_payment/{transaction_payment_id}", [\App\Http\Controllers\YukkCo\TransactionPaymentController::class, "show"])->name("cms.yukk_co.transaction_payment.item");


    // YUKK Payment Gateway
    Route::get("yukk_co/payment_channel/list", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "index"])->name("cms.yukk_co.payment_channel.list");
    Route::get("yukk_co/payment_channel/item/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "show"])->name("cms.yukk_co.payment_channel.item");
    Route::get("yukk_co/payment_channel/edit/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "edit"])->name("cms.yukk_co.payment_channel.edit");
    Route::post("yukk_co/payment_channel/edit/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "update"])->name("cms.yukk_co.payment_channel.update");
    Route::get("yukk_co/payment_channel/edit_status/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "editStatus"])->name("cms.yukk_co.payment_channel.edit_status");
    Route::post("yukk_co/payment_channel/edit_status/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelController::class, "updateStatus"])->name("cms.yukk_co.payment_channel.update_status");

    Route::get("yukk_co/payment_channel_category/list", [\App\Http\Controllers\YukkCo\PaymentChannelCategoryController::class, "index"])->name("cms.yukk_co.payment_channel_category.list");
    Route::get("yukk_co/payment_channel_category/item/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelCategoryController::class, "show"])->name("cms.yukk_co.payment_channel_category.item");
    Route::get("yukk_co/payment_channel_category/edit/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelCategoryController::class, "edit"])->name("cms.yukk_co.payment_channel_category.edit");
    Route::post("yukk_co/payment_channel_category/edit/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PaymentChannelCategoryController::class, "update"])->name("cms.yukk_co.payment_channel_category.update");

    Route::get("yukk_co/merchant_branch_pg/list", [\App\Http\Controllers\YukkCo\MerchantBranchPaymentGatewayController::class, "index"])->name("cms.yukk_co.merchant_branch_pg.list");
    Route::get("yukk_co/merchant_branch_pg/item/{merchant_branch_id}", [\App\Http\Controllers\YukkCo\MerchantBranchPaymentGatewayController::class, "show"])->name("cms.yukk_co.merchant_branch_pg.item");
    Route::get("yukk_co/merchant_branch_pg/edit/{merchant_branch_id}", [\App\Http\Controllers\YukkCo\MerchantBranchPaymentGatewayController::class, "edit"])->name("cms.yukk_co.merchant_branch_pg.edit");
    Route::post("yukk_co/merchant_branch_pg/edit/{merchant_branch_id}", [\App\Http\Controllers\YukkCo\MerchantBranchPaymentGatewayController::class, "update"])->name("cms.yukk_co.merchant_branch_pg.update");

    Route::get("yukk_co/transaction_pg/list", [\App\Http\Controllers\YukkCo\TransactionPGController::class, "index"])->name("cms.yukk_co.transaction_pg.list");
    Route::get("yukk_co/transaction_pg/item/{transaction_id}", [\App\Http\Controllers\YukkCo\TransactionPGController::class, "show"])->name("cms.yukk_co.transaction_pg.item");

    Route::get("yukk_co/settlement_pg_master/list", [\App\Http\Controllers\YukkCo\SettlementPgMasterController::class, "index"])->name("cms.yukk_co.settlement_pg_master.list");
    Route::get("yukk_co/settlement_pg_master/item/{settlement_pg_master_id}", [\App\Http\Controllers\YukkCo\SettlementPgMasterController::class, "show"])->name("cms.yukk_co.settlement_pg_master.show");
    Route::get("yukk_co/settlement_pg_master/list_source_of_fund", [\App\Http\Controllers\YukkCo\SettlementPgMasterController::class, "listSourceOfFund"])->name("cms.yukk_co.settlement_pg_master.list_source_of_fund");

    Route::get("yukk_co/partner_mdr_pg/list", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "index"])->name("cms.yukk_co.partner_mdr_pg.list");
    Route::get("yukk_co/partner_mdr_pg/item/{partner_id}/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "show"])->name("cms.yukk_co.partner_mdr_pg.item");
    Route::get("yukk_co/partner_mdr_pg/edit/{partner_id}/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "edit"])->name("cms.yukk_co.partner_mdr_pg.edit");
    Route::post("yukk_co/partner_mdr_pg/edit/{partner_id}/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "update"])->name("cms.yukk_co.partner_mdr_pg.update");
    Route::get("yukk_co/partner_mdr_pg/create", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "create"])->name("cms.yukk_co.partner_mdr_pg.create");
    Route::post("yukk_co/partner_mdr_pg/create", [\App\Http\Controllers\YukkCo\PartnerMdrInternalController::class, "store"])->name("cms.yukk_co.partner_mdr_pg.store");


    Route::get("yukk_co/provider_pg/list", [\App\Http\Controllers\YukkCo\ProviderController::class, "index"])->name("cms.yukk_co.provider.list");
    Route::get("yukk_co/provider_pg/item/{provider_id}", [\App\Http\Controllers\YukkCo\ProviderController::class, "show"])->name("cms.yukk_co.provider.item");
    Route::get("yukk_co/provider_pg/edit/{provider_id}", [\App\Http\Controllers\YukkCo\ProviderController::class, "edit"])->name("cms.yukk_co.provider.edit");
    Route::post("yukk_co/provider_pg/edit/{provider_id}", [\App\Http\Controllers\YukkCo\ProviderController::class, "update"])->name("cms.yukk_co.provider.update");


    Route::get("yukk_co/provider_has_payment_channel/item/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\ProviderHasPaymentChannelController::class, "show"])->name("cms.yukk_co.provider_has_payment_channel.item");
    Route::get("yukk_co/provider_has_payment_channel/create/{provider_id}", [\App\Http\Controllers\YukkCo\ProviderHasPaymentChannelController::class, "create"])->name("cms.yukk_co.provider_has_payment_channel.create");
    Route::post("yukk_co/provider_has_payment_channel/create/{provider_id}", [\App\Http\Controllers\YukkCo\ProviderHasPaymentChannelController::class, "store"])->name("cms.yukk_co.provider_has_payment_channel.store");
    Route::get("yukk_co/provider_has_payment_channel/edit/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\ProviderHasPaymentChannelController::class, "edit"])->name("cms.yukk_co.provider_has_payment_channel.edit");
    Route::post("yukk_co/provider_has_payment_channel/edit/{provider_id}/{payment_channel_id}", [\App\Http\Controllers\YukkCo\ProviderHasPaymentChannelController::class, "update"])->name("cms.yukk_co.provider_has_payment_channel.update");


    Route::get("yukk_co/partner/list", [\App\Http\Controllers\YukkCo\PartnerController::class, "index"])->name("cms.yukk_co.partner.list");
    Route::get("yukk_co/partner/item/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerController::class, "show"])->name("cms.yukk_co.partner.item");
    Route::get("yukk_co/partner/edit/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerController::class, "edit"])->name("cms.yukk_co.partner.edit");
    Route::post("yukk_co/partner/edit/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerController::class, "update"])->name("cms.yukk_co.partner.update");
    Route::post("yukk_co/partner/generate_client_id_secret/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerController::class, "generateClientIdSecret"])->name("cms.yukk_co.partner.generate_Client_id_secret");

    Route::get("yukk_co/partner/has_merchant_branch/list/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerHasMerchantBranchController::class, "index"])->name("cms.yukk_co.partner_has_merchant_branch.list");

    Route::get("yukk_co/skip_process_day/list", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "index"])->name("cms.yukk_co.skip_process_day.list");
    Route::get("yukk_co/skip_process_day/item/{skip_process_day_id}", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "show"])->name("cms.yukk_co.skip_process_day.item");
    Route::get("yukk_co/skip_process_day/create", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "create"])->name("cms.yukk_co.skip_process_day.create");
    Route::post("yukk_co/skip_process_day/create", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "store"])->name("cms.yukk_co.skip_process_day.store");
    Route::get("yukk_co/skip_process_day/edit/{skip_process_day_id}", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "edit"])->name("cms.yukk_co.skip_process_day.edit");
    Route::post("yukk_co/skip_process_day/edit/{skip_process_day_id}", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "update"])->name("cms.yukk_co.skip_process_day.update");
    Route::post("yukk_co/skip_process_day/delete/{skip_process_day_id}", [\App\Http\Controllers\YukkCo\SkipProcessDayController::class, "delete"])->name("cms.yukk_co.skip_process_day.delete");

    Route::get("yukk_co/settlement_pg_calendar/list", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "index"])->name("cms.yukk_co.settlement_pg_calendar.list");
    Route::get("yukk_co/settlement_pg_calendar/item/{settlement_pg_calendar_id}", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "show"])->name("cms.yukk_co.settlement_pg_calendar.item");
    Route::get("yukk_co/settlement_pg_calendar/create", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "create"])->name("cms.yukk_co.settlement_pg_calendar.create");
    Route::post("yukk_co/settlement_pg_calendar/store", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "store"])->name("cms.yukk_co.settlement_pg_calendar.store");
    Route::get("yukk_co/settlement_pg_calendar/edit/{settlement_pg_calendar_id}", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "edit"])->name("cms.yukk_co.settlement_pg_calendar.edit");
    Route::post("yukk_co/settlement_pg_calendar/edit/{settlement_pg_calendar_id}", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "update"])->name("cms.yukk_co.settlement_pg_calendar.update");
    Route::post("yukk_co/settlement_pg_calendar/delete/{settlement_pg_calendar_id}", [\App\Http\Controllers\YukkCo\SettlementPgCalendarController::class, "delete"])->name("cms.yukk_co.settlement_pg_calendar.delete");

    // YUKK Split Disbursement
    Route::get("yukk_co/disbursement_split/setting", [\App\Http\Controllers\YukkCo\SettingController::class, "index"])->name("cms.yukk_co.disbursement.setting");
    Route::post("yukk_co/disbursement_split/edit", [\App\Http\Controllers\YukkCo\SettingController::class, "edit"])->name("cms.yukk_co.disbursement.edit");

    Route::get("merchant_branch/transaction_pg/list", [\App\Http\Controllers\MerchantBranches\TransactionPGController::class, "index"])->name("cms.merchant_branch.transaction_pg.list");
    Route::get("merchant_branch/transaction_pg/item/{transaction_pg_id}", [\App\Http\Controllers\MerchantBranches\TransactionPGController::class, "show"])->name("cms.merchant_branch.transaction_pg.item");


    Route::get("partner/transaction_pg/list", [\App\Http\Controllers\Partners\TransactionPGController::class, "index"])->name("cms.partner.transaction_pg.list");
    Route::get("partner/transaction_pg/item/{transaction_pg_id}", [\App\Http\Controllers\Partners\TransactionPGController::class, "show"])->name("cms.partner.transaction_pg.item");


    Route::get("yukk_co/customer_invoice_master/list", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "index"])->name("cms.yukk_co.customer_invoice_master.index");
    Route::get("yukk_co/customer_invoice_master/item/{customer_invoice_master_id}", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "show"])->name("cms.yukk_co.customer_invoice_master.item");
    Route::get("yukk_co/customer_invoice_master/search_customer_partner", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "searchCustomerPartner"])->name("cms.yukk_co.customer_invoice_master.search_customer_partner");
    Route::get("yukk_co/customer_invoice_master/preview_invoice", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "previewInvoice"])->name("cms.yukk_co.customer_invoice_master.create_invoice");
    Route::get("yukk_co/customer_invoice_master/invoice_pdf_preview", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "invoicePdfPreview"])->name("cms.yukk_co.customer_invoice_master.invoice_pdf_preview");
    Route::get("yukk_co/customer_invoice_master/reimbursement_pdf_preview", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "reimbursementPdfPreview"])->name("cms.yukk_co.customer_invoice_master.reimbursement_pdf_preview");
    Route::get("yukk_co/customer_invoice_master/kwitansi_pdf_preview", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "kwitansiPdfPreview"])->name("cms.yukk_co.customer_invoice_master.kwitansi_pdf_preview");
    Route::get("yukk_co/customer_invoice_master/kwitansi_pdf", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "kwitansiPdf"])->name("cms.yukk_co.customer_invoice_master.kwitansi_pdf");
    Route::post("yukk_co/customer_invoice_master/preview_download_transaction_pg_list", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "previewDownloadTransaction"])->name("cms.yukk_co.customer_invoice_master.preview_download_transaction_pg_list");
    Route::post("yukk_co/customer_invoice_master/create_customer_invoice", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "createCustomerInvoice"])->name("cms.yukk_co.customer_invoice_master.create_customer_invoice");
    Route::post("yukk_co/customer_invoice_master/post_invoice", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "postInvoice"])->name("cms.yukk_co.customer_invoice_master.post_invoice");
    Route::post("yukk_co/customer_invoice_master/download_transaction", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "downloadTransaction"])->name("cms.yukk_co.customer_invoice_master.download_transaction_pg_list");
    Route::post("yukk_co/customer_invoice_master/delete", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "delete"])->name("cms.yukk_co.customer_invoice_master.delete");
    Route::post("yukk_co/customer_invoice_master/pay_invoice", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "payInvoice"])->name("cms.yukk_co.customer_invoice_master.pay_invoice");
    Route::post("yukk_co/customer_invoice_master/revert_status_email", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "revertStatusEmail"])->name("cms.yukk_co.customer_invoice_master.revert_status_email");
    Route::post("yukk_co/customer_invoice_master/change_customer_email", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "changeCustomerEmail"])->name("cms.yukk_co.customer_invoice_master.change_customer_email");
    Route::post("yukk_co/customer_invoice_master/trigger_send_customer_email", [\App\Http\Controllers\YukkCo\CustomerInvoiceMasterController::class, "triggerSendCustomerEmail"])->name("cms.yukk_co.customer_invoice_master.trigger_send_customer_email");

    Route::get("yukk_co/customer_invoice_provider/list", [\App\Http\Controllers\YukkCo\CustomerInvoiceProviderController::class, "index"])->name("cms.yukk_co.customer_invoice_provider.index");
    Route::get("yukk_co/customer_invoice_provider/detail", [\App\Http\Controllers\YukkCo\CustomerInvoiceProviderController::class, "detail"])->name("cms.yukk_co.customer_invoice_provider.detail");
    Route::get("yukk_co/customer_invoice_provider/download_transaction_list", [\App\Http\Controllers\YukkCo\CustomerInvoiceProviderController::class, "downloadTransactionList"])->name("cms.yukk_co.customer_invoice_provider.download_transaction_list");

    Route::get("yukk_co/partner_payout_master/list", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "index"])->name("cms.yukk_co.partner_payout_master.index");
    Route::get("yukk_co/partner_payout_master/show/{partner_payout_master_id}", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "show"])->name("cms.yukk_co.partner_payout_master.show");
    Route::get("yukk_co/partner_payout_master/create/search", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "createSearch"])->name("cms.yukk_co.partner_payout_master.create_search");
    Route::get("yukk_co/partner_payout_master/create/partner/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "createPartner"])->name("cms.yukk_co.partner_payout_master.create_partner");
    Route::post("yukk_co/partner_payout_master/create/partner/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "createPartnerPayoutMaster"])->name("cms.yukk_co.partner_payout_master.create_partner_payout_master");
    Route::post("yukk_co/partner_payout_master/mark_as_paid", [\App\Http\Controllers\YukkCo\PartnerPayoutMasterController::class, "markAsPaid"])->name("cms.yukk_co.partner_payout_master.mark_as_paid");

    Route::get("yukk_co/partner_webhook/resend_dynamic", [\App\Http\Controllers\YukkCo\PartnerWebhookController::class, "resendDynamicForm"])->name("yukk_co.partner_webhook.resend_dynamic_form");
    Route::post("yukk_co/partner_webhook/resend_dynamic", [\App\Http\Controllers\YukkCo\PartnerWebhookController::class, "resendDynamicProcess"])->name("yukk_co.partner_webhook.resend_dynamic_process");

    Route::get("yukk_co/partner_webhook/resend_static", [\App\Http\Controllers\YukkCo\PartnerWebhookController::class, "resendStaticForm"])->name("yukk_co.partner_webhook.resend_static_form");
    Route::post("yukk_co/partner_webhook/resend_static", [\App\Http\Controllers\YukkCo\PartnerWebhookController::class, "resendStaticProcess"])->name("yukk_co.partner_webhook.resend_static_process");

    //Activition Merchant
    Route::get("yukk_co/manage_company", [\App\Http\Controllers\YukkCo\CompanyController::class, "index"])->name("yukk_co.companies.list");
    Route::post("yukk_co/companies/add", [\App\Http\Controllers\YukkCo\CompanyController::class, "add"])->name("yukk_co.companies.add");
    Route::get("yukk_co/manage_company/{company_id}/edit", [\App\Http\Controllers\YukkCo\CompanyController::class, "edit"])->name("yukk_co.companies.edit");
    Route::get("yukk_co/manage_company/{id}", [\App\Http\Controllers\YukkCo\CompanyController::class, "show"])->name("yukk_co.companies.show");
    Route::post("yukk_co/manage_company/{company_id}/edit", [\App\Http\Controllers\YukkCo\CompanyController::class, "update"])->name("yukk_co.companies.update");
    Route::get("yukk_co/manage_company/{company_id}/destroy", [\App\Http\Controllers\YukkCo\CompanyController::class, "deleteCompany"])->name("yukk_co.companies.destroy");
    Route::post("yukk_co/companies/{company_id}/contracts", [\App\Http\Controllers\YukkCo\CompanyContractController::class, "store"])->name("yukk_co.companies.store_contract");
    Route::post("yukk_co/contracts/{id}/delete", [\App\Http\Controllers\YukkCo\CompanyContractController::class, "deleteContract"])->name("yukk_co.company_contracts.destroy");

    Route::get("yukk_co/merchant", [\App\Http\Controllers\YukkCo\MerchantController::class, "index"])->name('yukk_co.merchants.list');
    Route::get("yukk_co/merchant/add", [\App\Http\Controllers\YukkCo\MerchantController::class, "add"])->name('yukk_co.merchant.add');
    Route::get("yukk_co/merchant/{merchant_id}/edit", [\App\Http\Controllers\YukkCo\MerchantController::class, "edit"])->name("yukk_co.merchant.detail");
    Route::post("yukk_co/merchant/{merchant_id}/edit", [\App\Http\Controllers\YukkCo\MerchantController::class, "update"])->name("yukk_co.merchant.edit");
    Route::post("yukk_co/merchant/store", [\App\Http\Controllers\YukkCo\MerchantController::class, "store"])->name("yukk_co.merchant.store");
    Route::get("yukk_co/merchant/{merchant_id}/delete", [\App\Http\Controllers\YukkCo\MerchantController::class, "destroy"])->name("yukk_co.merchant.delete");
    Route::get("yukk_co/merchant/{id}", [\App\Http\Controllers\YukkCo\MerchantController::class, "show"])->name("yukk_co.merchant.show");
    Route::get("yukk_co/manage_merchant/data", [\App\Http\Controllers\YukkCo\MerchantController::class, "data"])->name("yukk_co.merchants.data");

    Route::get("yukk_co/merchant_branch", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "index"])->name("yukk_co.merchant_branch.list");
    Route::get("yukk_co/merchant_branch/add", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "add"])->name("yukk_co.merchant_branch.add");
    Route::get("yukk_co/merchant_branch/bulk_search", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "bulkSearchForm"])->name("yukk_co.merchant_branch.bulk_search.form");
    Route::post("yukk_co/merchant_branch/bulk_search", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "bulkSearchPost"])->name("yukk_co.merchant_branch.bulk_search.post");
    Route::get("yukk_co/merchant_branch/{id}", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "show"])->name("yukk_co.merchant_branch.show");
    Route::get("yukk_co/merchant_branch/{merchant_branch_id}/edit", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "edit"])->name("yukk_co.merchant_branch.edit");
    Route::post("yukk_co/merchant_branch/{merchant_branch_id}/edit", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "update"])->name("yukk_co.merchant_branch.update");
    Route::post("yukk_co/merchant_branch/store", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "store"])->name("yukk_co.merchant_branch.store");
    Route::post("yukk_co/merchant_branch/inactivate", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "inactivate"])->name("yukk_co.merchant_branch.inactive");

    Route::get("yukk_co/merchant_branch/bulk/upload", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "bulkCreate"])->name("yukk_co.merchant_branch.bulk.upload");
    Route::post("yukk_co/merchant_branch/bulk/preview", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "bulkPreview"])->name("yukk_co.merchant_branch.bulk.preview");
    Route::post("yukk_co/merchant_branch/bulk/store", [\App\Http\Controllers\YukkCo\MerchantBranchController::class, "bulkStore"])->name("yukk_co.merchant_branch.bulk.create");

    Route::post("yukk_co/merchant/list/json", [\App\Http\Controllers\YukkCo\MerchantController::class, 'listJson'])->name("yukk_co.merchant.list_json");
    Route::post("yukk_co/company/list/json", [\App\Http\Controllers\YukkCo\CompanyController::class, 'listJson'])->name("yukk_co.company.list_json");
    Route::post("yukk_co/partner/list/json", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, 'listJson'])->name("yukk_co.partner.list_json");
    Route::post("yukk_co/partner_fee/list/json", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, 'getPartnerFeeJSON'])->name("yukk_co.partner_fee.list_json");

    Route::post("yukk_co/province/list/json", [\App\Http\Controllers\YukkCo\ProvinceController::class, 'listJson'])->name("yukk_co.province.list_json");
    Route::post("yukk_co/city/list/json", [\App\Http\Controllers\YukkCo\CityController::class, 'listJson'])->name("yukk_co.city.list_json");
    Route::post("yukk_co/region/list/json", [\App\Http\Controllers\YukkCo\RegionController::class, 'listJson'])->name("yukk_co.region.list_json");

    Route::post("yukk_co/get_region", [\App\Http\Controllers\Inbound\RequestController::class, 'getRegion'])->name('region.list_to_production');
    Route::post("yukk_co/get_city", [\App\Http\Controllers\Inbound\RequestController::class, 'getCity'])->name('city.list_to_production');
    Route::post("yukk_co/electronic_certificate/ocr", [\App\Http\Controllers\Inbound\RequestController::class, 'ocrKTP'])->name('electronic_certificate.ocr');
    Route::get("yukk_co/electronic_certificate/get_kyc_logs", [\App\Http\Controllers\Inbound\RequestController::class, 'getKycLogs'])->name('electronic_certificate.get_kyc_logs');

    Route::get("yukk_co/data_verification", [\App\Http\Controllers\Inbound\RequestController::class, 'index'])->name('yukk_co.data_verification.list');
    Route::post("yukk_co/data_verification/{id}/update", [\App\Http\Controllers\Inbound\RequestController::class, 'update'])->name('yukk_co.data_verification.update');
    Route::get("yukk_co/data_verification/{id}/{type}", [\App\Http\Controllers\Inbound\RequestController::class, 'show'])->name('yukk_co.data_verification.detail');
    Route::post("yukk_co/data_verification/{id}/{type}", [\App\Http\Controllers\Inbound\RequestController::class, 'changeStatus'])->name('yukk_co.data_verification.status_update');
    Route::get("production/image", [\App\Http\Controllers\Inbound\RequestController::class, 'getImageIndex'])->name('get.image.index');
    Route::get("data_verification/image/download/{name}/{path}", [\App\Http\Controllers\Inbound\RequestController::class, 'downloadImage'])->where('path', '([A-z0-9\-\_\.\/]+)?')->name('download.image.data_verification');
    Route::get("production/image/{path}", [\App\Http\Controllers\Inbound\RequestController::class, 'getImage'])->where('path', '([A-z0-9\-\_\.\/]+)?')->name('index.image.data_verification');

    //Partner Activation
    Route::get("yukk_co/partners", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "index"])->name("yukk_co.partner.list");
    // download_public_key
    Route::get("yukk_co/partners/download_public_key", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "downloadPublicKey"])->name("yukk_co.partner.download_public_key");
    Route::get("yukk_co/partner/detail/{id}", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "detail"])->name("yukk_co.partner.detail");
    Route::get("yukk_co/partners/edit/{id}", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "edit"])->name("yukk_co.partner.edit");
    Route::post("yukk_co/partners/edit/{id}", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "update"])->name("yukk_co.partner.update");
    Route::get("yukk_co/partners/create", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "create"])->name("yukk_co.partner.create");
    Route::post("yukk_co/partners/store", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "store"])->name("yukk_co.partner.store");

    Route::get("yukk_co/partners/{partner_id}/credentials", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "index"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.VIEW')->name("cms.yukk_co.partners.credentials.index");
    Route::get("yukk_co/partners/{partner_id}/credentials/create", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "create"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.CREATE')->name("cms.yukk_co.partners.credentials.create");
    Route::get("yukk_co/partners/{partner_id}/payment-channels", [\App\Http\Controllers\JSON\PaymentGateway\PaymentChannelController::class, "index"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.CREATE')->name("cms.yukk_co.partners.credentials.payment_channels");
    Route::get("yukk_co/partners/{partner_id}/credentials/{token}", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "show"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.VIEW')->name("cms.yukk_co.partners.credentials.show");
    Route::get("yukk_co/partners/{partner_id}/credentials/{token}/edit", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "edit"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.UPDATE')->name("cms.yukk_co.partners.credentials.edit");
    Route::post("yukk_co/partners/{partner_id}/credentials", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "store"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.CREATE')->name("cms.yukk_co.partners.credentials.store");
    Route::put("yukk_co/partners/{partner_id}/credentials/{token}", [\App\Http\Controllers\YukkCo\PartnerProviderCredentialController::class, "update"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.UPDATE')->name("cms.yukk_co.partners.credentials.update");


    Route::get("json/partners/{partner_id}/payment-channels", [\App\Http\Controllers\JSON\PaymentGateway\PaymentChannelController::class, "index"])
        ->middleware('can-access:PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.CREATE')->name("cms.yukk_co.partners.credentials.payment_channels");

    //Partner Fee Activation
    Route::get("yukk_co/partner_fees", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "index"])->name("yukk_co.partner_fee.list");
    Route::get("yukk_co/partner_fees/detail/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "detail"])->name("yukk_co.partner_fee.detail");
    Route::get("yukk_co/partner_fees/edit/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "edit"])->name("yukk_co.partner_fee.edit");
    Route::post("yukk_co/partner_fees/edit/{partner_id}", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "update"])->name("yukk_co.partner_fee.update");
    Route::get("yukk_co/partner_fees/create", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "create"])->name("yukk_co.partner_fee.create");
    Route::post("yukk_co/partner_fees/store", [\App\Http\Controllers\YukkCo\PartnerFeeController::class, "store"])->name("yukk_co.partner_fee.store");

    //Event
    Route::get("yukk_co/events", [\App\Http\Controllers\YukkCo\EventController::class, "index"])->name("yukk_co.event.list");
    Route::get("yukk_co/events/detail/{partner_id}", [\App\Http\Controllers\YukkCo\EventController::class, "detail"])->name("yukk_co.event.detail");
    Route::get("yukk_co/events/edit/{partner_id}", [\App\Http\Controllers\YukkCo\EventController::class, "edit"])->name("yukk_co.event.edit");
    Route::post("yukk_co/events/edit/{partner_id}", [\App\Http\Controllers\YukkCo\EventController::class, "update"])->name("yukk_co.event.update");
    Route::get("yukk_co/events/create", [\App\Http\Controllers\YukkCo\EventController::class, "create"])->name("yukk_co.event.create");
    Route::post("yukk_co/events/store", [\App\Http\Controllers\YukkCo\EventController::class, "store"])->name("yukk_co.event.store");

    //Manage EDC
    Route::get("yukk_co/list_edcs", [\App\Http\Controllers\YukkCo\EdcController::class, "index"])->name("yukk_co.edc.list");
    Route::post("yukk_co/list_edc/update/{id}", [\App\Http\Controllers\YukkCo\EdcController::class, "update"])->name("yukk_co.edc.update");
    Route::get("yukk_co/list_edc/detail/{id}", [\App\Http\Controllers\YukkCo\EdcController::class, "detail"])->name("yukk_co.edc.detail");
    Route::get("yukk_co/list_edc/edit/{id}", [\App\Http\Controllers\YukkCo\EdcController::class, "edit"])->name("yukk_co.edc.edit");

    //Manage QRIS Setting
    Route::get("yukk_co/qris-settings/list", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "index"])->name("yukk_co.qris_setting.list");
    Route::get('yukk_co/qris-edc/{id}/mail', [\App\Http\Controllers\YukkCo\QRISSettingController::class, "mail"])->name('yukk_co.qris_setting.mail');
    Route::get("yukk_co/qris-edc/{id}/published", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "published"])->name("yukk_co.qris_setting.published");
    Route::post("yukk_co/qris-edc/{id}/edit", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "update"])->name("yukk_co.qris_setting.update");
    Route::post("yukk_co/qris-edc/{id}/dynamic", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "createDynamic"])->name("yukk_co.qris_setting.create.dynamic");
    Route::get("yukk_co/qris-edc/{id}/edit", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "edit"])->name("yukk_co.qris_setting.edit");
    Route::get("yukk_co/qris-edc/{id}/detail", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "detail"])->name("yukk_co.qris_setting.detail");
    Route::get("yukk_co/qris-edc/{id}/mail/preview", [\App\Http\Controllers\YukkCo\QRISSettingController::class, "preview"])->name("yukk_co.qris_setting.preview");

    Route::post("yukk_co/qris-setting/{id}/update", [\App\Http\Controllers\YukkCo\QRISSettingController::class, 'updateData'])->name("yukk_co.qris_setting.update-data");

    Route::get("yukk_co/manage_company/{id}/store", [\App\Http\Controllers\YukkCo\CompanyController::class, "show"])->name("yukk_co.companies.detail");
    Route::post("yukk_co/manage_company/store", [\App\Http\Controllers\YukkCo\CompanyController::class, "storeName"])->name("yukk_co.companies.store");

    //Owner Menu
    Route::get("yukk_co/manage_owner", [\App\Http\Controllers\YukkCo\OwnerController::class, "index"])->name("yukk_co.owners.list");
    Route::get("yukk_co/manage_owner/image", [\App\Http\Controllers\YukkCo\OwnerController::class, "getImage"])->name("yukk_co.owners.image");
    Route::middleware('can-access:MASTER_DATA.OWNERS.CREATE')->get("yukk_co/manage_owner/create", [\App\Http\Controllers\YukkCo\OwnerController::class, "create"])->name("yukk_co.owners.create");
    Route::middleware('can-access:MASTER_DATA.OWNERS.CREATE')->post("yukk_co/manage_owner/store", [\App\Http\Controllers\YukkCo\OwnerController::class, "store"])->name("yukk_co.owners.store");
    Route::post("yukk_co/manage_owner/verify", [\App\Http\Controllers\YukkCo\OwnerController::class, "verify"])->name("yukk_co.owners.verify");
    Route::get("yukk_co/manage_owner/fetch/{id}", [\App\Http\Controllers\YukkCo\OwnerController::class, "getOwnerDetails"])->name("yukk_co.owners.get_owner");
    Route::post("yukk_co/manage_owner/datatable", [\App\Http\Controllers\YukkCo\OwnerController::class, "data"])->name("yukk_co.owners.datatable");
    Route::post("yukk_co/manage_owner/scan/ktp", [\App\Http\Controllers\YukkCo\OwnerController::class, "scanKtp"])->name("yukk_co.owners.scan.ktp");
    Route::middleware('can-access:MASTER_DATA.OWNERS.EDIT')->post("yukk_co/manage_owner/{id}/update", [\App\Http\Controllers\YukkCo\OwnerController::class, "update"])->name("yukk_co.owners.update");
    Route::middleware('can-access:MASTER_DATA.OWNERS.EDIT')->get("yukk_co/manage_owner/{id}/edit", [\App\Http\Controllers\YukkCo\OwnerController::class, "edit"])->name("yukk_co.owners.edit");
    Route::middleware('can-access:MASTER_DATA.OWNERS.EDIT')->post("yukk_co/manage_owner/{id}/toggle", [\App\Http\Controllers\YukkCo\OwnerController::class, "toggle"])->name("yukk_co.owners.toggle");
    Route::middleware('can-access:MASTER_DATA.OWNERS.VIEW')->get("yukk_co/manage_owner/{id}", [\App\Http\Controllers\YukkCo\OwnerController::class, "show"])->name("yukk_co.owners.detail");
    Route::get("yukk_co/manage_owner/details/{id}", [\App\Http\Controllers\YukkCo\OwnerController::class, "getOwnerDetails"])->name("yukk_co.owners.get_owner");

    Route::get("yukk_co/manage_customer", [\App\Http\Controllers\YukkCo\CustomerController::class, "index"])->name("yukk_co.customers.list");
    Route::post("yukk_co/manage_customer/data", [\App\Http\Controllers\YukkCo\CustomerController::class, "data"])->name("yukk_co.customers.data");
    Route::post("yukk_co/manage_customer/datatable", [\App\Http\Controllers\YukkCo\CustomerController::class, "data"])->name("yukk_co.customers.datatable");
    // getImage of Customer
    Route::get("yukk_co/manage_customer/image", [\App\Http\Controllers\YukkCo\CustomerController::class, "getImage"])->name("yukk_co.customers.image");
    Route::get("yukk_co/manage_customer/create", [\App\Http\Controllers\YukkCo\CustomerController::class, "create"])->name("yukk_co.customers.create");
    Route::get("yukk_co/manage_customer/check_ktp", [\App\Http\Controllers\YukkCo\CustomerController::class, "checkKTPEntries"])->name("yukk_co.customers.check_ktp");
    Route::get("yukk_co/manage_customer_whitelist/create", [\App\Http\Controllers\YukkCo\CustomerController::class, "createWhitelist"])->name("yukk_co.customers.create_whitelist");
    Route::get("yukk_co/manage_customer/{id}", [\App\Http\Controllers\YukkCo\CustomerController::class, "show"])->name("yukk_co.customers.detail");
    Route::get("yukk_co/manage_customer/{id}/edit", [\App\Http\Controllers\YukkCo\CustomerController::class, "edit"])->name("yukk_co.customers.edit");
    Route::post("yukk_co/manage_customer", [\App\Http\Controllers\YukkCo\CustomerController::class, "store"])->name("yukk_co.customers.store");
    Route::post("yukk_co/manage_customer/{customer_id}", [\App\Http\Controllers\YukkCo\CustomerController::class, "update"])->name("yukk_co.customers.update");
    Route::post("yukk_co/manage_customer/store_bank/{customer_id}", [\App\Http\Controllers\YukkCo\CustomerController::class, "updateBankOnly"])->name("yukk_co.customers.update_bank");
    Route::post("yukk_co/manage_customer_whitelist", [\App\Http\Controllers\YukkCo\CustomerController::class, "storeWhitelist"])->name("yukk_co.customers.store_whitelist");
    Route::get("yukk_co/manage_customer_bulk_search", [\App\Http\Controllers\YukkCo\CustomerController::class, "bulkSearchForm"])->name("yukk_co.customers.bulk_search_form");
    Route::post("yukk_co/manage_customer_bulk_search", [\App\Http\Controllers\YukkCo\CustomerController::class, "bulkSearchPost"])->name("yukk_co.customers.bulk_search_post");

    Route::get("json/bank-accounts/select2", [\App\Http\Controllers\YukkCo\PartnerActivationController::class, "getBankAccountsJsonSelect2"])->name("json.bank-account.select");
    Route::get("json/companies", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getCompanyJson"])->name("json.company");
    Route::get("json/companies/select2", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getCompanyJsonSelect2"])->name("json.company.select");
    Route::get("json/customers", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getCustomerJson"])->name("json.customer");
    Route::get("json/customers/select2", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getCustomerJsonSelect2"])->name("json.customer.select");
    Route::get("json/merchants", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getMerchantJson"])->name("json.merchant");
    Route::get("json/merchants/select2", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getMerchantJsonSelect2"])->name("json.merchant.select");
    Route::get("json/partners/select2", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getPartnerJsonSelect2"])->name("json.partner.select");
    Route::get("json/merchant-branches", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getBranchJson"])->name("json.merchant.branches");
    Route::get("json/merchant-branches/select2", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getBranchJsonSelect2"])->name("json.merchant.branches.select");
    Route::get("json/merchant-branches/item", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "getBranchByIdJson"])->name("json.merchant.branches.item");

    Route::get("yukk_co/qris_pten_menu", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "index"])->name("yukk_co.merchant.pten.list");
    Route::get("yukk_co/qris_pten_menu/submit", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "submit"])->name("yukk_co.merchant.pten.submit");
    Route::post("yukk_co/qris_pten_menu/data", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "data"])->name("yukk_co.merchant.pten.data");
    Route::post("yukk_co/qris_pten_menu/update/status", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "changeStatusPending"])->name("yukk_co.merchant.pten.pending");
    Route::post("yukk_co/qris_pten_menu/update/status/pten", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "changeStatusPten"])->name("yukk_co.merchant.pten.status");
    Route::post("yukk_co/qris_pten_menu/update/status/json", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "changeStatusPendingFromJson"])->name("yukk_co.merchant.pten.pending.json");
    Route::get("yukk_co/qris_pten_menu/download", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "listDownload"])->name("yukk_co.merchant.pten.download.list");
    Route::post("yukk_co/qris_pten_menu/qris_download", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "listQRIS"])->name("yukk_co.merchant.pten.download.qris");
    Route::post("yukk_co/qris_pten_menu/export", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "export"])->name("yukk_co.merchant.pten.export");
    Route::get("yukk_co/qris_pten_menu/delete", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "listDelete"])->name("yukk_co.merchant.pten.delete.list");
    Route::post("yukk_co/qris_pten_menu/delete_pten", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "listDeletePten"])->name("yukk_co.merchant.pten.delete.pten");
    Route::post("yukk_co/qris_pten_menu/delete_import", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "ImportDeletePten"])->name("yukk_co.merchant.pten.delete.import");

    Route::get("yukk_co/qris_pten_menu/bulk/list", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "list"])->name("yukk_co.merchant.pten.bulk.list");
    Route::post("yukk_co/qris_pten_menu/bulk/list/data", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "listData"])->name("yukk_co.merchant.pten.bulk.listData");
    Route::post("yukk_co/qris_pten_menu/bulk/submit", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "bulkSubmit"])->name("yukk_co.merchant.pten.bulk.submit");

    Route::get("yukk_co/qris_pten_menu/{id}", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "show"])->name("yukk_co.merchant.pten.show");
    Route::get("yukk_co/qris_pten_menu/{id}/edit", [\App\Http\Controllers\YukkCo\MerchantBranchPTENController::class, "edit"])->name("yukk_co.merchant.pten.edit");

    //Manage Partner Login
    Route::get("yukk_co/manage_partner_login", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "index"])->name("yukk_co.partner_login.index");
    Route::get("yukk_co/manage_partner_login/add", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "add"])->name("yukk_co.partner_login.add");
    Route::get("yukk_co/manage_partner_login/add/{merchant_id}/{branch_id}", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "addFromManageQRIS"])->name("yukk_co.partner_login.add_from_qris");
    Route::post("yukk_co/manage_partner_login/store", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "store"])->name("yukk_co.partner_login.store");
    Route::get("yukk_co/manage_partner_login/{partner_login_id}/detail", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "detail"])->name("yukk_co.partner_login.detail");
    Route::get("yukk_co/manage_partner_login/{partner_login_id}/edit", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "edit"])->name("yukk_co.partner_login.edit");
    Route::post("yukk_co/manage_partner_login/{partner_login_id}/update_scope", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "updateScope"])->name("yukk_co.partner_login.update_scopes");

    Route::post("yukk_co/partner_login/reset/{id}", [\App\Http\Controllers\YukkCo\PartnerLoginController::class, "reset"])->name("yukk_co.partner_login.reset");

    //Partner Payment Link
    Route::get("merchant_branch/payment_link/list", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "index"])->name("cms.merchant_branches.payment_link.list");
    Route::get("merchant_branch/payment_link/create", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "create"])->name("cms.merchant_branches.payment_link.create");
    Route::get("merchant_branch/payment_link/import", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "import"])->name("cms.merchant_branches.payment_link.import");
    Route::get("merchant_branch/payment_link/import/template", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "downloadTemplate"])->name("cms.merchant_branches.payment_link.import.template");
    Route::get("merchant_branch/payment_link/import/download-bank-code", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "downloadListBankCode"])->name("cms.merchant_branches.payment_link.bank-code");
    Route::post("merchant_branch/payment_link/create", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "create"])->name("cms.merchant_branches.payment_link.submit");
    Route::post("merchant_branch/payment_link/import", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "bulkCreate"])->name("cms.merchant_branches.payment_link.submit.import");
    Route::get("merchant_branch/payment_link/delete/{payment_link_id}", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "delete"])->name("cms.merchant_branches.payment_link.delete");
    Route::get("merchant_branch/payment_link/detail/{payment_link_id}", [\App\Http\Controllers\MerchantBranches\PaymentLinkController::class, "show"])->name("cms.merchant_branches.payment_link.detail");

    //Beneficiary MDR'
    Route::get("yukk_co/beneficiary_mdr", [\App\Http\Controllers\YukkCo\MdrBeneficiaryController::class, "index"])->name("cms.yukk_co.mdr_beneficiary.index");
    Route::get("yukk_co/beneficiary_mdr/detail", [\App\Http\Controllers\YukkCo\MdrBeneficiaryController::class, "detail"])->name("cms.yukk_co.mdr_beneficiary.detail");
    Route::get("yukk_co/beneficiary_mdr/get-partners", [\App\Http\Controllers\YukkCo\MdrBeneficiaryController::class, "getPartners"])->name("cms.yukk_co.mdr_beneficiary.get_partners");
    Route::get("yukk_co/beneficiary_mdr/get-customers", [\App\Http\Controllers\YukkCo\MdrBeneficiaryController::class, "paginatedCustomer"])->name("cms.yukk_co.mdr_beneficiary.get_customers");

    Route::post("partner/partner_webhook_log/resend", [\App\Http\Controllers\Partners\TransactionPaymentController::class, "resendWebhook"])->name("cms.partner.partner_webhook_log.resend");

    //Profile Page
    Route::get("user/profile", [\App\Http\Controllers\Store\UserController::class, "profile"])->name("cms.user.profile");
    Route::post("user/update/profile", [\App\Http\Controllers\Store\UserController::class, "profileUpdate"])->name("cms.user.update");

    Route::post("user/update/password", [\App\Http\Controllers\Store\UserController::class, "updatePassword"])->name("cms.password.update");
    Route::get('/user/detail', function () {
        return view('user.detail');
    });

    // YUKK User Withdrawal
    Route::get("yukk_co/user_withdrawal/list", [\App\Http\Controllers\YukkCo\UserWithdrawalController::class, "index"])->name("cms.yukk_co.user_withdrawal.list");
    Route::get("yukk_co/user_withdrawal/item/{user_withdrawal_id}", [\App\Http\Controllers\YukkCo\UserWithdrawalController::class, "show"])->name("cms.yukk_co.user_withdrawal.item");
    Route::post("yukk_co/user_withdrawal/action/{user_withdrawal_id}", [\App\Http\Controllers\YukkCo\UserWithdrawalController::class, "action"])->name("cms.yukk_co.user_withdrawal.action");

    // YUKK QOIN
    // Order Deposit QOIN
    Route::get("yukk_co/order_deposit_qoin/list", [\App\Http\Controllers\YukkCo\OrderDepositQoinController::class, "index"])->name("cms.yukk_co.order_deposit_qoin.list");
    // QOIN Credit Logs
    Route::middleware('can-access:TRANSACTION_QOIN.CREDIT_LOGS_VIEW')
        ->get("yukk_co/transaction_qoin/credit_logs", [\App\Http\Controllers\YukkCo\QoinCreditLogController::class, "index"])->name('cms.yukk_co.transaction_qoin.credit_logs.index');
    Route::middleware('can-access:TRANSACTION_QOIN.CREDIT_LOGS_CREATE')
        ->post("yukk_co/transaction_qoin/credit_logs/create", [\App\Http\Controllers\YukkCo\QoinCreditLogController::class, "store"])->name('cms.yukk_co.transaction_qoin.credit_logs.store');


    // YUKK Order Deposit
    // Order Deposit
    Route::get("yukk_co/order_deposit/list", [\App\Http\Controllers\YukkCo\OrderDepositController::class, "index"])->name("cms.yukk_co.order_deposit.list");
    // Order Deposit Credit Logs
    Route::middleware('can-access:TRANSACTION_PLATFORM_DEPOSIT.CREDIT_LOGS_VIEW')
        ->get("yukk_co/transaction/credit_logs", [\App\Http\Controllers\YukkCo\OrderDepositCreditLogController::class, "index"])->name('cms.yukk_co.transaction.credit_logs.index');
    Route::middleware('can-access:TRANSACTION_PLATFORM_DEPOSIT.CREDIT_LOGS_CREATE')
        ->post("yukk_co/transaction/credit_logs/create", [\App\Http\Controllers\YukkCo\OrderDepositCreditLogController::class, "store"])->name('cms.yukk_co.transaction.credit_logs.store');

    // MERCHANT BRANCH Order Deposit
    // Order Deposit
    Route::get("merchant_branches/order_deposit/list", [\App\Http\Controllers\MerchantBranches\OrderDepositController::class, "index"])->name("cms.merchant_branch.order_deposit.list");
    // Order Deposit Credit Logs
    Route::middleware('can-access:TRANSACTION_PLATFORM_DEPOSIT.CREDIT_LOGS_VIEW')
        ->get("merchant_branches/transaction/credit_logs", [\App\Http\Controllers\MerchantBranches\OrderDepositCreditLogController::class, "index"])->name('cms.merchant_branch.transaction.credit_logs.index');

    // YUKK Beneficiary Edit
    Route::get("yukk_co/beneficiary_edit_request/list_coo_pending", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "listCooPending"])->name("cms.yukk_co.beneficiary_edit_request.list_coo");
    Route::get("yukk_co/beneficiary_edit_request/list_coo_approved", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "listCooApproved"])->name("cms.yukk_co.beneficiary_edit_request.list_coo_approved");
    Route::get("yukk_co/beneficiary_edit_request/list_coo_rejected", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "listCooRejected"])->name("cms.yukk_co.beneficiary_edit_request.list_coo_rejected");
    Route::get("yukk_co/beneficiary_edit_request/item/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "showCoo"])->name("cms.yukk_co.beneficiary_edit_request.show");
    Route::get("yukk_co/beneficiary_edit_request/edit_coo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "editCoo"])->name("cms.yukk_co.beneficiary_edit_request.edit_coo");
    Route::post("yukk_co/beneficiary_edit_request/edit_coo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "updateCoo"])->name("cms.yukk_co.beneficiary_edit_request.update_coo");
    Route::post("yukk_co/beneficiary_edit_request/approve_coo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "approveCoo"])->name("cms.yukk_co.beneficiary_edit_request.approve_coo");
    Route::post("yukk_co/beneficiary_edit_request/reject_coo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\BeneficiaryEditRequestController::class, "rejectCoo"])->name("cms.yukk_co.beneficiary_edit_request.reject_coo");

    // YUKK DTTOT
    Route::post("yukk_co/dttot/approval/toggle", [\App\Http\Controllers\YukkCo\DTTOTController::class, "toggleStatus"])->name("cms.yukk_co.dttot_approval.toggle");
    Route::get("yukk_co/dttot/approval/list", [\App\Http\Controllers\YukkCo\DTTOTController::class, "approvalList"])->name("cms.yukk_co.dttot_approval.list");
    Route::get("yukk_co/dttot/list", [\App\Http\Controllers\YukkCo\DTTOTController::class, "index"])->name("cms.yukk_co.dttot.list");
    Route::get("yukk_co/dttot/import", [\App\Http\Controllers\YukkCo\DTTOTController::class, "import"])->name("cms.yukk_co.dttot.import");
    Route::post("yukk_co/dttot/import_preview", [\App\Http\Controllers\YukkCo\DTTOTController::class, "importPreview"])->name("cms.yukk_co.dttot.import_preview");
    Route::get("yukk_co/dttot/import_preview", [\App\Http\Controllers\YukkCo\DTTOTController::class, "importPreview"])->name("cms.yukk_co.dttot.import_preview_get");
    Route::get("yukk_co/dttot/download_template", [\App\Http\Controllers\YukkCo\DTTOTController::class, "downloadTemplate"])->name("cms.yukk_co.dttot.download_template");
    Route::get("yukk_co/dttot/edit/{id}", [\App\Http\Controllers\YukkCo\DTTOTController::class, "edit"])->name("cms.yukk_co.dttot.edit");
    Route::put("yukk_co/dttot/update/{id}", [\App\Http\Controllers\YukkCo\DTTOTController::class, "update"])->name("cms.yukk_co.dttot.update");
    Route::get("yukk_co/dttot/approval/{id}", [\App\Http\Controllers\YukkCo\DTTOTController::class, "approvalDetail"])->name("cms.yukk_co.dttot_approval.detail");
    Route::get("yukk_co/dttot/{id}", [\App\Http\Controllers\YukkCo\DTTOTController::class, "detail"])->name("cms.yukk_co.dttot.detail");
    Route::post("yukk_co/dttot/{id}", [\App\Http\Controllers\YukkCo\DTTOTController::class, "delete"])->name("cms.yukk_co.dttot.delete");
    // YUKK Suspected Users
    Route::get("yukk_co/suspected_user/list", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "index"])->name("cms.yukk_co.suspected_user.list");
    Route::get("yukk_co/suspected_user/edit/{id}", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "edit"])->name("cms.yukk_co.suspected_user.edit");
    Route::get("yukk_co/suspected_user/{id}", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "detail"])->name("cms.yukk_co.suspected_user.detail");
    Route::post("yukk_co/suspected_user/{id}", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "update"])->name("cms.yukk_co.suspected_user.update")->whereNumber('id');
    Route::post("yukk_co/suspected_user/approval/toggle", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "toggleStatus"])->name("cms.yukk_co.suspected_user_approval.toggle");
    Route::get("yukk_co/suspected_user/approval/list", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "approvalIndex"])->name("cms.yukk_co.suspected_user_approval.list");
    Route::get("yukk_co/suspected_user/approval/edit/{id}", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "approvalEdit"])->name("cms.yukk_co.suspected_user_approval.edit");
    Route::get("yukk_co/suspected_user/approval/{id}", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "approvalDetail"])->name("cms.yukk_co.suspected_user_approval.detail")->whereNumber('id');
    Route::post("yukk_co/suspected_user/approval", [\App\Http\Controllers\YukkCo\SuspectedUserController::class, "approvalUpdate"])->name("cms.yukk_co.suspected_user_approval.update");

    // CFO YUKK Beneficiary Edit
    Route::get("yukk_co/beneficiary_edit_request/list_cfo_pending", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "listCfoPending"])->name("cms.yukk_co.beneficiary_edit_request.list_cfo");
    Route::get("yukk_co/beneficiary_edit_request/list_cfo_approved", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "listCfoApproved"])->name("cms.yukk_co.beneficiary_edit_request.list_cfo_approved");
    Route::get("yukk_co/beneficiary_edit_request/list_cfo_rejected", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "listCfoRejected"])->name("cms.yukk_co.beneficiary_edit_request.list_cfo_rejected");
    Route::get("yukk_co/beneficiary_edit_request/item_cfo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "showCfo"])->name("cms.yukk_co.beneficiary_edit_request.show_cfo");
    Route::post("yukk_co/beneficiary_edit_request/approve_cfo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "approveCfo"])->name("cms.yukk_co.beneficiary_edit_request.approve_cfo");
    Route::post("yukk_co/beneficiary_edit_request/reject_cfo/{beneficiary_edit_request_id}", [\App\Http\Controllers\YukkCo\CFO\BeneficiaryEditRequestController::class, "rejectCfo"])->name("cms.yukk_co.beneficiary_edit_request.reject_cfo");

    Route::middleware(\App\Http\Middleware\CheckJWTSession::class)->group(function () {
        Route::prefix('store/users')->name('store.users.')->group(function () {
            Route::get('/import', [
                ImportUserController::class,
                'showForm'
            ])->name('import_form')->middleware(
                'api-gateway.can:STORE.USERS_CREATE'
            );

            Route::post('/import', [
                ImportUserController::class,
                'import'
            ])->name('import')->middleware(
                'api-gateway.can:STORE.USERS_CREATE'
            );

            Route::post('/import/preview', [
                ImportUserController::class,
                'preview'
            ])->name('import_preview')->middleware(
                'api-gateway.can:STORE.USERS_CREATE'
            );
        });

        Route::get("store/users", [\App\Http\Controllers\Store\UserController::class, "index"])
            ->middleware('api-gateway.can:STORE.USERS_VIEW')->name('cms.store.users.list');
        Route::post("store/users", [\App\Http\Controllers\Store\UserController::class, "store"])
            ->middleware('api-gateway.can:STORE.USERS_CREATE')->name('cms.store.users.store');
        Route::get("store/users/create", [\App\Http\Controllers\Store\UserController::class, "create"])
            ->middleware('api-gateway.can:STORE.USERS_CREATE')->name('cms.store.users.create');
        Route::get("store/users/{id}", [\App\Http\Controllers\Store\UserController::class, "show"])
            ->middleware('api-gateway.can:STORE.USERS_VIEW')->name('cms.store.users.show')->whereNumber('id');
        Route::get("store/users/{id}/edit", [\App\Http\Controllers\Store\UserController::class, "edit"])
            ->middleware('api-gateway.can:STORE.USERS_EDIT')->name('cms.store.users.edit');
        Route::put("store/users/{id}/update", [\App\Http\Controllers\Store\UserController::class, "update"])
            ->middleware('api-gateway.can:STORE.USERS_EDIT')->name('cms.store.users.update');
        Route::post("store/users/{id}/active/toggle", [\App\Http\Controllers\Store\UserController::class, "toggleActive"])
            ->middleware('api-gateway.can:STORE.USERS_EDIT')->name('cms.store.users.toggle.active');
        Route::get("store/roles", [\App\Http\Controllers\Store\RoleController::class, "index"])
            ->middleware('api-gateweay.can:STORE.ROLES_VIEW')->name('cms.store.roles.list');
        Route::get("store/partners", [\App\Http\Controllers\Store\PartnerController::class, "index"])
            ->middleware('api-gateway.any:STORE.USERS_VIEW,STORE.USERS_CREATE,STORE.USERS_EDIT')->name('cms.store.partners.list');
        Route::get("store/merchant_branches", [\App\Http\Controllers\Store\MerchantBranchController::class, "index"])
            ->middleware('api-gateway.any:STORE.USERS_VIEW,STORE.USERS_CREATE,STORE.USERS_EDIT')->name('cms.store.merchant_branches.list');
        Route::get("store/beneficiaries", [\App\Http\Controllers\Store\BeneficiaryController::class, "index"])
            ->middleware('api-gateway.any:STORE.USERS_VIEW,STORE.USERS_CREATE,STORE.USERS_EDIT')->name('cms.store.beneficiaries.list');
        Route::post("store/users/{id}/reset", [\App\Http\Controllers\Store\UserController::class, "reset"])
            ->middleware('api-gateway.can:STORE.USERS_RESET_PASSWORD')->name('cms.store.users.reset');

        Route::get('partner/credentials', [PaymentGatewayCredentialController::class, 'index']);
        Route::get('partner/credentials/tech-docs', [PaymentGatewayCredentialController::class, 'techDocs']);

        Route::get('partner/balance', [\App\Http\Controllers\Partners\MoneyTransferController::class, "balance"])->name("cms.partner.money_transfer.balance");

        Route::get('partner/mt/transaction_item/list', [\App\Http\Controllers\Partners\MoneyTransferTransactionItemController::class, "index"])->name("cms.partner.money_transfer.transaction_item.list");
        Route::get('partner/mt/transaction_item/item/{transaction_item_id}', [\App\Http\Controllers\Partners\MoneyTransferTransactionItemController::class, "show"])->name("cms.partner.money_transfer.transaction_item.show");

        Route::get('yukk_co/pg/tech-docs', [PaymentGatewayTechDocController::class, 'index'])
            ->middleware('api-gateway.can:PAYMENT_GATEWAY.TECH_DOCS.VIEW');
        Route::post('yukk_co/pg/tech-docs', [PaymentGatewayTechDocController::class, 'store'])
            ->middleware('api-gateway.can:PAYMENT_GATEWAY.TECH_DOCS.CREATE');
        Route::get('yukk_co/pg/tech-docs/download', [PaymentGatewayTechDocController::class, 'show'])
            ->middleware('api-gateway.can:PAYMENT_GATEWAY.TECH_DOCS.VIEW');

        Route::get('/json/cms-api/grouping-access-controls', [
            GroupingAccessControlController::class,
            'index'
        ])->name('json.cms-api.grouping-access-controls')->middleware();

        Route::post('/json/store/users/import', [
            ImportUserController::class,
            'import'
        ])->name('json.store.users.import')->middleware(
            'api-gateway.can:STORE.USERS_CREATE'
        );

        Route::post('/json/store/users/import', [
            ImportUserJsonController::class,
            'import'
        ])->name('json.store.users.import')->middleware(
            'api-gateway.can:STORE.USERS_CREATE'
        );

        //Manage Validate Topup
        Route::get("yukk_co/manage_settings", [
            \App\Http\Controllers\YukkCo\GeneralSettingController::class,
            "index"
        ])->name("yukk_co.general.settings.index")->middleware(
            'api-gateway.any:SETTINGS.VIEW,SETTINGS.UPDATE'
        );
        Route::get("yukk_co/manage_settings/data", [
            \App\Http\Controllers\YukkCo\GeneralSettingController::class,
            "data"
        ])->name("yukk_co.general.settings.data")->middleware(
            'api-gateway.any:SETTINGS.VIEW,SETTINGS.UPDATE'
        );
        Route::get("yukk_co/manage_settings/{id}/edit", [
            \App\Http\Controllers\YukkCo\GeneralSettingController::class,
            "edit"
        ])->name("yukk_co.general.settings.edit")->middleware(
            'api-gateway.can:SETTINGS.UPDATE'
        );
        Route::get("yukk_co/manage_settings/{id}", [
            \App\Http\Controllers\YukkCo\GeneralSettingController::class,
            "show"
        ])->name("yukk_co.general.settings.show")->middleware(
            'api-gateway.any:SETTINGS.VIEW,SETTINGS.UPDATE'
        );
        Route::post("yukk_co/manage_settings/{id}", [
            \App\Http\Controllers\YukkCo\GeneralSettingController::class,
            "update"
        ])->name("yukk_co.general.settings.update")->middleware(
            'api-gateway.can:SETTINGS.UPDATE'
        );

        //Manage Suspect
        Route::get("yukk_co/manage_suspects", [
            \App\Http\Controllers\YukkCo\SuspectController::class,
            "index"
        ])->name("yukk_co.suspects.index")->middleware(
            'api-gateway.any:LAST_TOPUP_VALIDATION.VIEW,LAST_TOPUP_VALIDATION.UPDATE'
        );
        Route::get("yukk_co/manage_suspects/data", [
            \App\Http\Controllers\YukkCo\SuspectController::class,
            "data"
        ])->name("yukk_co.suspects.data")->middleware(
            'api-gateway.any:LAST_TOPUP_VALIDATION.VIEW,LAST_TOPUP_VALIDATION.UPDATE'
        );
        Route::get("yukk_co/manage_suspects/{id}/edit", [
            \App\Http\Controllers\YukkCo\SuspectController::class,
            "edit"
        ])->name("yukk_co.suspects.edit")->middleware(
            'api-gateway.can:LAST_TOPUP_VALIDATION.UPDATE'
        );
        Route::get("yukk_co/manage_suspects/{id}", [
            \App\Http\Controllers\YukkCo\SuspectController::class,
            "show"
        ])->name("yukk_co.suspects.show")->middleware(
            'api-gateway.any:LAST_TOPUP_VALIDATION.VIEW,LAST_TOPUP_VALIDATION.UPDATE'
        );
        Route::post("yukk_co/manage_suspects/{id}", [
            \App\Http\Controllers\YukkCo\SuspectController::class,
            "update"
        ])->name("yukk_co.suspects.update")->middleware(
            'api-gateway.can:LAST_TOPUP_VALIDATION.UPDATE'
        );
    });

    Route::prefix('money_transfer')->group(function () {
        Route::middleware([
            \App\Http\Middleware\CheckJWTSession::class,
            'api-gateway.any:MONEY_TRANSFER.TRANSFER,MONEY_TRANSFER.YUKK_CASH'
        ])
            ->get('redirect', [\App\Http\Controllers\Partners\MoneyTransferController::class, 'redirect'])
            ->name("cms.partner.money_transfer.redirect");

        Route::prefix('provider_deposits')->group(function () {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderDepositController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.TOP_UP_BALANCE_VIEW')->name('money_transfer.provider_deposits.index');
            Route::get('{id}/detail', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderDepositController::class, "detail"])
                ->middleware('can-access:MONEY_TRANSFER.TOP_UP_BALANCE_VIEW')->name('money_transfer.provider_deposits.detail');
            Route::put('{id}/retry', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderDepositController::class, "retry"])
                ->middleware('can-access:MONEY_TRANSFER.TOP_UP_BALANCE_UPDATE')->name('money_transfer.provider_deposits.retry');
            Route::put('{id}/success', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderDepositController::class, "markSuccess"])
                ->middleware('can-access:MONEY_TRANSFER.TOP_UP_BALANCE_UPDATE')->name('money_transfer.provider_deposits.mark_success');
        });

        Route::prefix('transactions')->group(function () {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTIONS_VIEW')->name('money_transfer.transactions.index');
            Route::get('/{id}/detail', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionController::class, "detail"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTIONS_VIEW')->name('money_transfer.transactions.detail');
            Route::post('/{id}/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTIONS_UPDATE')->name('money_transfer.transactions.update');
            Route::get('/export', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionItemExportController::class)
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTIONS_VIEW')->name('money_transfer.transactions.export');
            Route::post('/bulk-update', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionBulkUpdateController::class)
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTIONS_UPDATE')->name('money_transfer.transactions.bulk_update');

            Route::prefix('batches')->group(function() {
                Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionBatchController::class, "index"])
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW')->name('money_transfer.transactions.batches.index');
                Route::get('/{code}/detail', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionBatchController::class, "show"])
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW')->name('money_transfer.transactions.batches.show');
                Route::get('/{code}/detail/export', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionBatchController::class, "export"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW')->name('money_transfer.transactions.batches.export');
            });

            Route::prefix('groups')->group(function() {
                Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionGroupController::class, "index"])
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')->name('money_transfer.transactions.groups.index');
                Route::get('/{code}/detail', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionGroupController::class, "show"])
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')->name('money_transfer.transactions.groups.show');
                Route::get('/{code}/detail/export', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransactionGroupController::class, "export"])
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')->name('money_transfer.transactions.groups.export');
            });

            Route::prefix('items')->group(function() {
                Route::get('/log', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionItemLogsController::class)
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW,MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')
                    ->name('money_transfer.transactions.items.logs');
                Route::get('/{code}', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionItemController::class)
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW,MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')
                    ->name('money_transfer.transactions.items.show');
                Route::get('/{code}/webhooks/send', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionItemWebhookSendController::class)
                    ->middleware('can-access:MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW,MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW')
                    ->name('money_transfer.transactions.items.webhooks.send');
            });
        });

        Route::get('provider_balance_histories', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderBalanceHistoryController::class, "index"])
            ->middleware('can-access:MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW')->name('money_transfer.provider_balance_histories.index');

        Route::prefix('provider_balances')->middleware('can-access:MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_UPDATE')->group(function () {
            Route::post('cashout', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderBalanceHistoryController::class, "cashout"])->name('money_transfer.provider_balances.cashout');
            Route::post('adjustment', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderBalanceHistoryController::class, "adjustment"])->name('money_transfer.provider_balances.adjustment');
        });

        Route::prefix('partners')->group(function () {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_VIEW')->name('money_transfer.partners.index');
            Route::get('{id}/setting', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "edit"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_VIEW')->name('money_transfer.partners.edit');
            Route::post('/store', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "store"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')->name('money_transfer.partners.add');
            // Route::post('/user/store', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "userStore"])
            //     ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')->name('money_transfer.partners.user.store');
            Route::post('/bank/store', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "bankStore"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')->name('money_transfer.partners.bank.store');
            Route::put('/{id}/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')->name('money_transfer.partners.update');
            Route::put('/{id}/bank/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "bankUpdate"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')->name('money_transfer.partners.bank.update');
            // Route::put('/{id}/user/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\PartnerSettingController::class, "userUpdate"])
            //    ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')->name('money_transfer.partners.user.update');
        });

        Route::prefix('entities')->group(function () {
            Route::post('/credentials', [\App\Http\Controllers\YukkCo\MoneyTransfer\CredentialController::class, "store"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')->name('money_transfer.entities.credentials.store');
            Route::post('/public-keys', [\App\Http\Controllers\YukkCo\MoneyTransfer\PublicKeyController::class, "store"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')->name('money_transfer.entities.public-keys.store');
            Route::put('/public-keys/{id}', [\App\Http\Controllers\YukkCo\MoneyTransfer\PublicKeyController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')->name('money_transfer.entities.public-keys.update');
        });

        Route::prefix('providers')->group(function () {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_VIEW')->name('money_transfer.providers.index');
            Route::get('/{id}/setting', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "edit"])
                ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_VIEW')->name('money_transfer.providers.edit');

            // Route::post('/store', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "store"])
            //     ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_CREATE')->name('money_transfer.providers.add');
            Route::post('/bank/store', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "bankStore"])
                ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_CREATE')->name('money_transfer.providers.bank.store');

            Route::put('/{id}/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE')->name('money_transfer.providers.update');
            Route::put('/{id}/bank/update', [\App\Http\Controllers\YukkCo\MoneyTransfer\ProviderController::class, "bankUpdate"])
                ->middleware('can-access:MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE')->name('money_transfer.providers.bank.update');
        });

        Route::prefix('settings')->group(function() {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\SettingController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.SETTINGS_VIEW')
                ->name('money_transfer.settings.index');
            Route::put('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\SettingController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.SETTINGS_UPDATE')
                ->name('money_transfer.settings.update');
        });

        Route::prefix('transfer-proxies')->group(function() {
            Route::get('/', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransferProxyController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
                ->name('money_transfer.transfer_proxies.index');
            Route::get('/export', \App\Http\Controllers\YukkCo\MoneyTransfer\TransferProxyExportController::class)
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
                ->name('money_transfer.transfer_proxies.export');
            Route::post('/bulk-update', \App\Http\Controllers\YukkCo\MoneyTransfer\TransferProxyBulkUpdateController::class)
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
                ->name('money_transfer.transfer_proxies.bulk_update');
            Route::get('/logs', \App\Http\Controllers\YukkCo\MoneyTransfer\TransactionItemLogsController::class)
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
                ->name('money_transfer.transfer_proxies.logs');
            Route::get('/{code}', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransferProxyController::class, "show"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
                ->name('money_transfer.transfer_proxies.show');
            Route::post('/{id}', [\App\Http\Controllers\YukkCo\MoneyTransfer\TransferProxyController::class, "update"])
                ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
                ->name('money_transfer.transfer_proxies.update');
        });

        Route::prefix('json')->group(function () {
            Route::get('/entities', [\App\Http\Controllers\JSON\MoneyTransfer\EntityController::class, "index"])
                ->middleware('can-access:MONEY_TRANSFER.PARTNER_SETTINGS_VIEW')->name('money_transfer.json.entities.index');

            Route::prefix('transfer-proxies')->group(function() {
                Route::get('/status-counter', \App\Http\Controllers\JSON\MoneyTransfer\TransferProxyStatusCounterController::class)
                    ->middleware('can-access:MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
                    ->name('money_transfer.json.transfer_proxies.status_counter');
            });

            Route::prefix('providers')->group(function() {
                Route::get('/', [\App\Http\Controllers\JSON\MoneyTransfer\ProviderController::class, "index"])
                    ->middleware('can-access:MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW')
                    ->name('money_transfer.json.providers.index');
            });

            Route::prefix('transactions')->group(function() {
                Route::get('/summary', \App\Http\Controllers\JSON\MoneyTransfer\TransactionItemSummaryController::class)
                    ->middleware('can-access:MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW')
                    ->name('money_transfer.json.transactions.summary');
            });

            Route::prefix('provider-balance-histories')->group(function() {
                Route::get('/', \App\Http\Controllers\JSON\MoneyTransfer\ProviderBalanceHistorySummaryController::class)
                    ->middleware('can-access:MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW')
                    ->name('money_transfer.json.provider-balance-histories.summary');
            });
        });
    });

    // Settlemet Online
    Route::middleware('can-access:SETTLEMENT.SETTLEMENT_ONLINE')
        ->prefix('yukk-co/transaction-online')
        ->name('yukk-co.transaction-online.')
        ->group(function () {
            Route::get('transactions', [TransactionController::class, 'index']);
            Route::resource('settlements', SettlementController::class)->only('index', 'store');
            Route::resource('transfers', TransferController::class)->only('index', 'store');
            Route::resource('transfer-items', TransferItemsController::class)->only('index');
            Route::post('transfer-items/{id}/retry', RetryTransferItemController::class);
            Route::get('transfer-items/{id}/transactions', TransferItemTransactionController::class);
            Route::post('manual-transfers', ManualTransferController::class);
        });

    Route::prefix('yukk_co/approvals')->group(function () {
        Route::get("companies", [\App\Http\Controllers\YukkCo\Approval\CompanyController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.COMPANIES')->name('approval.companies.index');
        Route::post("companies/action", [\App\Http\Controllers\YukkCo\Approval\CompanyController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.COMPANIES')->name('approval.companies.action');
        Route::get("companies/{id}", [\App\Http\Controllers\YukkCo\Approval\CompanyController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.COMPANIES')->name('approval.companies.show');
        Route::get("companies/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\CompanyController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.COMPANIES')->name('approval.companies.master.show');
        Route::put("companies/{id}", [\App\Http\Controllers\YukkCo\Approval\CompanyController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.COMPANIES')->name('approval.companies.update');

        Route::get("merchants", [\App\Http\Controllers\YukkCo\Approval\MerchantController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANTS')->name('approval.merchants.index');
        Route::get("merchants/{id}", [\App\Http\Controllers\YukkCo\Approval\MerchantController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANTS')->name('approval.merchants.show');
        Route::post("merchants/action", [\App\Http\Controllers\YukkCo\Approval\MerchantController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANTS')->name('approval.merchants.action');
        Route::get("merchants/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\MerchantController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANTS')->name('approval.merchants.master.show');
        Route::put("merchants/{id}", [\App\Http\Controllers\YukkCo\Approval\MerchantController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANTS')->name('approval.merchants.update');

        Route::get("merchant_branches", [\App\Http\Controllers\YukkCo\Approval\MerchantBranchController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANT_BRANCHES')->name('approval.merchant_branches.index');
        Route::post("merchant_branches/action", [\App\Http\Controllers\YukkCo\Approval\MerchantBranchController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANT_BRANCHES')->name('approval.merchant_branches.action');
        Route::get("merchant_branches/{id}", [\App\Http\Controllers\YukkCo\Approval\MerchantBranchController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANT_BRANCHES')->name('approval.merchant_branches.show');
        Route::get("merchant_branches/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\MerchantBranchController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANT_BRANCHES')->name('approval.merchant_branches.master.show');
        Route::put("merchant_branches/{id}", [\App\Http\Controllers\YukkCo\Approval\MerchantBranchController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.MERCHANT_BRANCHES')->name('approval.merchant_branches.update');

        Route::get("owners", [\App\Http\Controllers\YukkCo\Approval\OwnerController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.OWNERS')->name('approval.owners.index');
        Route::post("owners/action", [\App\Http\Controllers\YukkCo\Approval\OwnerController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.OWNERS')->name('approval.owners.action');
        Route::get("owners/{id}", [\App\Http\Controllers\YukkCo\Approval\OwnerController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.OWNERS')->name('approval.owners.show');
        Route::get("owners/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\OwnerController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.OWNERS')->name('approval.owners.master.show');
        Route::put("owners/{id}", [\App\Http\Controllers\YukkCo\Approval\OwnerController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.OWNERS')->name('approval.owners.update');

        Route::get("beneficiaries", [\App\Http\Controllers\YukkCo\Approval\BeneficiaryController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.BENEFICIARIES')->name('approval.beneficiaries.index');
        Route::post("beneficiaries/action", [\App\Http\Controllers\YukkCo\Approval\BeneficiaryController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.BENEFICIARIES')->name('approval.beneficiaries.action');
        Route::get("beneficiaries/{id}", [\App\Http\Controllers\YukkCo\Approval\BeneficiaryController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.BENEFICIARIES')->name('approval.beneficiaries.show');
        Route::get("beneficiaries/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\BeneficiaryController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.BENEFICIARIES')->name('approval.beneficiaries.master.show');
        Route::put("beneficiaries/{id}", [\App\Http\Controllers\YukkCo\Approval\BeneficiaryController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.BENEFICIARIES')->name('approval.beneficiaries.update');

        Route::get("partners", [\App\Http\Controllers\YukkCo\Approval\PartnerController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNERS')->name('approval.partners.index');
        Route::post("partners/action", [\App\Http\Controllers\YukkCo\Approval\PartnerController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNERS')->name('approval.partners.action');
        Route::get("partners/{id}", [\App\Http\Controllers\YukkCo\Approval\PartnerController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNERS')->name('approval.partners.show');
        Route::get("partners/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\PartnerController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNERS')->name('approval.partners.master.show');
        Route::put("partners/{id}", [\App\Http\Controllers\YukkCo\Approval\PartnerController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNERS')->name('approval.partners.update');

        Route::get("partner_fees", [\App\Http\Controllers\YukkCo\Approval\PartnerFeeController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNER_FEES')->name('approval.fees.index');
        Route::post("partner_fees/action", [\App\Http\Controllers\YukkCo\Approval\PartnerFeeController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNER_FEES')->name('approval.fees.action');
        Route::get("partner_fees/{id}", [\App\Http\Controllers\YukkCo\Approval\PartnerFeeController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNER_FEES')->name('approval.fees.show');
        Route::get("partner_fees/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\PartnerFeeController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNER_FEES')->name('approval.fees.master.show');
        Route::put("partner_fees/{id}", [\App\Http\Controllers\YukkCo\Approval\PartnerFeeController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.PARTNER_FEES')->name('approval.fees.update');

        Route::get("events", [\App\Http\Controllers\YukkCo\Approval\EventController::class, 'index'])
            ->middleware('can-access:MANAGE_APPROVAL.EVENTS')->name('approval.events.index');
        Route::post("events/action", [\App\Http\Controllers\YukkCo\Approval\EventController::class, 'toggleStatus'])
            ->middleware('can-access:MANAGE_APPROVAL.EVENTS')->name('approval.events.action');
        Route::get("events/{id}", [\App\Http\Controllers\YukkCo\Approval\EventController::class, 'show'])
            ->middleware('can-access:MANAGE_APPROVAL.EVENTS')->name('approval.events.show');
        Route::get("events/{id}/detail", [\App\Http\Controllers\YukkCo\Approval\EventController::class, 'showMaster'])
            ->middleware('can-access:MANAGE_APPROVAL.EVENTS')->name('approval.events.master.show');
        Route::put("events/{id}", [\App\Http\Controllers\YukkCo\Approval\EventController::class, 'update'])
            ->middleware('can-access:MANAGE_APPROVAL.EVENTS')->name('approval.events.update');
    });

    Route::prefix('yukk_co/activity')->group(function () {
        Route::get('/logs', [RowChangeLogController::class, "index"])
            ->middleware('can-access:ACTIVITY_LOG')->name('activity.log.index');
        Route::get('/{id}/detail', [RowChangeLogController::class, "detail"])
            ->middleware('can-access:ACTIVITY_LOG')->name('activity.log.detail');
    });
    Route::prefix('client_credentials')->group(function () {
        Route::get('/', [\App\Http\Controllers\Clients\ClientController::class, "index"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.VIEW')->name('client_credential.index');
        Route::get('/{id}/edit', [\App\Http\Controllers\Clients\ClientController::class, "edit"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.UPDATE')->name('client_credential.edit');
        Route::get('/{id}/detail', [\App\Http\Controllers\Clients\ClientController::class, "detail"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.VIEW')->name('client_credential.detail');
        Route::get('/create', [\App\Http\Controllers\Clients\ClientController::class, "create"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.CREATE')->name('client_credential.create');
        Route::post('/{id}/update', [\App\Http\Controllers\Clients\ClientController::class, "update"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.UPDATE')->name('client_credential.update');
        Route::post('/store', [\App\Http\Controllers\Clients\ClientController::class, "store"])
            ->middleware('can-access:CLIENT_MANAGEMENT.CLIENTS.CREATE')->name('client_credential.store');

        Route::get('/json/select2/customer', [\App\Http\Controllers\Clients\ClientController::class, "getCustomerJsonSelect2"])
            ->name('credential.customer.select2');
        Route::get('/json/select2/partner', [\App\Http\Controllers\Clients\ClientController::class, "getPartnerJsonSelect2"])
            ->name('credential.partner.select2');
    });

    Route::prefix('yukk_co/transaction_merchant_online')->group(function () {
        Route::get('/', [\App\Http\Controllers\YukkCo\TransactionOnlineController::class, "index"])
            ->middleware('can-access:CORE_API.TRANSACTION_ONLINE.VIEW')->name('transaction_merchant_online.index');
        Route::get('/{id}/detail', [\App\Http\Controllers\YukkCo\TransactionOnlineController::class, "detail"])
            ->middleware('can-access:CORE_API.TRANSACTION_ONLINE.VIEW')->name('transaction_merchant_online.detail');
    });

    Route::prefix('yukk_co/platform_setting')->group(function () {
        Route::get('/', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "index"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.VIEW')->name('platform_setting.index');
        Route::get('/{id}/detail', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "detail"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.VIEW')->name('platform_setting.detail');
        Route::get('/{id}/edit', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "edit"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.UPDATE')->name('platform_setting.edit');
        Route::post('/{id}/update', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "update"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.UPDATE')->name('platform_setting.update');
        Route::get('/create', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "create"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.CREATE')->name('platform_setting.create');
        Route::post('/store', [\App\Http\Controllers\YukkCo\ServiceSettingController::class, "store"])
            ->middleware('can-access:CORE_API.PLATFORM_SETTINGS.CREATE')->name('platform_setting.store');
    });

    Route::get('yukk_co/legal_approval/companies',[CompanyController::class, "index"])->name('legal_approval.companies.index');
    Route::post('yukk_co/legal_approval/companies/action',[CompanyController::class, "action"])->name('legal_approval.companies.action');
    Route::post('yukk_co/legal_approval/companies/bulk_action',[CompanyController::class, "bulkAction"])->name('legal_approval.companies.bulk_action');
    Route::get('yukk_co/legal_approval/{id}/detail',[CompanyController::class, "detail"])->name('legal_approval.companies.detail');
});

require_once(__DIR__ . '/core.php');
require_once(__DIR__ . '/merchant_acquisition.php');
require_once(__DIR__ . '/store_management.php');
