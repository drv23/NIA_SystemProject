<?php
return [

    'paths' => ['api/*', 'login', 'logout', 'sanctum/csrf-cookie'],

    'credentials' => true,

    // Replace '*' with your frontend's exact origin
    'allowed_origins' => ['http://localhost:5174'], // or whatever your frontend port is

    'allowed_methods' => ['*'],

    'allowed_headers' => ['*'],

    'supports_credentials' => true,

];