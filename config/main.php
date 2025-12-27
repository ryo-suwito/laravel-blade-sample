<?php

return [
    'default_bank' => [
        'bank_id' => env('BENEFICIARY_DEFAULT_BANK_ID', '1'),
        'bank_type' => env('BENEFICIARY_DEFAULT_BANK_TYPE', 'BCA'),
        'account_number' => env('BENEFICIARY_DEFAULT_ACCOUNT_NUMBER', '2770622959'),
        'account_name' => env('BENEFICIARY_DEFAULT_ACCOUNT_NAME', 'PT YUKK KREASI INDONESIA'),
        'branch_name' => env('BENEFICIARY_DEFAULT_BRANCH_NAME', 'KCP TAMAN DUTA INDAH'),
    ]
];