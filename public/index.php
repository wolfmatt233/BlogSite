<?php

define("BASE_URL", "http://localhost:8000");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require '../vendor/autoload.php';

$router = require '../app/Routes/index.php';