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

    try {
      $this->connection = new PDO(
        "mysql:host=$dbhost;charset=utf8mb4;port=$dbport;dbname=$dbName",
        $userName,
        $userPassword,
        [
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
      );

    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  private function createTable($pdo, $tableName, $sql)
  {
    $tableExists = $pdo->query("SHOW TABLES LIKE $tableName");

    if (!$tableExists) {
      $pdo->exec($sql);
    }
  }

  public function createTables()
  {
    $pdo = $this->connection;
    $tables = array(
      "users" => "
      CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP()
      );
      ",
      "projects" => "
      CREATE TABLE projects (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        start_date DATE NOT NULL,
        user_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
      );
      ",
      "requirements" => "
      CREATE TABLE requirements (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        description VARCHAR(255),
        priority INTEGER CHECK (priority >= 1 and priority <= 5),
        project_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
      );
      ",
      "functional_requirements" => "
      CREATE TABLE functional_requirements (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        requirement_id INTEGER NOT NULL,
        estimate INTEGER NOT NULL,
        FOREIGN KEY (requirement_id) REFERENCES requirements(id) ON DELETE CASCADE
      );
      ",
      "nonfunctional_requirements" => "
      CREATE TABLE nonfunctional_requirements (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        unit VARCHAR(255) NOT NULL,
        value VARCHAR(255) NOT NULL,
        requirement_id INTEGER NOT NULL,
        FOREIGN KEY (requirement_id) REFERENCES requirements(id) ON DELETE CASCADE
      );
      "
    );

    foreach ($tables as $tableName => $sql) {
      $this->createTable($pdo, $tableName, $sql);
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
