<?php

$providers = [
    App\Providers\AppServiceProvider::class,
];

// Don't register the aiblog provider during testing to avoid DB schema checks
if (env('APP_ENV') !== 'testing') {
    $providers[] = Sligoman\AiblogApiWeb\BlogApiServiceProvider::class;
}

return $providers;
