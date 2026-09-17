<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database credentials
|--------------------------------------------------------------------------
|
| This is the ONE file that differs between your XAMPP machine and the live
| hosting. Everything else — the site address, whether errors are shown — is
| worked out automatically from the request.
|
| On cPanel: create the database and a user in "MySQL® Databases", tick ALL
| PRIVILEGES for that user on that database, then paste the three values here.
| cPanel prefixes both names with your account name, e.g. kohsar_site.
|
*/

return [
    // ---- Live hosting (cPanel). Fill these in before uploading. ----
    'production' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'pcshfdco_pcs',
        'user'    => 'pcshfdco_pcsshfd',
        'pass'    => 'CPANEL_DB_PASSWORD',
        'charset' => 'utf8mb4',
    ],

    // ---- This XAMPP machine. Leave as is. ----
    'development' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'pakistan_cambridge_school',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
];
