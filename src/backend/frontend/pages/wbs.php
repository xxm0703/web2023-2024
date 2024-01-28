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
  <script type="module" src="../js/wbsExport.js" defer></script>
  <title>WBS Export</title>
</head>

<body>
  <h2>Export as WBS</h2>
  <form id="wbs-form">
    <input type="number" id="wbs-project-id" placeholder="Enter project id" />
    <button id="wbs-export-button" type="submit">Export</button>
  </form>
  <div id="wbs-container">WBS string:</div>
</body>

</html>