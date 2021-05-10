<?php


namespace src\Controller;

use src\Repository\Database;
use src\Repository\TaskRepository;
use src\Service\TaskService;

class TaskController
{
    private $conn;
    private $taskRepository;

    private $startLimit = 0;
    private $firstPage = 1;
    private $lastPage = 1;
    private $limit = 10;
    private $taskService;

    public function __construct()
    {
        $this->conn = Database::getDBConnection();
        $this->taskRepository = new TaskRepository();
        $this->taskService = new TaskService();
    }

    public function index()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
        $url = $url[0];

        if (isset($_POST['delete']) && isset($_POST['idTask']) && !empty($_POST['idTask'])) {
            $this->taskRepository->deleteTasks([$_POST['idTask']]);
            header('Location: '.$url);
        }

        if (isset($_GET['delete_ids']) && !empty($_GET['delete_ids'])) {
            $this->taskRepository->deleteTasks($_GET['delete_ids']);
            header('Location: '.$url);
        }

        list($conditions, $parameters, $prevQueries, $orderBy) = $this->taskService->getDataFromQuery();

        if (isset($_GET['offerCount']) && is_numeric($_GET['offerCount']) && in_array($_GET['offerCount'], OFFER_COUNTS) && $_GET['offerCount'] != 10) {
            $this->limit = $_GET['offerCount'];
        }

        $orderBy = in_array($orderBy, ORDER_BYS) ? $orderBy : "id_task ASC";

        $priorities = $this->taskRepository->getPriorities();
        $tableCount = $this->taskRepository->getTaskCount($conditions, $parameters);

        $this->lastPage = floor($tableCount / $this->limit);
        if ($tableCount % $this->limit != 0) {
            $this->lastPage++;
        }

        if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] <= $this->lastPage && $_GET['page'] > 0) {
            $this->firstPage = $_GET['page'];
        }

        $this->startLimit = ($this->firstPage - 1) * $this->limit;
        $orderData = [
            'orderBy' => $orderBy,
            'startLimit' => (int)$this->startLimit,
            'limit' => (int)$this->limit
        ];
        $todoData = $this->taskRepository->getTasks($conditions, $parameters, $orderData);

        $prevQueries[] = "page=";

        $query = implode("&", $prevQueries);

        $showPagination = $this->lastPage > 1;

        define("PAGE_DATA", [
            'priorities' => $priorities,
            'todoData' => $todoData,
            'query' => $query,
            'showPagination' => $showPagination,
            'firstPage' => $this->firstPage,
            'lastPage' => $this->lastPage,
            'startLimit' => $this->startLimit,
            'limit' => $this->limit,
        ]);
        require_once APP_PATH . '/templates/task/tasks.php';
    }

    public function add()
    {
        $priorities = $this->taskRepository->getPriorities();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
                $parameters['description'] = trim($_POST['description']);
            } else {
                $errors[] = 'Описание не может быть пустым';
            }

            if (isset($_POST['dateStart']) && !empty($_POST['dateStart'])) {
                $date = new \DateTime($_POST['dateStart']);
                $parameters['dateStart'] = $date->format('Y-m-d H:i:s');
            } else {
                $errors[] = 'Дата начала задачи не может быть пустым';
            }

            if (isset($_POST['dateEnd']) && !empty($_POST['dateEnd'])) {
                $date = new \DateTime($_POST['dateEnd']);
                $parameters['dateEnd'] = $date->format('Y-m-d H:i:s');
            } else {
                $parameters['dateEnd'] = null;
            }

            if (isset($_POST['priorety']) && !empty($_POST['priorety'])) {
                $parameters['priorety'] = $_POST['priorety'];
            } else {
                $errors[] = 'Приоритет не может быть пустым';
            }

            $success = false;
            if (empty($errors)) {
                $success = $this->taskRepository->addTask($parameters);
            }
        }

        define("PAGE_DATA", [
            'priorities' => $priorities,
            'success' => $success ?? null,
            'errors' => $errors ?? null
        ]);

        require_once APP_PATH . '/templates/task/add_task.php';
    }

    public function edit()
    {
        $priorities = $this->taskRepository->getPriorities();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (isset($_POST['idTask']) && !empty($_POST['idTask'])) {
                $parameters['idTask'] = $_POST['idTask'];
            } else {
                $errors[] = 'Что-то пошло не так';
            }

            if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
                $parameters['description'] = trim($_POST['description']);
            } else {
                $errors[] = 'Описание не может быть пустым';
            }

            if (isset($_POST['dateStart']) && !empty($_POST['dateStart'])) {
                $date = new \DateTime($_POST['dateStart']);
                $parameters['dateStart'] = $date->format('Y-m-d H:i:s');
            } else {
                $errors[] = 'Дата начала задачи не может быть пустым';
            }

            if (isset($_POST['dateEnd']) && !empty($_POST['dateEnd'])) {
                $date = new \DateTime($_POST['dateEnd']);
                $parameters['dateEnd'] = $date->format('Y-m-d H:i:s');
            } else {
                $parameters['dateEnd'] = null;
            }

            if (isset($_POST['priorety']) && !empty($_POST['priorety'])) {
                $parameters['priorety'] = $_POST['priorety'];
            } else {
                $errors[] = 'Приоритет не может быть пустым';
            }

            if (empty($errors)) {
                $success = $this->taskRepository->updateTask($parameters);
            }
        }

        $taskData = $this->taskRepository->getTask($_GET['id'] ?? '', '%Y-%m-%dT%H%:%i');
        if (!$taskData) {
            http_response_code(404);
            include PAGE_404;
            return;
        }

        define("PAGE_DATA", [
            'priorities' => $priorities,
            'success' => $success ?? null,
            'errors' => $errors ?? null
        ]);

        require_once APP_PATH . '/templates/task/edit_task.php';
    }
}
