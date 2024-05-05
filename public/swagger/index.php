<?php
define('APP_PATH', dirname(__DIR__, 2));
var_dump(APP_PATH);
header("Content-Type: text/html");

require APP_PATH.'/public/swagger/dist/index.html';

exit;