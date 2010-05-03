<?php

// instantiate framework, load configuration, dispatch application
require dirname( __FILE__ ) . '/lib/Core.php';
$config = require 'app/frontend/config/base.php';

$app = Core::createApp($config);
$app->dispatch();