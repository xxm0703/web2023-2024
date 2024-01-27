<?php

session_start();

class Router
{
  private $routes = [];

  public function addRoute($method, $pattern, $handler, $requiresAuth = false)
  {
    $this->routes[] = [$method, $pattern, $handler, $requiresAuth];
  }

  public function handleRequest()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];

    foreach ($this->routes as $route) {
      list($method, $pattern, $handler, $requiresAuth) = $route;

      if ($method === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches);

        if ($requiresAuth && !isset($_SESSION['email'])) {
          echo "401 Unauthorized";
          return;
        }

        call_user_func_array($handler, $matches);
        return;
      }
    }

    echo "404 Not Found";
  }
}
