<?php

require 'vendor/autoload.php';

use Figaro\Premium\Comments\Tests\Boot;

$settings = array(
  'app_name' => 'odm-test',
  'db_credentials' => array(
    'host' => 'localhost',
    'port' => 27017,
    'base' => 'comments',
  ),
  'logger_channel' => 'odm-test',
);

$boot = new Boot($settings);
return $boot;
