<?php

$routes = [
    '/auth/hello' => [AuthHelloController::class, 'index'],
    '/auth/complete' => [AuthCompleteController::class, 'index'],

    '/get/public-key' => [GetPublicKeyController::class, 'index', [AuthMiddleware::class]],
    '/message/send' => [MessageSendController::class, 'index', [AuthMiddleware::class]],
    '/message/receive' => [MessageReceiveController::class, 'index', [AuthMiddleware::class]],

    '/error' => function () {
        return 'Could not find this page.';
    }
];