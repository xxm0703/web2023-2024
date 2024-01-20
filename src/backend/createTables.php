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
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			created_at TIMESTAMP
		);
		",
		"requirements" => "
		CREATE TABLE requirements (
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			description VARCHAR(255),
			project_id INTEGER NOT NULL,
			created_at TIMESTAMP,
			FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
		);
		",
		"functional_requirements" => "
		CREATE TABLE functional_requirements (
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			requirement_id INTEGER NOT NULL,
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
		",
		"hash_tags" => "
		CREATE TABLE hash_tags (
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) UNIQUE NOT NULL,
			created_at TIMESTAMP
		);
		",
		"hash_tags_requirements" => "
		CREATE TABLE hash_tags_requirements (
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			hash_tag_id INTEGER NOT NULL,
			requirement_id INTEGER NOT NULL,
			FOREIGN KEY (hash_tag_id) REFERENCES hash_tags(id) ON DELETE CASCADE,
			FOREIGN KEY (requirement_id) REFERENCES requirements(id) ON DELETE CASCADE
		);
		"
	);

    foreach ($tables as $tableName => $sql) {
        createTable($pdo, $tableName, $sql);
    }
}
  
?>

