<?php
class ConnectionMysql
{
    protected $HOST = 'localhost';
    protected $USER = 'root';
    protected $PASSWORD = '';
    protected $DATABASE = 'pruebaphp';
    protected $PORT = 3306;
    protected $CHARSET = 'utf8mb4';
    public $CONNECTION;
    public $RESULT;
    public $ERROR;

    public function connect()
    {
        try {
            $this->CONNECTION = new PDO(
                "mysql:host={$this->HOST};
                port={$this->PORT};
                dbname={$this->DATABASE};
                charset={$this->CHARSET}",
                $this->USER,
                $this->PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            $this->CONNECTION->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->CONNECTION->exec("SET NAMES 'utf8mb4'");
            $this->CONNECTION->exec("SET lc_time_names = 'es_ES'");
            return $this->CONNECTION;
        } catch (PDOException $e) {
            $this->ERROR = $e->getMessage();
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }

    public function disconnect()
    {
        $this->CONNECTION = null;
    }

    public function queryPost(string $query, array $params = [])
    {
        $CONNECTION = $this->connect();
        if (!$CONNECTION) {
            return false;
        }
        try {
            $CONNECTION->beginTransaction();
            $QUERY_PREPARED = $CONNECTION->prepare($query);
            $RESULT = $QUERY_PREPARED->execute($params);
            $CONNECTION->commit();
            return $RESULT;
        } catch (\Throwable $th) {
            $CONNECTION->rollBack();
            $this->ERROR = $th->getMessage();
            error_log("Query error: " . $this->ERROR);
            return false;
        }
    }

    public function queryGet(string $query, array $params = [])
    {
        $CONNECTION = $this->connect();
        if (!$CONNECTION) {
            return false;
        }
        try {
            $QUERY_PREPARED = $CONNECTION->prepare($query);
            $QUERY_PREPARED->execute($params);
            return $QUERY_PREPARED->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            $this->ERROR = $th->getMessage();
            error_log("Query error: " . $this->ERROR);
            return false;
        }
    }

    public function getLastError()
    {
        return $this->ERROR;
    }
}
