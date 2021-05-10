<?php

namespace src\Controller;

use src\Repository\TaskRepository;
use src\Service\TaskApiService;

class TaskApiController
{
    private $taskApiService;
    private $taskRepository;

    public function __construct()
    {
        $this->taskApiService = new TaskApiService();
        $this->taskRepository = new TaskRepository();
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

    public function taskAction(int $id)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->getTask($id);
                break;
            case 'PUT':
                $this->update($id);
                break;
            case 'DELETE':
                $this->delete($id);
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
        echo json_encode($this->taskRepository->getAll());
    }

    private function getTask(int $id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($this->taskRepository->getTask($id));
    }

    private function update(int $id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        $apiData = file_get_contents("php://input");
        if ($task = $this->taskRepository->getTask($id)) {
            parse_str($apiData, $parameters);
            $parameters = array_merge($task, $parameters);
            if ($this->taskRepository->updateTaskApi($parameters)) {
                echo json_encode($parameters);
            } else {
                echo json_encode('Обновление не удалось');
            }
        } else {
            echo json_encode("Задачи с id \"$id\" не существует");
        }
    }

    private function delete(int $id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($this->taskRepository->deleteTasks([$id])) {
            echo json_encode("Задача с id \"$id\" удалена");
        } else {
            echo json_encode("Удаление не удалось");
        }
    }

    private function apiNotFound()
    {
        http_response_code(404);
        return include_once PAGE_404;
    }
}
