<?php
session_start();

if (!isset($_SESSION['email'])) {
  header('Location: /frontend/pages/login.php');
  exit;
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../styles/dashboard.css" />
  <script type="module" src="../js/navbar.js" defer></script>
  <title>Dashboard</title>
</head>

<body>
  <header>
    <input type="button" value="Dashboard" id="dashboard-button" />
    <input type="button" value="Log out" id="logout-button" />
  </header>
  <section id="app">
    <ul>
      <li>
        <a href="/frontend/pages/requirementForm.php">Add Requirement</a>
      </li>
      <li>
        <a href="/frontend/pages/requirementList.php">Requirements List</a>
      </li>
      <li>
        <a href="/frontend/pages/export.php">Export requirements</a>
      </li>
    </ul>
  </section>
</body>

</html>