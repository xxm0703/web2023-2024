<?php
session_start();

if (!isset($_SESSION['email'])) {
  header('Location: /frontend/pages/login.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/requirementList.css" />
    <script type="module" src="../js/requirementList.js" defer></script>
    <title>Requirements List</title>
  </head>
  <body>
    <h2>Requirements list</h2>
    <ul id="requirements-list"></ul>
  </body>
</html>
