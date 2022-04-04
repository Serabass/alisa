<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'instance.php';
include_once 'towns.php';
include_once 'response.php';

header('Content-Type: application/json');
echo json_encode(SampleAlisa::instance()->meta());
