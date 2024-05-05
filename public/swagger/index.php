<?php
define('APP_PATH', dirname(__DIR__, 2));

header("Content-Type: text/html");

require APP_PATH.'/public/swagger/dist/index.html';

exit;