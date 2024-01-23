<?php

require_once 'Db.php';
require_once 'routing.php';
require_once(__DIR__ . '/functionalRequirements/view.php');
require_once(__DIR__ . '/nonfunctionalRequirements/view.php');

class Applicaiton
{
  private function addRoutes($router)
  {
    $frView = new FunctionalRequirementsView();
    $router->addRoute('GET', '#^/functionalRequirements/?$#', [$frView, 'fetchAllFunctionalRequirements']);
    $router->addRoute('GET', '#^/functionalRequirements/(\d+)$#', [$frView, 'fetchFunctionalRequirementById']);
    $router->addRoute('POST', '#^/functionalRequirements/?$#', [$frView, 'addFunctionalRequirement']);
    $router->addRoute('DELETE', '#^/functionalRequirements/(\d+)$#', [$frView, 'removeFunctionalRequirement']);

    $nfrView = new NonfunctionalRequirementsView();
    $router->addRoute('GET', '#^/nonfunctionalRequirements/?$#', [$nfrView, 'fetchAllNonfunctionalRequirements']);
    $router->addRoute('GET', '#^/nonfunctionalRequirements/(\d+)$#', [$nfrView, 'fetchNonfunctionalRequirementById']);
    $router->addRoute('POST', '#^/nonfunctionalRequirements/?$#', [$nfrView, 'addNonfunctionalRequirement']);
    $router->addRoute('DELETE', '#^/nonfunctionalRequirements/(\d+)$#', [$nfrView, 'removeNonfunctionalRequirement']);
  }

  public function run()
  {
    $database = new Db();
    $router = new Router();
    $this->addRoutes($router);
    $router->handleRequest();
  }
}

$app = new Applicaiton();
$app->run();
