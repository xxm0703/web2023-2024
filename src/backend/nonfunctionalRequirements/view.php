<?php

require_once 'controller.php';

class NonfunctionalRequirementsView
{
  private $controller;

  public function __construct()
  {
    $this->controller = new NonfunctionalRequirementsController();
  }

  public function fetchAllNonfunctionalRequirements()
  {
    $requirements = $this->controller->fetchAllNonfunctionalRequirements();
    echo json_encode($requirements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function fetchNonfunctionalRequirementById($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $requirement = $this->controller->fetchNonfunctionalRequirementById($id);
    echo json_encode($requirement, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function addNonfunctionalRequirement()
  {
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = $this->controller->addNonfunctionalRequirement($postData);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  public function removeNonunctionalRequirement($id)
  {
    if (!is_numeric($id)) {
      echo "Invalid id";
      return;
    }
    $result = $this->controller->removeNonfunctionalRequirementById($id);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}