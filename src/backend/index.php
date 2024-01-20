<?php

require_once 'Db.php'; 

function getUserById($userId)
{
    $database = new Db();
    $connection = $database->getConnection();

    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $connection->prepare($sql);

echo "Hello world!<br>";
    // Validating the parameter because it is comming from the frontend
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    $stmt->execute();

    $user = $stmt->fetch();

    return $user !== false ? $user : null;
}
getUserById(2)
?>
