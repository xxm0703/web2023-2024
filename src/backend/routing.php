<?php

class Router
{
  private $routes = [];

  public function addRoute($method, $pattern, $handler)
  {
    $this->routes[] = [$method, $pattern, $handler];
  }

  public function handleRequest()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];

    foreach ($this->routes as $route) {
      list($method, $pattern, $handler) = $route;

      if ($method === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches);

        call_user_func_array($handler, $matches);
        return;
      }
    }

    echo "404 Not Found";
  }
}
