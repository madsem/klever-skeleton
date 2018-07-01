<?php

// instantiate App
$app = \Klever\App\Wrapper::getInstance();

// get application routes
if ($console = PHP_SAPI == 'cli' ? true : false) {
    require_once base_path('routes/console.php');
}
else {
    require_once base_path('routes/web.php');
    require_once base_path('routes/api.php');
}
