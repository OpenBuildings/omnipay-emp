<?php

error_reporting(E_ALL);

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('Omnipay\\Emp\\Test\\', __DIR__.'/src');

date_default_timezone_set('UTC');
