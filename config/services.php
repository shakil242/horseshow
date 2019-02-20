<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'stripeFee' => '2.90',
        'stripeFeeCent' => '0.30',
    ],
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' =>env('PAYPAL_SECRET'),
        'app_id' => env('app_id'),
        'developerAccountEmail' => 'shakilmultan@gmail.com',
        'ApplicationID' => env('ApplicationID'),
        'APIUsername' => env('APIUsername'),
        'APIPassword' => env('APIPassword'),
        'APISignature' => env('APISignature'),
        'paypalFee' => '2.90',
        'paypalFeeCent' => '0.30',

    ],

    'ls_query' => [
        'table'              => 'users',
        // specify the name of search columns
        'searchColumns'      => ['name'],
        // specify order by column. This is optional
        'orderBy'            => '',
        // specify order direction e.g. ASC or DESC. This is optional
        'orderDirection'     => '',
        /**
         * filter the result by entering table column names
         * to get all the columns, remove filterResult or make it an empty array
         */
        'filterResult'       => [],
        /**
         * specify search query comparison operator.
         * possible values for comparison operators are: 'LIKE' and '='. this is required
         */
        'comparisonOperator' => 'LIKE',
        /**
         * searchPattern is used to specify how the query is searched.
         * possible values are: 'q', '*q', 'q*', '*q*'. this is required
         */
        'searchPattern'      => 'q*',
        // specify search query case sensitivity
        'caseSensitive'      => false,
        // to limit the maximum number of result uncomment this:
        //'maxResult' => 100,
        // to display column header, change 'active' value to true
        'displayHeader' => [
            'active' => true,
            'mapper' => [
                'name' => 'Name',
//                        'your_second_column' => 'Your Desired Second Title'
            ]
        ],
        'type'               => 'mysql',
    ],
    //

];

