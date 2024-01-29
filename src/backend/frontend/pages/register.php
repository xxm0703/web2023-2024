<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link href="../styles/register.css" rel="stylesheet" />
  <script type="module" src="../js/register.js" defer></script>
</head>

<body>
  <form id="register-form">
    <input id="email" type="email" name="email" placeholder="Email" />
    <input id="password" type="password" name="password" placeholder="Password" />
    <button type="submit">Register</button>
  </form>
</body>

</html>