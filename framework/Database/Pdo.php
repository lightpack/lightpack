<?php

namespace Framework\Database;

use Framework\Database\Lucid\Model;
use Framework\Database\Query\Query;

class Pdo 
{
    protected $statement;
    protected $connection;

    public function __construct(
        string $dsn, 
        string $username = null, 
        string $password = null, 
        array $options = null
    ) {
        try {
            $this->connection = new \PDO($dsn, $username, $password, $options);
        } catch(\PDOException $e) {
			throw new \Exception('Database connection failed: \'' . $e->getMessage() . '\'');
        }

		$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function table(string $table)
    {
        return new Query($table, $this);
    }

    public function query(string $sql, array $params = null)
    {
        if($params) {
            $this->statement = $this->connection->prepare($sql);
            $this->statement->execute($params);
        } else {
            $this->statement = $this->connection->query($sql);
        }

        return $this->statement;
    }

    public function model(string $model)
    {
        $modelInstance = new $model;
        $modelInstance->setConnection($this);

        return $modelInstance;
    }
}