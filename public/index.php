<?php

// fire up composer
require dirname(__DIR__) . '/vendor/autoload.php';

// bootstrap everythang...
require_once dirname(__DIR__) . '/bootstrap/app.php';

// run, app, RUN!
$app->run();