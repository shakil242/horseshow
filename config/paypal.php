<?php
//    return [
//        // set your paypal credential
//        'client_id' => env('PAYPAL_CLIENT_ID',''),
//        'secret' => env('PAYPAL_SECRET',''),
//
//        /**
//         * SDK configuration
//         */
//        'settings' => array(
//            /**
//             * Available option 'sandbox' or 'live'
//             */
//            'mode' => env('PAYPAL_MODE','sandbox'),
//
//            /**
//             * Specify the max request time in seconds
//             */
//            'http.ConnectionTimeOut' => 30,
//
//            /**
//             * Whether want to log to a file
//             */
//            'log.LogEnabled' => true,
//
//            /**
//             * Specify the file that want to write on
//             */
//            'log.FileName' => storage_path() . '/logs/paypal.log',
//
//            /**
//             * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
//             *
//             * Logging is most verbose in the 'FINE' level and decreases as you
//             * proceed towards ERROR
//             */
//            'log.LogLevel' => 'ERROR'
//        ),
//    ];

    
    return [
        'mode' => 'sandbox',        // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
        'sandbox' => [
            'username' => 'shakilmultan_api1.gmail.com',       // Api Username
            'password' => 'JLWM72XED6LUNUWX',       // Api Password
            'secret' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31ALdDNCpY6jPwd5eS0OJJ0lKlsP-v',         // This refers to api signature
            'certificate' => '',    // Link to paypals cert file, storage_path('cert_key_pem.txt')
            'app_id'=>env('app_id'),
        ],
       
        'payment_action' => 'Sale', // Can Only Be 'Sale', 'Authorization', 'Order'
        'currency' => 'USD',
        'notify_url' => '',         // Change this accordingly for your application.
    ];