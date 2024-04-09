<?php

$routes = [
    '/auth/hello' => [AuthHelloController::class, 'index'],
    '/auth/complete' => [AuthCompleteController::class, 'index'],

    '/get/public-key' => [GetPublicKeyController::class, 'index'],
    '/set/public-key' => [SetPublicKeyController::class, 'index', [AuthMiddleware::class]],

    '/error' => function () {
        return 'Could not find this page.';
    }
];