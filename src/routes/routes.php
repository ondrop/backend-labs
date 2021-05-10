<?php

use src\Model\Router;
use src\Controller\TaskController;
use src\Controller\TaskApiController;

Router::add('/', function() {
    (new TaskController())->index();
});

Router::add('/add_task', function() {
    (new TaskController())->add();
});

Router::add('/edit_task', function() {
    (new TaskController())->edit();
});

/** task api */

Router::add('/api/tasks', function() {
    (new TaskApiController())->index();
});

Router::add('/api/tasks/(?P<id>[0-9]+)', function(int $id) {
    (new TaskApiController())->taskAction($id);
});
