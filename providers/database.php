<?php

namespace Mwu\Pdo;

use PDO;
use PDOException;

class Database
{
    private
        $db,
        $affected = 0;

    public function connect()
    {
        try {
            $this->db = new PDO(DB_CONFIG, DB_USER, DB_PASSWORD);
        } catch (PDOException $err) {
            echo "Error: {$err->getMessage()}";
        }
    }

    public function dbQuery($sql, $params = [])
    {
        $affected = 0;

        if (!isset($db) || empty($db)) {
            $this->connect();
        }

        try {
            $query = $this->db->prepare($sql);
            $query->execute($params);
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            $affected = $query->rowCount();
        } catch (PDOException $err) {
            echo "Error: {$err->getMessage()}";
        }

        return $result;
    }

    public function get($param)
    {
        return $this->$param;
    }

    public function close()
    {
        $this->db = null;
    }
}
