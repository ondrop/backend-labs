<?php

use src\Model\Router;

define('APP_PATH', dirname(__DIR__).'/src');
define('PAGE_404', APP_PATH.'/templates/404page.html');

require_once APP_PATH.'/common.php';

Router::execute($_SERVER['REQUEST_URI']);
