<?php

require_once 'controller.php';

class FunctionalRequirementsView
{
  private $controller;

  public function __construct()
  {
    $this->controller = new FunctionalRequirementsController();
  }

  public function fetchAllFunctionalRequirements()
  {
    $requirements = [];
    if (isset($_GET['projectId'])) {
      $projectId = $_GET['projectId'];
      $requirements = $this->controller->fetchFunctionalRequirementsForProject($projectId);
    } else {
      $requirements = $this->controller->fetchAllFunctionalRequirements();
    }
    echo json_encode($requirements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function fetchFunctionalRequirementById($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $requirement = $this->controller->fetchFunctionalRequirementById($id);
    echo json_encode($requirement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function addFunctionalRequirement()
  {
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = $this->controller->addFunctionalRequirement($postData);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function removeFunctionalRequirement($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->removeFunctionalRequirementById($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
