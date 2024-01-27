<?php

require_once 'controller.php';

class UsersView
{
  private $controller;

  public function __construct()
  {
    $this->controller = new UsersController();
  }

  public function checkLoginStatus() {
    $result = $this->controller->checkLoginStatus();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function register()
  {
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = $this->controller->register($postData);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function login()
  {
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = $this->controller->login($postData);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function logout()
  {
    $result = $this->controller->logout();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
