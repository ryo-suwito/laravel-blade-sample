<?php

/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Jul-21
 * Time: 16:42
 */

namespace App\Helpers;

use App\Http\Resources\ApiErrorResource;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiHelper
{

    const END_POINT_STORE_AUTH_LOGIN = "store/auth/login";
    const END_POINT_STORE_MY_PROFILE = "store/my-profile";
    const END_POINT_STORE_CHANGE_PRIMARY_USER_ROLE = "store/change-primary-user-role";
    const END_POINT_STORE_USERS_LIST = "store/users";
    const END_POINT_STORE_ROLES_LIST = "store/roles";

    const END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_MERCHANT_BRANCH = "order/transaction_payment/list/merchant_branch";
    const END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_MERCHANT_BRANCH = "order/transaction_payment/item/merchant_branch";

    const END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_PARTNER = "order/transaction_payment/list/partner";
    const END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_PARTNER = "order/transaction_payment/item/partner";

    const END_POINT_ORDER_MT_TRANSACTION_ITEM_LIST_PARTNER = "order/money_transfer/transaction_item/list/partner";
    const END_POINT_ORDER_MT_TRANSACTION_ITEM_ITEM_PARTNER = "order/money_transfer/transaction_item/item/partner";

    const END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_CUSTOMER = "order/transaction_payment/list/customer";
    const END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_CUSTOMER = "order/transaction_payment/item/customer";

    const END_POINT_ORDER_TRANSACTION_PG_LIST_CUSTOMER = "order/transaction_pg/list/customer";
    const END_POINT_ORDER_TRANSACTION_PG_ITEM_CUSTOMER = "order/transaction_pg/item/customer";

    const END_POINT_STORE_FORGOT_PASSWORD_REQUEST_TOKEN = "store/auth/forgot-password/request-token";
    const END_POINT_STORE_FORGOT_PASSWORD_CHECK_TOKEN = "store/auth/forgot-password/check-token";
    const END_POINT_STORE_FORGOT_PASSWORD_CHANGE_PASSWORD = "store/auth/forgot-password/change-password";

    const END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_YUKK_CO = "order/transaction_payment/list/yukk_co";
    const END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_YUKK_CO = "order/transaction_payment/item/yukk_co";

    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_LIST_YUKK_CO = "disbursement/settlement_master/list/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_YUKK_CO = "disbursement/settlement_master/item/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_SWITCHING_YUKK_CO = "disbursement/settlement_master/switching/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ACTION_YUKK_CO = "disbursement/settlement_master/action/yukk_co";

    const END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_LIST_YUKK_CO = "disbursement/settlement_transfer/list/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_ITEM_YUKK_CO = "disbursement/settlement_transfer/item/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_ACTION_YUKK_CO = "disbursement/settlement_transfer/action/yukk_co";

    // JALIN LOG
    const END_POINT_JALIN_LOG_LIST_INBOUND_YUKK_CO = "jalin/log/list_inbound/yukk_co";
    const END_POINT_JALIN_LOG_ITEM_INBOUND_YUKK_CO = "jalin/log/item_inbound/yukk_co";
    const END_POINT_JALIN_LOG_LIST_OUTBOUND_YUKK_CO = "jalin/log/list_outbound/yukk_co";
    const END_POINT_JALIN_LOG_ITEM_OUTBOUND_YUKK_CO = "jalin/log/item_outbound/yukk_co";

    const END_POINT_JALIN_QRIS_RE_HIT_LIST_YUKK_CO = "jalin/qris_re_hit/list/yukk_co";
    const END_POINT_JALIN_QRIS_RE_HIT_ITEM_YUKK_CO = "jalin/qris_re_hit/item/yukk_co";
    const END_POINT_JALIN_QRIS_RE_HIT_STORE_YUKK_CO = "jalin/qris_re_hit/store/yukk_co";
    const END_POINT_JALIN_QRIS_RE_HIT_APPROVE_YUKK_CO = "jalin/qris_re_hit/approve/yukk_co";
    const END_POINT_JALIN_QRIS_RE_HIT_REJECT_YUKK_CO = "jalin/qris_re_hit/reject/yukk_co";

    const END_POINT_JALIN_PARTNER_ORDER_ITEM_YUKK_CO = "jalin/partner_order/item/yukk_co";
    const END_POINT_JALIN_PARTNER_ORDER_EDIT_YUKK_CO = "jalin/partner_order/edit/yukk_co";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_YUKK_CO = "disbursement/disbursement_customer/list/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_YUKK_CO = "disbursement/disbursement_customer/item/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_RESEND_EMAIL_YUKK_CO = "disbursement/disbursement_customer/resend_email/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_ACTION_YUKK_CO = "disbursement/disbursement_customer_transfer/action/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_FLIP_LIST_YUKK_CO = "disbursement/disbursement_customer_flip/list/yukk_co";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_LIST_YUKK_CO = "disbursement/disbursement_customer_transfer_bulk/list/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_ITEM_YUKK_CO = "disbursement/disbursement_customer_transfer_bulk/item/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_ACTION_YUKK_CO = "disbursement/disbursement_customer_transfer_bulk/action/yukk_co";

    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_LIST_YUKK_CO = "disbursement/settlement_debt/list/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_ITEM_YUKK_CO = "disbursement/settlement_debt/item/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_GET_FROM_DISPUTE_YUKK_CO = "disbursement/settlement_debt/get_from_dispute/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_SUBMIT_FROM_DISPUTE_YUKK_CO = "disbursement/settlement_debt/submit_from_dispute/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_GET_SHARING_PROFIT_YUKK_CO = "disbursement/settlement_debt/get_sharing_profit/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_SUBMIT_SHARING_PROFIT_YUKK_CO = "disbursement/settlement_debt/submit_sharing_profit/yukk_co";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_LIST_YUKK_CO = "disbursement/disbursement_partner/list/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_ITEM_YUKK_CO = "disbursement/disbursement_partner/item/yukk_co";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_ACTION_YUKK_CO = "disbursement/disbursement_partner/action/yukk_co";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_CUSTOMER = "disbursement/disbursement_customer/list/customer";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_CUSTOMER = "disbursement/disbursement_customer/item/customer";
    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_CUSTOMER = "disbursement/settlement_master/item/customer";
    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_MASTER_ITEM_CUSTOMER = "disbursement/settlement_pg_master/item/customer";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_LIST_PARTNER = "disbursement/disbursement_partner/list/partner";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_ITEM_PARTNER = "disbursement/disbursement_partner/item/partner";
    const END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_PARTNER = "disbursement/settlement_master/item/partner";

    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_PARTNER = "disbursement/disbursement_customer/list/partner";
    const END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_PARTNER = "disbursement/disbursement_customer/item/partner";

    const END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_LIST_YUKK_CO = "disbursement/skip_process_day/list/yukk_co";
    const END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_ITEM_YUKK_CO = "disbursement/skip_process_day/item/yukk_co";
    const END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_CREATE_YUKK_CO = "disbursement/skip_process_day/create/yukk_co";
    const END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_EDIT_YUKK_CO = "disbursement/skip_process_day/edit/yukk_co";
    const END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_DELETE_YUKK_CO = "disbursement/skip_process_day/delete/yukk_co";

    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_LIST_YUKK_CO = "disbursement/settlement_pg_calendar/list/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_ITEM_YUKK_CO = "disbursement/settlement_pg_calendar/item/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_CREATE_YUKK_CO = "disbursement/settlement_pg_calendar/create/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_EDIT_YUKK_CO = "disbursement/settlement_pg_calendar/edit/yukk_co";
    const END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_DELETE_YUKK_CO = "disbursement/settlement_pg_calendar/delete/yukk_co";

    const END_POINT_DISBURSEMENT_VIEW_YUKK_CO = "beneficiary/customer/item/customer";
    const END_POINT_DISBURSEMENT_LIST_BANK_CUSTOMER = "beneficiary/bank/list/customer";
    const END_POINT_DISBURSEMENT_CREATE_REQUEST_CUSTOMER = "beneficiary/beneficiary_edit_request/create/customer";

    const END_POINT_DISBURSEMENT_CREATE_OTP_CUSTOMER = "beneficiary/beneficiary_edit_otp/request/customer";

    const END_POINT_DISBURSEMENT_SETTLEMENT_SUMMARY_LIST_CUSTOMER = "disbursement/settlement_summary/list/customer";

    // YUKK PG
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO = "pg-service/payment_channel/list/yukk_co";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO = "pg-service/payment_channel/item/yukk_co";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_EDIT_YUKK_CO = "pg-service/payment_channel/edit/yukk_co";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_EDIT_STATUS_YUKK_CO = "pg-service/payment_channel/edit_status/yukk_co";

    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_LIST_YUKK_CO = "pg-service/payment_channel_category/list/yukk_co";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_ITEM_YUKK_CO = "pg-service/payment_channel_category/item/yukk_co";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_EDIT_YUKK_CO = "pg-service/payment_channel_category/edit/yukk_co";

    const END_POINT_PG_SERVICE_MERCHANT_BRANCH_LIST_YUKK_CO = "pg-service/merchant_branch/list/yukk_co";
    const END_POINT_PG_SERVICE_MERCHANT_BRANCH_ITEM_YUKK_CO = "pg-service/merchant_branch/item/yukk_co";
    const END_POINT_PG_SERVICE_MERCHANT_BRANCH_EDIT_YUKK_CO = "pg-service/merchant_branch/edit/yukk_co";

    const END_POINT_ORDER_TRANSACTION_PG_LIST_YUKK_CO = "order/transaction_pg/list/yukk_co";
    const END_POINT_ORDER_TRANSACTION_PG_ITEM_YUKK_CO = "order/transaction_pg/item/yukk_co";

    const END_POINT_SPLIT_DISBURSEMENT_YUKK_CO = "disbursement/disbursement_split/setting/yukk_co";
    const END_POINT_EDIT_SPLIT_DISBURSEMENT_YUKK_CO = "disbursement/disbursement_split/edit/yukk_co";

    const END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_LIST_YUKK_CO = "disbursement/settlement_pg_master/list/yukk_co";
    const END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_ITEM_YUKK_CO = "disbursement/settlement_pg_master/item/yukk_co";
    const END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_LIST_SOURCE_OF_FUND_YUKK_CO = "disbursement/settlement_pg_master/list_source_of_fund/yukk_co";

    const END_POINT_PG_SERVICE_PARTNER_MDR_LIST_YUKK_CO = "pg-service/partner_mdr/list/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_MDR_ITEM_YUKK_CO = "pg-service/partner_mdr/item/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_MDR_EDIT_YUKK_CO = "pg-service/partner_mdr/edit/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_MDR_GET_DATA_FOR_INSERT_YUKK_CO = "pg-service/partner_mdr/data_for_insert/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_MDR_CREATE_YUKK_CO = "pg-service/partner_mdr/create/yukk_co";

    const END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO = "pg-service/provider/list/yukk_co";
    const END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO = "pg-service/provider/item/yukk_co";
    const END_POINT_PG_SERVICE_PROVIDER_EDIT_YUKK_CO = "pg-service/provider/edit/yukk_co";

    const END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_ITEM_YUKK_CO = "pg-service/provider_has_payment_channel/item/yukk_co";
    const END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_CREATE_YUKK_CO = "pg-service/provider_has_payment_channel/create/yukk_co";
    const END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_EDIT_YUKK_CO = "pg-service/provider_has_payment_channel/edit/yukk_co";

    const END_POINT_PG_SERVICE_PARTNER_LIST_YUKK_CO = "pg-service/partner/list/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_ITEM_YUKK_CO = "pg-service/partner/item/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_EDIT_YUKK_CO = "pg-service/partner/edit/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_GENERATE_CLIENT_ID_SECRET_YUKK_CO = "pg-service/partner/generate_client_id_secret/yukk_co";

    const END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_LIST_YUKK_CO = "pg-service/partner_has_merchant_branch/list/yukk_co";

    const END_POINT_ORDER_TRANSACTION_PG_LIST_MERCHANT_BRANCH = "order/transaction_pg/list/merchant_branch";
    const END_POINT_ORDER_TRANSACTION_PG_ITEM_MERCHANT_BRANCH = "order/transaction_pg/item/merchant_branch";

    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_MERCHANT_BRANCH = "pg-service/payment_channel/list/merchant_branch";
    const END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_PARTNER = "pg-service/payment_channel/list/partner";

    const END_POINT_ORDER_TRANSACTION_PG_LIST_PARTNER = "order/transaction_pg/list/partner";
    const END_POINT_ORDER_TRANSACTION_PG_ITEM_PARTNER = "order/transaction_pg/item/partner";

    const END_POINT_PG_SERVICE_MERCHANT_BRANCH_LIST_NOT_IN_PARTNER_HAS_MERCHANT_BRANCH_YUKK_CO = "pg-service/merchant_branch/list_not_in_partner_has_merchant_branches/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_CREATE_YUKK_CO = "pg-service/partner_has_merchant_branch/create/yukk_co";

    const END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_PURE_LIST_YUKK_CO = "pg-service/partner_has_merchant_branch/pure_list/yukk_co";
    const END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_DELETE_YUKK_CO = "pg-service/partner_has_merchant_branch/delete/yukk_co";

    const END_POINT_INBOUND_GET_LIST_TABLE = "inbound/request-production/list";
    const END_POINT_INBOUND_SHOW_REQUEST = "inbound/request-production/show";
    const END_POINT_INBOUND_UPDATE = "inbound/request-production/update";
    const END_POINT_INBOUND_CHANGE_STATUS = "inbound/request-production/change-status";

    const END_POINT_INBOUND_GET_CITY_LIST = "inbound/city/list-json";
    const END_POINT_INBOUND_GET_REGION_LIST = "inbound/region/list-json";

    // YUKK User Withdrawal Service
    const END_POINT_USER_WITHDRAWAL_LIST_YUKK_CO = "withdrawal-service/user_withdrawal/list/yukk_co";
    const END_POINT_USER_WITHDRAWAL_ITEM_YUKK_CO = "withdrawal-service/user_withdrawal/item/yukk_co";
    const END_POINT_USER_WITHDRAWAL_ACTION_YUKK_CO = "withdrawal-service/user_withdrawal/action/yukk_co";


    // QRIS Webhook
    const END_POINT_ORDER_PARTNER_WEBHOOK_LOG_LIST_PARTNER = "order/partner_webhook_log/list/partner";
    const END_POINT_ORDER_PARTNER_WEBHOOK_LOG_ITEM_PARTNER = "order/partner_webhook_log/item/partner";
    const END_POINT_ORDER_PARTNER_WEBHOOK_LOG_RESEND_PARTNER = "order/partner_webhook_log/resend/partner";
    const END_POINT_ORDER_PARTNER_WEBHOOK_LOG_LIST_YUKK_CO = "order/partner_webhook_log/list/yukk_co";


    // YUKK QOIN Service
    const END_POINT_QOIN_ORDER_DEPOSIT_LIST_YUKK_CO = "transaction-qoin/order_deposit/list/yukk_co";
    const END_POINT_QOIN_CREDIT_LOG_LIST_YUKK_CO = "transaction-qoin/credit-logs";
    const END_POINT_QOIN_CREDIT_LOG_CREATE_YUKK_CO = "transaction-qoin/credit-logs/create";


    // YUKK Order Deposit Service
    const END_POINT_ORDER_DEPOSIT_LIST_YUKK_CO = "transaction-platform-deposit/order_deposit/list/yukk_co";
    const END_POINT_CREDIT_LOG_LIST_YUKK_CO = "transaction-platform-deposit/credit-logs";
    const END_POINT_CREDIT_LOG_CREATE_YUKK_CO = "transaction-platform-deposit/credit-logs/create";
    const END_POINT_ORDER_DEPOSIT_PLATFORM_LIST = "transaction-platform-deposit/platforms/list";
    const END_POINT_LAST_CREDIT_GROUP_BY_PLATFORM_YUKK_CO = "transaction-platform-deposit/last_credit_group_by_platform/yukk_co";

    // Merchant Branch Order Deposit Service
    const END_POINT_ORDER_DEPOSIT_LIST_MERCHANT_BRANCH = "merchant_branch_deposit/order_deposit/list";
    const END_POINT_CREDIT_LOG_LIST_MERCHANT_BRANCH = "merchant_branch_deposit/credit-logs";
    const END_POINT_ORDER_DEPOSIT_PLATFORM_LIST_MERCHANT_BRANCH = "merchant_branch_deposit/platforms/list";

    // YUKK Beneficiary Service
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_LIST_YUKK_CO = "beneficiary/beneficiary_edit_request/list/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_ITEM_YUKK_CO = "beneficiary/beneficiary_edit_request/item/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_EDIT_COO_YUKK_CO = "beneficiary/beneficiary_edit_request/edit_coo/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_APPROVE_COO_YUKK_CO = "beneficiary/beneficiary_edit_request/approve_coo/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_REJECT_COO_YUKK_CO = "beneficiary/beneficiary_edit_request/reject_coo/yukk_co";
    const END_POINT_BENEFICIARY_LIST_COSTUMER = "beneficiary-mdr/list/customer";
    const END_POINT_BENEFICIARY_LIST_PARTNER = "beneficiary-mdr/list/partner";
    const END_POINT_BENEFICIARY_TRANSACTION_DETAIL = "beneficiary-mdr/transaction-detail/customer";

    // Merchant Acquisition
    const END_POINT_MERCHANT_ACQUISITION_COMPANY_GET_OPTION = "merchant-acquisition/options/companies";
    const END_POINT_MERCHANT_ACQUISITION_MERCHANT_GET_OPTION = "merchant-acquisition/options/merchants";
    const END_POINT_MERCHANT_ACQUISITION_MERCHANT_BRANCH_GET_OPTION = "merchant-acquisition/options/merchant_branches";
    const END_POINT_MERCHANT_ACQUISITION_CUSTOMER_GET_OPTION = "merchant-acquisition/options/customers";
    const END_POINT_MERCHANT_ACQUISITION_CUSTOMER_SEARCH_BULK_BY_NAME = "merchant-acquisition/customer/search_bulk_by_name";
    const END_POINT_COMPANY_COMPANY_GET_LIST = "merchant-acquisition/company/list";
    const END_POINT_COMPANY_COMPANY_ITEM = "merchant-acquisition/company/item";
    const END_POINT_COMPANY_COMPANY_STORE_NAME = "merchant-acquisition/company/store";
    const END_POINT_COMPANY_ADD_YUKK_CO = "merchant-acquisition/company/add";
    const END_POINT_COMPANY_DESTROY_YUKK_CO = "merchant-acquisition/company/destroy/yukk_co";
    const END_POINT_COMPANY_COMPANY_CONTRACT_ADD_YUKK_CO = "merchant-acquisition/company_contract/add/yukk_co";
    const END_POINT_COMPANY_COMPANY_CONTRACT_DELETE_YUKK_CO = "merchant-acquistion/company_contract/delete/yukk_co";

    const END_POINT_MERCHANT_GET_LIST_YUKK_CO = "merchant-acquisition/merchant/list";
    const END_POINT_MERCHANT_DETAIL_YUKK_CO = "merchant-acquisition/merchant/item";
    const END_POINT_MERCHANT_EDIT_YUKK_CO = "merchant-acquisition/merchant/edit";
    const END_POINT_MERCHANT_ADD_YUKK_CO = "merchant-acquisition/merchant/add";
    const END_POINT_MERCHANT_STORE_YUKK_CO = "merchant-acquisition/merchant/store";
    const END_POINT_MERCHANT_DESTROY_YUKK_CO = "merchant-acquisition/merchant/destroy";

    const END_POINT_MERCHANT_BRANCH_GET_LIST_YUKK_CO = "merchant-acquisition/merchant_branch/list";
    const END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO = "merchant-acquisition/merchant_branch/item";
    const END_POINT_MERCHANT_BRANCH_UPDATE_YUKK_CO = "merchant-acquisition/merchant_branch/edit";
    const END_POINT_MERCHANT_BRANCH_STORE_YUKK_CO = "merchant-acquisition/merchant_branch/store";
    const END_POINT_MERCHANT_BRANCH_INACTIVATE_YUKK_CO = "merchant-acquisition/merchant_branch/inactivate";

    const END_POINT_MERCHANT_BRANCH_GET_BULK_LIST_YUKK_CO = "merchant-acquisition/merchant_branch/bulk/list";
    const END_POINT_MERCHANT_BRANCH_STORE_BULK_LIST_YUKK_CO = "merchant-acquisition/merchant_branch/bulk/store/list";

    const END_POINT_PROVINCE_LIST_YUKK_CO = "merchant-acquisition/province/list_json";
    const END_POINT_CITY_LIST_YUKK_CO = "merchant-acquisition/city/list_json";
    const END_POINT_REGION_LIST_YUKK_CO = "merchant-acquisition/region/list_json";
    const END_POINT_BANK_GET_LIST = "merchant-acquisition/bank";
    const END_POINT_CITY_GET_LIST = "merchant-acquisition/city";
    const END_POINT_CUSTOMER_GET_LIST = "merchant-acquisition/customer";
    const END_POINT_CUSTOMER_ITEM = "merchant-acquisition/customer/item";
    const END_POINT_CUSTOMER_STORE = "merchant-acquisition/customer/store";
    const END_POINT_CUSTOMER_UPDATE = "merchant-acquisition/customer/update";
    const END_POINT_OWNER_GET_LIST = "merchant-acquisition/owner";
    const END_POINT_OWNER_ITEM = "merchant-acquisition/owner/item";
    const END_POINT_OWNER_STORE = "merchant-acquisition/owner/store";
    const END_POINT_OWNER_SCAN_KTP = "merchant-acquisition/owner/scan/ktp";
    const END_POINT_OWNER_TOGGLE_STATUS = "merchant-acquisition/owner/toggle/active";
    const END_POINT_CUSTOMER_CHECK_KTP = "merchant-acquisition/customer/check_ktp";
    const END_POINT_MERCHANT_BY_COMPANY_GET = "merchant-acquisition/merchant/list";
    const END_POINT_MERCHANT_BRANCH_BY_MERCHANT_GET = "merchant-acquisition/merchant_branch/get-by-merchant";
    const END_POINT_MERCHANT_BRANCH_BY_PARTNER_LOGIN_GET = "merchant-acquisition/merchant_branch/get-by-partner-login";
    const END_POINT_MERCHANT_BRANCH_PTEN_UPDATE_STATUS_PENDING = "merchant-acquisition/merchant/pten/update/status";
    const END_POINT_MERCHANT_BRANCH_PTEN_GET_LIST = "merchant-acquisition/merchant/pten/list";
    const END_POINT_PTEN_MERCHANT_BRANCH_GET_LIST = "merchant-acquisition/merchant_branch/pten/list";
    const END_POINT_PTEN_MERCHANT_BRANCH_BULK_SUBMIT = "merchant-acquisition/merchant_branch/pten/store";
    const END_POINT_DOWNLOAD_QRIS_MERCHANT_BRANCH = "merchant-acquisition/merchant_branch/pten/download_qris";

    // Manage Partner
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_LIST_YUKK_CO = "merchant-acquisition/partner/list";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_EDIT_YUKK_CO = "merchant-acquisition/partner/edit";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_UPDATE_YUKK_CO = "merchant-acquisition/partner/update";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_STORE_YUKK_CO = "merchant-acquisition/partner/store";

    //Manage Partner Fee
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_LIST_YUKK_CO = "merchant-acquisition/partner_fee/list";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_EDIT_YUKK_CO = "merchant-acquisition/partner_fee/edit";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_UPDATE_YUKK_CO = "merchant-acquisition/partner_fee/update";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_STORE_YUKK_CO = "merchant-acquisition/partner_fee/store";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_TIME_THRESHOLD_YUKK_CO = "merchant-acquisition/partner_fee/time_threshold";

    //Manage Event
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_LIST_YUKK_CO = "merchant-acquisition/event/list";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_EDIT_YUKK_CO = "merchant-acquisition/event/edit";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_UPDATE_YUKK_CO = "merchant-acquisition/event/update";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_STORE_YUKK_CO = "merchant-acquisition/event/store";

    //Manage EDC
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_LIST_YUKK_CO = "merchant-acquisition/edc/list";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_DETAIL_YUKK_CO = "merchant-acquisition/edc/detail";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_UPDATE_DETAIL_YUKK_CO = "merchant-acquisition/edc/update";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_CREATE_YUKK_CO = "merchant-acquisition/edc/store";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_CREATE_DYNAMIC_YUKK_CO = "merchant-acquisiton/edc/create/dynamic";

    //Manage QRIS List
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_LIST_YUKK_CO = "merchant-acquisition/manage-qris/list";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_EDIT_YUKK_CO = "merchant-acquisition/manage-qris/edit";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_DETAIL_YUKK_CO = "merchant-acquisition/manage-qris/detail";
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_GET_PARTNER_FEE_JSON_YUKK_CO = "merchant-acquisition/partner_fee/json";

    const END_POINT_MERCHANT_ACTIVATION_SERVICE_UPDATE_QRIS_SETTING_YUKK_CO = "merchant-acquisition/qris-setting/update";

    //Manage Partner Login
    const END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_LIST = "merchant-acquisition/partner-login/index";
    const END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_DETAIL = "merchant-acquisition/partner-login/detail";
    const END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_STORE = "merchant-acquisition/partner-login/store";
    const END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_UPDATE_SCOPE = "merchant-acquisition/partner-login/update-scope";


    //Manage Reset Password
    const END_POINT_MERCHANT_ACTIVATION_SERVICE_EDIT_PARTNER_LOGIN_BY_YUKK_CO = "merchant-acquisition/partner-login/edit";
    const END_POINT_MERCHANT_ACTIVATION_RESET_PASSWORD = "merchant-acquisition/partner-login/reset";

    //Send Email
    const END_POINT_MERCHANT_ACTIVATION_SEND_EMAIL = "merchant-acquisition/mail/send";

    //Bulk Approval/Reject in Approval
    const END_POINT_MENU_APPROVAL_ACTION_YUKK_CO = "merchant-acquisition/menu-approval/bulk-action";

    // CFO YUKK Beneficiary Service
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_LIST_CFO_YUKK_CO = "beneficiary/beneficiary_edit_request/list_cfo/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_APPROVE_CFO_YUKK_CO = "beneficiary/beneficiary_edit_request/approve_cfo/yukk_co";
    const END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_REJECT_CFO_YUKK_CO = "beneficiary/beneficiary_edit_request/reject_cfo/yukk_co";

    // CFO YUKK Beneficiary Service
    const END_POINT_MERCHANT_BRANCH_LIST_PAYMENT_LINK = "p/pg-payment-links";
    const END_POINT_MERCHANT_BRANCH_CREATE_PAYMENT_LINK = "p/pg-payment-links";
    const END_POINT_MERCHANT_BRANCH_BULK_CREATE_PAYMENT_LINK = "p/pg-payment-links/bulk";
    const END_POINT_MERCHANT_BRANCH_DETAIL_PAYMENT_LINK = "p/pg-payment-links/";
    const END_POINT_MERCHANT_BRANCH_CHECK = "p/pg-payment-links/merchant-branches/";
    const END_POINT_MERCHANT_BRANCH_PAYMENT_CHANNEL_PAYMENT_LINK = "p/pg-payment-links/payment-channels";

    // YUKK PG Invoice Service
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_LIST_YUKK_CO = "pg_invoice/customer_invoice_master/list/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_ITEM_YUKK_CO = "pg_invoice/customer_invoice_master/item/yukk-co";
    const END_POINT_PG_INVOICE_SEARCH_CUSTOMER_PARTNER = "pg_invoice/search_customer_partner/yukk-co";
    const END_POINT_PG_INVOICE_GET_PREVIEW_DATA = "pg_invoice/get_preview_data/yukk-co";
    const END_POINT_PG_INVOICE_GET_PREVIEW_TRANSACTION_LIST = "pg_invoice/get_preview_transaction_list/yukk-co";
    const END_POINT_PG_INVOICE_CREATE_CUSTOMER_INVOICE = "pg_invoice/create_customer_invoice/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_LIST_YUKK_CO = "pg_invoice/customer/list/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_LIST_YUKK_CO = "pg_invoice/partner/list/yukk-co";
    const END_POINT_PG_INVOICE_PROVIDER_LIST_YUKK_CO = "pg_invoice/provider/list/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_DETAIL_LIST_YUKK_CO = "pg_invoice/customer_invoice_detail/list/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_DELETE_YUKK_CO = "pg_invoice/customer_invoice_master/delete/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_POST_YUKK_CO = "pg_invoice/customer_invoice_master/post/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_PAY_YUKK_CO = "pg_invoice/customer_invoice_master/pay/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_LIST_YUKK_CO = "pg_invoice/customer_invoice_provider/list/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_DETAIL_YUKK_CO = "pg_invoice/customer_invoice_provider/detail/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_DOWNLOAD_TRANSACTION_LIST_YUKK_CO = "pg_invoice/customer_invoice_provider/download_transaction_list/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_REVERT_STATUS_EMAIL_YUKK_CO = "pg_invoice/customer_invoice_master/revert_status_email/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_CHANGE_CUSTOMER_EMAIL_YUKK_CO = "pg_invoice/customer_invoice_master/change_customer_email/yukk-co";
    const END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_TRIGGER_SEND_CUSTOMER_EMAIL_YUKK_CO = "pg_invoice/customer_invoice_master/trigger_send_customer_email/yukk-co";

    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_LIST_YUKK_CO = "pg_invoice/partner_payout_master/list/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_ITEM_YUKK_CO = "pg_invoice/partner_payout_master/item/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_PREVIEW_LIST_YUKK_CO = "pg_invoice/partner_payout_master/preview_list/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_PREVIEW_DETAIL_YUKK_CO = "pg_invoice/partner_payout_master/preview_detail/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_CREATE_YUKK_CO = "pg_invoice/partner_payout_master/create/yukk-co";
    const END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_MARK_AS_PAID_YUKK_CO = "pg_invoice/partner_payout_master/mark_as_paid/yukk-co";

    const END_POINT_CORE_V2_PARTNER_WEBHOOK_RESEND_DYNAMIC = "core-api/partner_webhook/resend_dynamic";
    const END_POINT_CORE_V2_PARTNER_WEBHOOK_RESEND_STATIC  = "core-api/partner_webhook/resend_static";

    // Money Transfer Flip Log
    const END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_DEPOSITS = "money-transfer/provider-deposits";
    const END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_STATUS_COUNTER = "money-transfer/provider-deposits/status-counter";
    const END_POINT_MONEY_TRANSFER_TRANSACTION_GET_TRANSACTIONS = "money-transfer/transactions";
    const END_POINT_MONEY_TRANSFER_TRANSACTION_GET_STATUS_COUNTER = "money-transfer/transactions/status-counter";
    const END_POINT_MONEY_TRANSFER_TRANSACTION_GET_SUMMARY_DISBURSEMENT = "money-transfer/transactions/summary-disbursement";
    const END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDERS = "money-transfer/providers";
    const END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDER_CONFIG = "money-transfer/providers/config";
    const END_POINT_MONEY_TRANSFER_PROVIDER_PAYMENT_CHANNELS_STORE = "money-transfer/providers/payment-channels/store";
    const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCE_HISTORIES_GET_HISTORIES = "money-transfer/provider-balance-histories";
    const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_GET_SUMMARY = "money-transfer/provider-balances/summary";
    const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_GET_DETAIL_SUMMARY = "money-transfer/provider-balances/summary-detail";
    const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_CASHOUT = "money-transfer/provider-balances/adjustments/cashout";
    const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_ADJUSTMENT = "money-transfer/provider-balances/adjustments/adjustment";
    // const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_BALANCE = "money-transfer/provider-balances/adjustments/balance";
    // const END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_UNIQUE_CODE = "money-transfer/provider-balances/adjustments/unique";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_GET_PARTNERS = "money-transfer/partner-setting/partners";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_STORE = "money-transfer/partner-setting/store";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_USER_STORE = "money-transfer/partner-setting/user/store";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_PAYMENT_CHANNELS = "money-transfer/partner-setting/payment-channels";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_PAYMENT_CHANNELS_STORE = "money-transfer/partner-setting/payment-channels/store";
    const END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_GET_CONFIG = "money-transfer/partner-setting/config";
    const END_POINT_MONEY_TRANSFER_BANKS = "money-transfer/banks";

    // JALIN LOG
    const END_POINT_RINTIS_LOG_LIST_INBOUND_YUKK_CO = "rintis/log/list_inbound/yukk_co";
    const END_POINT_RINTIS_LOG_ITEM_INBOUND_YUKK_CO = "rintis/log/item_inbound/yukk_co";

    const END_POINT_RINTIS_QRIS_RE_HIT_LIST_YUKK_CO = "rintis/qris_re_hit/list/yukk_co";
    const END_POINT_RINTIS_QRIS_RE_HIT_ITEM_YUKK_CO = "rintis/qris_re_hit/item/yukk_co";
    const END_POINT_RINTIS_QRIS_RE_HIT_STORE_YUKK_CO = "rintis/qris_re_hit/store/yukk_co";
    const END_POINT_RINTIS_QRIS_RE_HIT_APPROVE_YUKK_CO = "rintis/qris_re_hit/approve/yukk_co";
    const END_POINT_RINTIS_QRIS_RE_HIT_REJECT_YUKK_CO = "rintis/qris_re_hit/reject/yukk_co";

    // DTTOT LIST
    const END_POINT_DTTOT_LIST_YUKK_CO = "yukk_co/dttot";
    const END_POINT_DTTOT_DETAIL_YUKK_CO = "yukk_co/dttot/";
    const END_POINT_DTTOT_EDIT_YUKK_CO = "yukk_co/dttot/";
    const END_POINT_DTTOT_UPDATE_YUKK_CO = "yukk_co/dttot/";
    const END_POINT_DTTOT_DELETE_YUKK_CO = "yukk_co/dttot/";
    const END_POINT_DTTOT_IMPORT_PREVIEW_YUKK_CO = "yukk_co/dttot/import/preview";
    const END_POINT_DTTOT_IMPORT_YUKK_CO = "yukk_co/dttot/import";

    // DTTOT APPROVAL
    const END_POINT_DTTOT_APPROVAL_LIST_YUKK_CO = "yukk_co/dttot/approvals";
    const END_POINT_DTTOT_APPROVAL_DETAIL_YUKK_CO = "yukk_co/dttot/approvals/";
    const END_POINT_DTTOT_APPROVAL_ACTION_YUKK_CO = "yukk_co/dttot/approvals/action";

    // ACTIVITY LOG
    const END_POINT_ACTIVITY_LOG_LIST_YUKK_CO = "merchant-acquisition/activity-log/list";
    const END_POINT_ACTIVITY_LOG_DETAIL_YUKK_CO = "merchant-acquisition/activity-log/detail";

    // SUSPECTED USERS
    const END_POINT_SUSPECTED_USERS_LIST_YUKK_CO = "yukk_co/suspected_users";
    const END_POINT_SUSPECTED_USERS_DETAIL_YUKK_CO = "yukk_co/suspected_users/";
    const END_POINT_SUSPECTED_USERS_ACTION_YUKK_CO = "yukk_co/suspected_users/action";
    const END_POINT_SUSPECTED_USERS_APPROVAL_LIST_YUKK_CO = "yukk_co/suspected_user/approvals";
    const END_POINT_SUSPECTED_USERS_APPROVAL_DETAIL_YUKK_CO = "yukk_co/suspected_user/approvals/";
    const END_POINT_SUSPECTED_USERS_APPROVAL_ACTION_YUKK_CO = "yukk_co/suspected_user/approvals/action";
    const END_POINT_INTERNAL_BLACKLIST = "yukk_co/internal_blacklist";

    // KYM BENEFICIARY
    const END_POINT_KYM_BENEFICIARY_VERIFY_YUKK_CO = "yukk_co/merchant_dttot/verify";
    const END_POINT_KYM_BENEFICIARY_FIND_YUKK_CO = "yukk_co/merchant_dttot/";

    // KYM INBOUND
    const END_POINT_KYM_INBOUND_OCR = "yukk_co/electronic_certificate/inbound_ocr";
    const END_POINT_KYM_INBOUND_VERIFY = "yukk_co/electronic_certificate/inbound_verify";
    const END_POINT_KYM_INBOUND_BIND_KYM_DATA = "yukk_co/electronic_certificate/bind_kym_data";
    const END_POINT_KYM_INBOUND_KYC_DATA = "yukk_co/electronic_certificate/get_kym_data";

    const END_POINT_CLIENT_MANAGEMENT_GET_PERMISSION_LIST = "client-management/permissions";
    const END_POINT_CLIENT_MANAGEMENT_STORE_CLIENT_PERMISSION = "client-management/clients/create";

    const END_POINT_CLIENT_MANAGEMENT_GET_CUSTOMER_LIST = "client-management/options/customers";
    const END_POINT_CLIENT_MANAGEMENT_GET_PARTNER_LIST = "client-management/options/partners";

    const END_POINT_TRANSACTION_ONLINE_GET_TRANSACTION_LIST = "core-api-v3/transaction-online/index";
    const END_POINT_TRANSACTION_ONLINE_GET_TRANSACTION_DETAIL = "core-api-v3/transaction-online/show";

    const END_POINT_PLATFORM_SETTING_LIST = "core-api-v3/platform-setting/index";
    const END_POINT_PLATFORM_SETTING_DETAIL = "core-api-v3/platform-setting/show";
    const END_POINT_PLATFORM_SETTING_UPDATE = "core-api-v3/platform-setting/update";
    const END_POINT_PLATFORM_SETTING_STORE = "core-api-v3/platform-setting/store";

    // APPROVAL LEGAL
    const END_POINT_LEGAL_APPROVAL_COMPANY_LIST = "merchant-activation/legal-approval/index";
    const END_POINT_LEGAL_APPROVAL_COMPANY_DETAIL = "merchant-activation/legal-approval/detail";
    const END_POINT_LEGAL_APPROVAL_COMPANY_ACTION = "merchant-activation/legal-approval/action";

    public static function getBaseUrl()
    {
        return env("BASE_URL_API_GATEWAY");
    }

    public static function requestGeneral($method, $url, $guzzle_params)
    {
        $client = new Client([
            "base_uri" => self::getBaseUrl(),
        ]);

        // Inject JWT token if exists
        $jwt_token = S::getJwtToken();
        $role = S::getUserRole()->role->name ?? '';

        $action = request()->route()->getActionName();
        preg_match('/([a-z]*)@/i', $action, $matches);
        $controller = $matches[1];

        if ($jwt_token || $role || $controller) {
            $original_headers = isset($guzzle_params['headers']) ? $guzzle_params['headers'] : [];
            $injected_headers = array_merge($original_headers, [
                "Authorization" => "Bearer " . $jwt_token,
                "X-USER-ROLE" => $role,
                "X-ACTION-FROM" => $controller
            ]);
            $guzzle_params['headers'] = $injected_headers;
        }

        $exception = null;
        try {
            $response = $client->request($method, $url, $guzzle_params);
        } catch (RequestException $guzzle_exception) {
            $exception = $guzzle_exception;

            if ($guzzle_exception->hasResponse()) {
                $response = $guzzle_exception->getResponse();
            } else {
                // Don't have Response??
                // Timeout ???
                $response = null;
            }
        } catch (GuzzleException $guzzle_exception) {
            $exception = $guzzle_exception;

            // Timeout
            $response = null;
        } catch (Exception $e) {
            $exception = $e;
            try {
                $response = $e->getResponse();
            } catch (Exception $e) {
                $exception = $e;

                $response = null;
            }
        }

        if ($exception != null && env("API_HELPER_LOG_FAILED", false)) {
            Log::error($exception->getMessage());
        }

        if ($response) {
            // There is a response
            //  Build response based on the $response
            $custom_response = new CustomResponse($response);
        } else {
            // There is no response
            //  Build a default Response
            $custom_response = new CustomResponse($response);
        }

        return $custom_response;
    }
}
