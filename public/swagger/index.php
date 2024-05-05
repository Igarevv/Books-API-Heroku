<?php
define('APP_PATH', dirname(__DIR__, 2));

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

require APP_PATH.'/public/swagger/dist/index.html';

//exit();