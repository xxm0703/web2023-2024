<?php

require_once 'Db.php';
require_once 'model.php';

session_start();

class UsersController
{
  private $db;

  public function __construct()
  {
    $this->db = new Db();
  }

  public function register($data): bool
  {
    try {
      $areValidCredentials = $this->validateCredentials($data['email'], $data['password']);
      if (!$areValidCredentials) {
        echo "Invalid credentials\n";
        return false;
      }

      $connection = $this->db->getConnection();

      $selectStatement = $connection->prepare('SELECT id FROM `users` WHERE email = ?');
      $selectStatement->execute([$data['email']]);

      if ($selectStatement->fetch()) {
        return false;
      }

      $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

      $insertStatement = $connection->prepare('INSERT INTO `users` (`email`, `password`) VALUES(:email, :password)');
      $insertStatement->execute([
        'email' => $data['email'],
        'password' => $hashedPassword,
      ]);

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function checkLoginStatus(): bool
  {
    return isset($_SESSION['email']);
  }

  public function login($data)
  {
    $areValidCredentials = $this->validateCredentials($data['email'], $data['password']);
    if (!$areValidCredentials) {
      echo "Invalid credentials\n";
      return false;
    }

    $connection = $this->db->getConnection();

    $selectStatement = $connection->prepare('SELECT * FROM `users` WHERE email = ?');
    $selectStatement->execute([$data['email']]);

    $user = $selectStatement->fetch();
    if (!$user) {
      return false;
    }

    $loginSuccessful = password_verify($data['password'], $user['password']);

    if ($loginSuccessful) {
      $_SESSION['email'] = $data['email'];
      $_SESSION['userId'] = $user['id'];
    }

    return $loginSuccessful;
  }

  public function logout(): bool
  {
    return session_destroy();
  }

  private function validateCredentials($email, $password): bool
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }

    if (strlen($password) < 6) {
      return false;
    }

    return true;
  }

}