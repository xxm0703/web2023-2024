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

  public function exportMindMap($projectId)
  {
    return $this->generateString($projectId, '@startmindmap', '@endmindmap');
  }

  public function exportWBS($projectId)
  {
    return $this->generateString($projectId, '@startwbs', '@endwbs');
  }

  private function generateString($projectId, $start, $end)
  {
    $projectWithFunctionalRequirements = $this->fetchFunctionalRequirements($projectId);
    $projectWithNonFunctionalRequirements = $this->fetchNonFunctionalRequirements($projectId);

    if (empty($projectWithFunctionalRequirements) && empty($projectWithNonFunctionalRequirements)) {
      return "";
    }

    $projectName = $projectWithFunctionalRequirements[0]->projectName;

    $result = $start . "\n";
    $result .= '* ' . $projectName . "\n";
    $result .= '** Functional Requirements' . "\n";
    foreach ($projectWithFunctionalRequirements as $pfr) {
      $result .= '*** ' . $pfr->requirementName . "\n";
      if ($pfr->requirementDescription !== null) {
        $result .= '**** ' . $pfr->requirementDescription . "\n";
      }
      $result .= '**** ' . "Priority: " . $pfr->priority . "\n";
    }

    $symb = '*';
    if ($start === '@startmindmap') {
      $symb = '-';
    }

    $result .= $this->genSymbol($symb, 2) . 'Non Functional Requirements' . "\n";
    foreach ($projectWithNonFunctionalRequirements as $pnfr) {
      $result .= $this->genSymbol($symb, 3) . $pnfr->requirementName . "\n";
      if ($pnfr->requirementDescription !== null) {
        $result .= $this->genSymbol($symb, 4) . $pnfr->requirementDescription . "\n";
      }
      $result .= $this->genSymbol($symb, 4) . "Priority: " . $pnfr->priority . "\n";

      $result .= $this->genSymbol($symb, 4) . "Measurement: " . $pnfr->unit . "\n";
      $result .= $this->genSymbol($symb, 4) . "Value: " . $pnfr->value . "\n";
    }

    $result .= $end;
    return $result;
  }

  private function genSymbol($symb, $times) {
    $result = "";
    for ($i = 0; $i < $times; $i++) {
      $result .= $symb;  
    }
    $result .= ' ';
    return $result;
  }

  private function fetchFunctionalRequirements($projectId): array
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `p`.`name` as project_name, `r`.`name` as requirement_name, `r`.`description`, `r`.`priority`
        FROM `projects` `p` 
        left join `requirements` `r` on `r`.`project_id` = `p`.`id`
        join `functional_requirements` `fr` on `fr`.`requirement_id` = `r`.`id`
        WHERE `p`.`id` = :projectId and `p`.`user_id` = :userId
        ORDER BY `r`.`priority` DESC'
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
        'SELECT `p`.`name` as project_name, `r`.`name` as requirement_name, `r`.`description`, `r`.`priority`, `nfr`.`unit`, `nfr`.`value`
        FROM `projects` `p` 
        left join `requirements` `r` on `r`.`project_id` = `p`.`id`
        join `nonfunctional_requirements` `nfr` on `nfr`.`requirement_id` = `r`.`id`
        WHERE `p`.`id` = :projectId and `p`.`user_id` = :userId
        ORDER BY `r`.`priority` DESC'
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