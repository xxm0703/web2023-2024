<?php

require_once 'Db.php';
require_once 'routing.php';
require_once(__DIR__ . '/functionalRequirements/view.php');
require_once(__DIR__ . '/nonfunctionalRequirements/view.php');
require_once(__DIR__ . '/users/view.php');
require_once(__DIR__ . '/projects/view.php');

class Applicaiton
{
  private function addRoutes($router)
  {
    $frView = new FunctionalRequirementsView();
    $router->addRoute('GET', '#^/functionalRequirements(?:/\?.*)?$#', [$frView, 'fetchAllFunctionalRequirements'], true);
    $router->addRoute('GET', '#^/functionalRequirements/(\d+)$#', [$frView, 'fetchFunctionalRequirementById'], true);
    $router->addRoute('POST', '#^/functionalRequirements/?$#', [$frView, 'addFunctionalRequirement'], true);
    $router->addRoute('DELETE', '#^/functionalRequirements/(\d+)$#', [$frView, 'removeFunctionalRequirement'], true);

    $nfrView = new NonfunctionalRequirementsView();
    $router->addRoute('GET', '#^/nonfunctionalRequirements(?:/\?.*)?$#', [$nfrView, 'fetchAllNonfunctionalRequirements'], true);
    $router->addRoute('GET', '#^/nonfunctionalRequirements/(\d+)$#', [$nfrView, 'fetchNonfunctionalRequirementById'], true);
    $router->addRoute('POST', '#^/nonfunctionalRequirements/?$#', [$nfrView, 'addNonfunctionalRequirement'], true);
    $router->addRoute('DELETE', '#^/nonfunctionalRequirements/(\d+)$#', [$nfrView, 'removeNonfunctionalRequirement'], true);

    $userView = new UsersView();
    $router->addRoute('GET', '#^/session/?$#', [$userView, 'checkLoginStatus']);
    $router->addRoute('POST', '#^/login/?$#', [$userView, 'login']);
    $router->addRoute('POST', '#^/register/?$#', [$userView, 'register']);
    $router->addRoute('DELETE', '#^/logout/?$#', [$userView, 'logout'], true);

    $projectsView = new ProjectsView();
    $router->addRoute('GET', '#^/projects/?$#', [$projectsView, 'fetchAllProjects'], true);
    $router->addRoute('GET', '#^/projects/(\d+)$#', [$projectsView, 'fetchProjectById'], true);
    $router->addRoute('POST', '#^/projects/?$#', [$projectsView, 'addProject'], true);
    $router->addRoute('POST', '#^/projects/import/?$#', [$projectsView, 'importProjects'], true);
    $router->addRoute('DELETE', '#^/projects/(\d+)$#', [$projectsView, 'removeProject'], true);
    $router->addRoute('GET', '#^/projects/wbs/(\d+)$#', [$projectsView, 'exportWBS'], true);
    $router->addRoute('GET', '#^/projects/mindmap/(\d+)$#', [$projectsView, 'exportMindMap'], true);
    $router->addRoute('GET', '#^/projects/gantt/(\d+)$#', [$projectsView, 'exportGantt'], true);
  }

  public function run()
  {
    $database = new Db();
    $database->createTables();
    $router = new Router();
    $this->addRoutes($router);
    $router->handleRequest();
  }
}

$app = new Applicaiton();
$app->run();
