<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Billing API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi koneksi ke sistem Billing (PDAM Cater) untuk sinkronisasi
    | data master dan (nantinya) pengiriman data catat meter.
    |
    */

    // Base URL API billing (tanpa trailing slash)
    'base_url' => env('BILLING_BASE_URL', 'http://192.168.1.70:8080/api/v1'),

    // API Key yang diberikan developer billing
    'api_key' => env('BILLING_API_KEY', ''),

    // Timeout request (detik)
    'timeout' => env('BILLING_TIMEOUT', 30),

    // Apakah sinkronisasi data master aktif?
    'sync_enabled' => env('BILLING_SYNC_ENABLED', false),

];