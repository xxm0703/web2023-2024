<?php


class Db
{
    private $connection;

    public function __construct()
    {
        $dbhost = $_ENV['DB_HOST'];        
        $dbport = $_ENV['DB_PORT'];
        $dbName = $_ENV['MYSQL_DATABASE'];
        $userName = $_ENV['MYSQL_USER'];
        $userPassword = $_ENV['MYSQL_PASSWORD'];

        $this->connection = new PDO("mysql:host=$dbhost;charset=utf8mb4;port=$dbport;dbname=$dbName", $userName, $userPassword,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

?>