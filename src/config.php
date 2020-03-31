<?php

return [
    ///mode switch - live or test
    'mode' => 'test',
    'test_api_secret_key' => 'hfucj5jatq8h',
    'test_api_public_key' => 'uvjqzm5xl6bw',

    'live_api_secret_key' => 'qgnewbde0wlj',
    'live_api_public_key' => '0z8eqm1blvyk',

    'currency' => 'NGN',///USD,GHS,KES are other options
    //urls
    'balance_link' => 'https://sandbox.wallets.africa/self/balance', // check balance
    'transfer_link' => 'https://sandbox.wallets.africa/transfer/bank/account', //make transfer
    'transaction_details_link' => 'https://sandbox.wallets.africa/transfer/bank/details', // get the status of a transaction
    'account_enquiry_link' => 'https://sandbox.wallets.africa/transfer/bank/account/enquire', //confirm account details
    'allBanks_link' => 'https://sandbox.wallets.africa/transfer/banks/all', //fetch banks and their codes

    ///live urls

    'live_balance_link' => 'https://api.wallets.africa/self/balance', // check balance
    'live_transfer_link' => 'https://api.wallets.africa/transfer/bank/account', //make transfer
    'live_transaction_details_link' => 'https://api.wallets.africa/transfer/bank/details', // get the status of a transaction
    'live_account_enquiry_link' => 'https://api.wallets.africa/transfer/bank/account/enquire', //confirm account details
    'live_allBanks_link' => 'https://api.wallets.africa/transfer/banks/all', //fetch banks and their codes
];

