<?php

require_once 'Db.php';
require_once 'model.php';

session_start();

class ProjectsController
{
  private $db;

  public function __construct()
  {
    $this->db = new Db();
  }

  public function exportWBS($projectId)
  {
    $projectWithFunctionalRequirements = $this->fetchFunctionalRequirements($projectId);
    $projectWithNonFunctionalRequirements = $this->fetchNonFunctionalRequirements($projectId);

    if (empty($projectWithFunctionalRequirements) && empty($projectWithNonFunctionalRequirements)) {
      return "";
    }

    $projectName = $projectWithFunctionalRequirements[0]->projectName;

    $wbsString = '@startwbs' . "\n";
    $wbsString .= '* ' . $projectName . "\n";
    $wbsString .= '** Functional Requirements' . "\n";
    foreach ($projectWithFunctionalRequirements as $pfr) {
      $wbsString .= '*** ' . $pfr->requirementName . "\n";
      if ($pfr->requirementDescription !== null) {
        $wbsString .= '**** ' . $pfr->requirementDescription . "\n";
      }
    }

    $wbsString .= '** Non Functional Requirements' . "\n";
    foreach ($projectWithNonFunctionalRequirements as $pnfr) {
      $wbsString .= '*** ' . $pnfr->requirementName . "\n";
      if ($pnfr->requirementDescription !== null) {
        $wbsString .= '**** ' . $pnfr->requirementDescription . "\n";
      }

      $wbsString .= '**** ' . "Measured with " . $pnfr->unit . "\n";
      $wbsString .= '**** ' . "Value: " . $pnfr->value . "\n";
    }

    $wbsString .= '@endwbs';
    return $wbsString;
  }

  private function fetchFunctionalRequirements($projectId): array
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `p`.`name` as project_name, `r`.`name` as requirement_name, `r`.`description`
        FROM `projects` `p` 
        left join `requirements` `r` on `r`.`project_id` = `p`.`id`
        join `functional_requirements` `fr` on `fr`.`requirement_id` = `r`.`id`
        WHERE `p`.`id` = :projectId and `p`.`user_id` = :userId'
      );
      $select->execute([
        'projectId' => $projectId,
        'userId' => $_SESSION['userId'],
      ]);
      $rows = $select->fetchAll();

      $pfrArray = [];
      foreach ($rows as $row) {
        $pfrArray[] = ProjectWithFunctionalRequirements::fromAssoc($row);
      }

      return $pfrArray;
    } catch (Exception $e) {
      return [];
    }
  }

  private function fetchNonFunctionalRequirements($projectId): array
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `p`.`name` as project_name, `r`.`name` as requirement_name, `r`.`description`, `nfr`.`unit`, `nfr`.`value`
        FROM `projects` `p` 
        left join `requirements` `r` on `r`.`project_id` = `p`.`id`
        join `nonfunctional_requirements` `nfr` on `nfr`.`requirement_id` = `r`.`id`
        WHERE `p`.`id` = :projectId and `p`.`user_id` = :userId'
      );
      $select->execute([
        'projectId' => $projectId,
        'userId' => $_SESSION['userId'],
      ]);
      $rows = $select->fetchAll();

      $pnfrArray = [];
      foreach ($rows as $row) {
        $pnfrArray[] = ProjectWithNonFunctionalRequirements::fromAssoc($row);
      }

      return $pnfrArray;
    } catch (Exception $e) {
      return [];
    }
  }

}