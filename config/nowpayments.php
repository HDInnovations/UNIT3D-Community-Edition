<?php

/*
 * This file is part of the Laravel NOWPayments package.
 *
 * (c) Prevail Ejimadu <prevailexcellent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /**
     * API Key From NOWPayments Dashboard.
     */
    'apiKey' => getenv('NOWPAYMENTS_API_KEY'),

    /**
     * You enviroment can either be live or sandbox.
     * Make sure to add the appropriate API key after changing the enviroment in .env.
     */
    'env' => getenv('NOWPAYMENTS_ENV', 'sandbox'),

    /**
     * NOWPayments Live URL.
     */
    'liveUrl' => "https://api.nowpayments.io/v1",

    /**
     * NOWPayments Sandbox URL.
     */
    'sandboxUrl' => "https://api-sandbox.nowpayments.io/v1",

    /**
     * Your callback URL.
     */
    'callbackUrl' => getenv('NOWPAYMENTS_CALLBACK_URL'),

    /**
     * Your URL Path.
     */
    'path' => 'laravel-nowpayments',

    /**
     * You can add your custom middleware to access the dashboard here.
     */
    'middleware' => null, // "Authorise::class",

    /**
     * Your Nowpayment email here.
     */
    'email' => getenv('NOWPAYMENTS_EMAIL'),

    /**
     * Your Nowpayment password here.
     */
    'password' => getenv('NOWPAYMENTS_PASSWORD'),
];
