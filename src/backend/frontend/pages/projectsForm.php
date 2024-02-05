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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/projectsForm.css" />
  <script type="module" src="../js/projectsForm.js" defer></script>
  <script type="module" src="../js/navbar.js" defer></script>
  <title>Create Project</title>
</head>
<body>
<header>
    <input type="button" value="Dashboard" id="dashboard-button" />
    <input type="button" value="Log out" id="logout-button" />
  </header>
  <section>
    <h2>Create Project</h2>
    <form id="projects-form" method="POST">
      <label for="projectName">Project Name:</label>
      <input type="text" id="projectName" name="projectName" required>
      
      <label for="projectStartDate">Start Date:</label>
      <input type="date" id="projectStartDate" name="projectStartDate" required>


      <button type="submit">Create Project</button>
    </form>
  </section>

  <section>
    <h2>Projects list</h2>
    <ul id="projects-list"></ul>
  </section>
</body>
</html>