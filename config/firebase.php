<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account Credentials
    |--------------------------------------------------------------------------
    |
    | Path ke file service account JSON yang diunduh dari Firebase Console.
    | Buka: Firebase Console → Project Settings → Service Accounts
    |       → Generate new private key
    |
    | Simpan file JSON tersebut di: storage/app/firebase/service-account.json
    |
    */
    // 'credentials' => storage_path(env('FIREBASE_CREDENTIALS', 'app/firebase/service-account.json')),
    'credentials' => env('FIREBASE_SERVICE_ACCOUNT_JSON')
        ? json_decode(env('FIREBASE_SERVICE_ACCOUNT_JSON'), true)
        : null,

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    |
    | Project ID dari Firebase Console → Project Settings → General.
    | Contoh: "kepitink-12345"
    |
    */
    'project_id' => env('FIREBASE_PROJECT_ID', ''),

];
