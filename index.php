<?php

use src\Model\Router;

require_once './src/common.php';

Router::execute($_SERVER['REQUEST_URI']);
