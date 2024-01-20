<?php
function createTable($pdo, $tableName, $sql) {
    $tableExists = $pdo->query("SHOW TABLES LIKE $tableName");

    if (!$tableExists) {
        // Execute the SQL statement
        $pdo->exec($sql);

    } 
}

function createTables($pdo) {

$tables = array(
    "projects" => "
      CREATE TABLE projects (
        id INTEGER PRIMARY KEY,
        name VARCHAR(255),
        created_at TIMESTAMP
      );
    ",
    "requirements" => "
      CREATE TABLE requirements (
        id INTEGER PRIMARY KEY,
        name VARCHAR(255),
        description VARCHAR(255),
        project_id INTEGER,
        created_at TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects(id)
      );
    ",
    "functional_requirements" => "
      CREATE TABLE functional_requirements (
        id INTEGER PRIMARY KEY,
        requirement_id INTEGER,
        FOREIGN KEY (requirement_id) REFERENCES requirements(id)
      );
    ",
    "nonfunctional_requirements" => "
      CREATE TABLE nonfunctional_requirements (
        id INTEGER PRIMARY KEY,
        unit VARCHAR(255),
        value VARCHAR(255),
        requirement_id INTEGER,
        FOREIGN KEY (requirement_id) REFERENCES requirements(id)
      );
    ",
    "hash_tags" => "
      CREATE TABLE hash_tags (
        id INTEGER PRIMARY KEY,
        name VARCHAR(255) UNIQUE,
        created_at TIMESTAMP
      );
    ",
    "hash_tags_requirements" => "
      CREATE TABLE hash_tags_requirements (
        id INTEGER PRIMARY KEY,
        hash_tag_id INTEGER,
        requirement_id INTEGER,
        FOREIGN KEY (hash_tag_id) REFERENCES hash_tags(id),
        FOREIGN KEY (requirement_id) REFERENCES requirements(id)
      );
    "
  );

    foreach ($tables as $tableName => $sql) {
        createTable($pdo, $tableName, $sql);
    }
}
  
?>

