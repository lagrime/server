<?php

require_once "core/autoloader/IAutoloader.php";
require_once "core/autoloader/Autoloader.php";
require_once "core/SB.php";
require_once "routes.php";
require_once "config.php";

if (!isset($routes)) die(404);

(new Autoloader())->register();
(new Router())->route($routes);