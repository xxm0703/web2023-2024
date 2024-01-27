<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="../styles/login.css" rel="stylesheet" />
  <script type="module" src="../js/login.js" defer></script>
</head>

<body>
  <form id="login-form">
    <input id="email" type="email" name="email" placeholder="Email" />
    <input id="password" type="password" name="password" placeholder="Password" />
    <button type="submit">Log in</button>
  </form>
</body>

</html>