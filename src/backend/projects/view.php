<?php

require_once 'controller.php';

class ProjetsView
{
  private $controller;

  public function __construct()
  {
    $this->controller = new ProjectsController();
  }

  public function exportWBS($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->exportWBS($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function exportMindMap($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->exportMindMap($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
