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
  <link rel="stylesheet" href="../styles/export.css" />
  <script type="module" src="../js/export.js" defer></script>
  <script type="module" src="../js/navbar.js" defer></script>
  <title>Export</title>
</head>

<body>
  <header>
    <input type="button" value="Dashboard" id="dashboard-button" />
    <input type="button" value="Log out" id="logout-button" />
  </header>
  <section>
    <h2>Export project requirements</h2>
    <form id="export-form">
      <input required type="number" id="project-id" placeholder="Enter project id" />
      <select id="export-type">
        <option value="wbs">Work Breakdown Structure (WBS)</option>
        <option value="mindmap">MindMap</option>
        <option value="gantt">Gantt Chart</option>
      </select>
      <button id="export-button" type="submit">Export</button>
    </form>
    <div id="container">Generated string:</div>
  </section>
</body>

</html>