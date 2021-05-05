<?php

namespace src\Service;

use src\Repository\Database;
use src\Repository\TaskRepository;

class TaskApiService
{
    private $taskRepository;
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getDBConnection();
        $this->taskRepository = new TaskRepository();
    }

    public function new(): array
    {
        $parameters = [];
        $errors = [];
        if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
            $parameters['description'] = trim($_POST['description']);
        } else {
            $errors[] = 'Описание не может быть пустым';
        }

        if (isset($_POST['date_start']) && !empty(trim($_POST['date_start']))) {
            try {
                $dateTime = new \DateTime(trim($_POST['date_start']));
                $parameters['dateStart'] = $dateTime->format('Y-m-d H:i:s');
            } catch(\Exception $e) {
                $errors[] = 'Некорректная дата. Валидный формат YYYY-MM-DD hh:ii';
                return [$errors, false];
            }
        } else {
            $errors[] = 'Дата начала задачи не может быть пустым';
        }

        if (isset($_POST['date_end']) && !empty(trim($_POST['date_end']))) {
            try {
                $dateTime = new \DateTime(trim($_POST['date_end']));
                $parameters['dateEnd'] = $dateTime->format('Y-m-d H:i:s');
            } catch(\Exception $e) {
                $errors[] = 'Некорректная дата. Валидный формат YYYY-MM-DD hh:ii';
                return [$errors, false];
            }
        } else {
            $parameters['dateEnd'] = null;
        }

        $priority = $this->taskRepository->getPrioritiy(trim($_POST['priorety']));
        if (isset($_POST['priorety']) && !empty(trim($_POST['priorety']))) {
            if ($priority) {
                $parameters['priorety'] = $priority['id'];
            } else {
                $errors[] = 'Приоритет "'.trim($_POST['priorety']).'" не существует';
            }
        } else {
            $errors[] = 'Приоритет не может быть пустым';
        }

        if (empty($errors)) {
            if ($this->taskRepository->addTask($parameters)) {
                return [$errors, $this->taskRepository->getTask($this->conn->lastInsertId())];
            } else {
                $errors[] = 'Что-то пошло не так';
                return [$errors, false];
            }
        } else {
            return [$errors, false];
        }
    }

    public function getAll()
    {
        return $this->taskRepository->getAll();
    }
}
