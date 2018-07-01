<?php

$app->group('/api', function () {
   $this->get('/', '\Klever\Controllers\Api\SomeController:index');
});