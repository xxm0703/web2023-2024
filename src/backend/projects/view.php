<?php

require_once 'controller.php';

class ProjectsView
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

  public function exportGantt($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->exportGantt($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function fetchAllProjects()
  {
    $projects = $this->controller->fetchAllProjects();
    echo json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function fetchProjectById($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $project = $this->controller->fetchProjectById($id);
    echo json_encode($project, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function addProject()
  {
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = $this->controller->addProject($postData);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function removeProject($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->removeProjectById($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
