<?php

require_once 'Db.php'; 
require_once 'createTables.php'; 

header('Access-Control-Allow-Origin: *');  // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
function getUserById($userId)
{
    $database = new Db();
    $pdo = $database->getConnection();

    $stmt = $pdo->prepare("SELECT id, name, created_at FROM projects");
    $stmt->execute();
    $projectsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($projectsData);
}
getUserById(2)
?>
