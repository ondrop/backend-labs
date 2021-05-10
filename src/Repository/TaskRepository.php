<?php

namespace src\Repository;

class TaskRepository
{
    const DATE_FORMAT = '%Y-%m-%d %H%:%i';

    private $conn;

    public function __construct()
    {
        $this->conn = Database::getDBConnection();
    }

    public function getAll()
    {
        $STH = $this->conn->prepare("
            SELECT
                t.id_task as id_task,
                t.description as description,
                DATE_FORMAT(t.date_start, '%d-%m-%Y %H%:%i:%s') as date_start,
                DATE_FORMAT(t.date_end, '%d-%m-%Y %H%:%i:%s') as date_end,
                p.value AS priorety
            FROM todo AS t
            LEFT JOIN priorety p ON p.id_priorety = t.id_priorety
            ORDER BY id_task
        ");
        $STH->execute();
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        return $STH->fetchAll();
    }

    public function getPrioritiy(string $value)
    {
        $STH = $this->conn->prepare("
            SELECT id_priorety id, `value`
            FROM `priorety` AS p
            WHERE `value` LIKE :priorety
        ");
        $STH->bindValue('priorety', $value);
        $STH->execute();
        return $STH->fetch();
    }

    public function getPriorities()
    {
        $STH = $this->conn->prepare("SELECT id_priorety id, value FROM `priorety` AS p");
        $STH->execute();
        return $STH->fetchAll();
    }

    public function getTaskCount(array $conditions, array $parameters)
    {
        $sql = "
        SELECT count(*) as tableCount
        FROM todo AS t
        LEFT JOIN priorety p ON p.id_priorety = t.id_priorety
    ";

        if ($conditions)
        {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $STH = $this->conn->prepare($sql);
        $STH->execute($parameters);
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $STH->fetch();
        return $result['tableCount'];
    }

    public function getTasks(array $conditions, array $parameters, array $orderData)
    {
        $sql = "
        SELECT
            t.id_task as id_task,
            t.description as description,
            DATE_FORMAT(t.date_start, '%d-%m-%Y %H%:%i:%s') as date_start,
            DATE_FORMAT(t.date_end, '%d-%m-%Y %H%:%i:%s') as date_end,
            p.value AS priorety
        FROM todo AS t
        LEFT JOIN priorety p ON p.id_priorety = t.id_priorety
    ";

        if (!empty($conditions))
        {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY " . $orderData['orderBy'] . " LIMIT " . $orderData['startLimit'] . ", " . $orderData['limit'];

        $STH = $this->conn->prepare($sql);
        $STH->execute($parameters);
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        return $STH->fetchAll();
    }

    public function getTask(string $id, string $dateFormat = self::DATE_FORMAT)
    {
        $sql = "
        SELECT
            t.id_task as idTask,
            t.description as description,
            DATE_FORMAT(t.date_start, :dateFormat) as dateStart,
            DATE_FORMAT(t.date_end, :dateFormat) as dateEnd,
            p.value AS priorety
        FROM todo AS t
        LEFT JOIN priorety p ON p.id_priorety = t.id_priorety
        WHERE id_task = :id
    ";

        $STH = $this->conn->prepare($sql);
        $STH->bindValue('id', $id, \PDO::PARAM_INT);
        $STH->bindValue('dateFormat', $dateFormat);
        $STH->execute();
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        return $STH->fetch();
    }

    public function updateTask(array $parameters)
    {
        $sql = "
        UPDATE todo AS t
        SET 
            t.description = :description, 
            t.date_start = :dateStart,
            t.date_end = :dateEnd,
            t.id_priorety = :priorety
        WHERE t.id_task = :idTask
    ";

        $STH = $this->conn->prepare($sql);
        return $STH->execute($parameters);
    }

    public function deleteTasks(array $ids)
    {
        $inQuery = implode(',', array_map('intval', $ids));
        $sql = "
        DELETE FROM todo AS t
        WHERE t.id_task IN (".$inQuery.")
    ";

        $STH = $this->conn->prepare($sql);
        return $STH->execute();
    }

    public function addTask(array $parameters)
    {
        $sql = "
        INSERT INTO todo(description, date_start, date_end, id_priorety)
        VALUES(:description, :dateStart, :dateEnd, :priorety)
    ";

        $STH = $this->conn->prepare($sql);
        return $STH->execute($parameters);
    }

    public function updateTaskApi(array $parameters)
    {
        $sql = "
        UPDATE todo AS t
        SET 
            t.description = :description, 
            t.date_start = :dateStart,
            t.date_end = :dateEnd,
            t.id_priorety = (SELECT id_priorety FROM priorety WHERE `value` = :priorety)
        WHERE t.id_task = :idTask
    ";

        $STH = $this->conn->prepare($sql);
        return $STH->execute($parameters);
    }
}
