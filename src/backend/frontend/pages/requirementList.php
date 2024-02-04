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
  <script type="module" src="../js/navbar.js" defer></script>
  <title>Requirements List</title>
</head>

<body>
  <header>
    <input type="button" value="Dashboard" id="dashboard-button" />
    <input type="button" value="Log out" id="logout-button" />
  </header>
  <section>
    <h2>Requirements list</h2>
    <form id="requirement-form">
      <label for="projectId">Project ID:</label>
      <input required class="input" type="number" id="projectId" placeholder="Enter project id" />
      <button id="load-req-button" type=submit>Load Requirements</button>
    </form>
    <label id="filters-label" for="filter-checkboxes">Filter by:</label>
    <div id="filter-checkboxes">
      <label>
        <input type="checkbox" id="func-checkbox" checked /> Functional
      </label>
      <label>
        <input type="checkbox" id="non-func-checkbox" checked /> Non functional
      </label>
    </div>
    <ul id="requirement-list"></ul>
  </section>
</body>

</html>