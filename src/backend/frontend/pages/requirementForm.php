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
  <link rel="stylesheet" href="../styles/requirementForm.css" />
  <script type="module" src="../js/requirementForm.js" defer></script>
  <script type="module" src="../js/navbar.js" defer></script>
  <title>Requirements Form</title>
</head>

<body>
  <header>
    <input type="button" value="Dashboard" id="dashboard-button" />
    <input type="button" value="Log out" id="logout-button" />
  </header>
  <section>
    <h2>Add New Requirement</h2>
    <form id="requirement-form">
      <input required class="input" type="text" id="requirementName" placeholder="Enter requirement name" />
      <input class="input" type="text" id="requirementDescription" placeholder="Enter requirement description" />
      <input required class="input" type="number" id="requirementPriority" placeholder="Enter priority (1-5)" min="1" max="5" />
      <input required class="input" type="number" id="requirementProjectId" placeholder="Enter project id" />
      <input class="input" type="text" id="requirementUnit" placeholder="Enter requirement unit" />
      <input class="input" type="text" id="requirementValue" placeholder="Enter requirement value" />
      <label for="requirementType">Requirement type:</label>
      <select id="requirementType">
        <option value="functional">Functional</option>
        <option value="nonfunctional">Non-Functional</option>
      </select>
      <button id="add-req-button" type=submit>Add Requirement</button>
    </form>
  </section>
</body>

</html>