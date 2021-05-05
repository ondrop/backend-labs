<?php

namespace src\Controller;

use src\Service\TaskApiService;

class TaskApiController
{
    private $taskApiService;

    public function __construct()
    {
        $this->taskApiService = new TaskApiService();
    }

    public function index()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->new();
                break;
            case 'GET':
                $this->getAll();
                break;
            default:
                $this->apiNotFound();
        }
    }

    private function new()
    {
        list($errors, $result) = $this->taskApiService->new();
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($errors);
        }
    }

    private function getAll()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($this->taskApiService->getAll());
    }

    private function apiNotFound()
    {
        http_response_code(404);
        return include './404page.html';
    }
}
