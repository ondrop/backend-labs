<?php

namespace src\Repository;

class Database
{
    public static function getDBConnection(): \PDO
    {
        static $DBH = null;
        if ($DBH == null)
        {
            try {
                $DBH = new \PDO(DB_DSN, DB_USER, DB_PASSWORD);
            }
            catch(\PDOException $e) {
                echo $e->getMessage();
                file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
            }
        }

        return $DBH;
    }
}
