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

  public function exportGantt($projectId)
  {
    $projectWithFunctionalRequirements = $this->fetchFunctionalRequirements($projectId);

    if (empty($projectWithFunctionalRequirements)) {
      return "";
    }

    $projectName = $projectWithFunctionalRequirements[0]->projectName;
    $startDate = $projectWithFunctionalRequirements[0]->startDate;

    $ganttString = "@startgantt\n";
    $ganttString .= "Project starts the " . $startDate . "\n";
    $ganttString .= "printscale weekly\n";
    $ganttString .= "--" . $projectName . "--\n";

    foreach ($projectWithFunctionalRequirements as $pfr) {
      $ganttString .= '[' . $pfr->requirementName . "] requires " . $pfr->estimate . " days\n";
    }

    $ganttString .= "@endgantt\n";
    return $ganttString;
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
      $result .= '**** ' . "Estimate: " . $pfr->estimate . " days\n";
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

  private function genSymbol($symb, $times)
  {
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
        'SELECT `p`.`name` as project_name, `p`.`start_date`, `r`.`name` as requirement_name, `r`.`description`, `r`.`priority`, `fr`.`estimate`
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
  public function fetchAllProjects(): array
  {
    try {
      $connection = $this->db->getConnection();
      $userId = $_SESSION['userId'];
      $select = $connection->prepare(
        'SELECT `p`.`id`, `p`.`name`, `p`.`start_date`, `p`.`user_id`, `p`.`created_at`
        from `projects` `p`
        where `p`.`user_id` = ?'
      );
      $select->execute([$userId]);
      $rows = $select->fetchAll();

      $projects = [];
      foreach ($rows as $row) {
        $projects[] = Project::fromAssoc($row);
      }

      return $projects;
    } catch (Exception $e) {
      echo "" . $e->getMessage();
      return [];
    }
  }

  private function fetchProjectById($id): ?Project
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `p`.`id`, `p`.`name`, `p`.`start_date`, `p`.`user_id`, `p`.`created_at`
        from `projects` `p`
        where `p`.`id` = ?'
      );
      $select->execute([$id]);
      $project = $select->fetch();

      if ($project === false) {
        return null;
      }

      return Project::fromAssoc($project);
    } catch (Exception $e) {
      echo "" . $e->getMessage();
    }
  }

  public function addProject($data): bool
  {
    $connection = $this->db->getConnection();
    $userId = $_SESSION['userId'];
    if (!$userId) {
      return false;
    }

    return $this->insertProject($data) != -1;
  }
  public function importProjects($data)
  {
    $userId = $_SESSION['userId'];    
    if (!$userId) {
      return false;
    }
    foreach ($data as $project) {
      $projectId = $this->insertProject($project);
      $this->insertFunctional($project['functional'], $projectId);
      $this->insertNonfunctional($project['nonfunctional'], $projectId);
    }
    return true;
  }
  public function removeProjectById($id) : bool
  {
    $project = $this->fetchProjectById($id);
    $userId = $_SESSION['userId'];
    $isAuthorized = $project->getOwnerId() == $userId;

    if (!$isAuthorized) {
      return false;
    }

    try {

      $connection = $this->db->getConnection();

      $delete = $connection->prepare('DELETE from `projects` `p` where `p`.`id` = ?');
      $delete->execute([$id]);

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  } 

  private function insertNonfunctional($data, $projectId) : bool {
    $connection = $this->db->getConnection();
    foreach ($data as $requirement) {
      try {
        $connection->beginTransaction();
  
        $insert = $connection->prepare(
          'INSERT INTO `requirements` (`name`, `description`, `priority`, `project_id`) VALUES (:name, :description, :priority, :projectId)'
        );
        $result = $insert->execute([
          'name' => $requirement['name'],
          'description' => $requirement['description'],
          'priority' => $requirement['priority'],
          'projectId' => $projectId
        ]);
  
        $id = $connection->lastInsertId();
        $nonfunctionalRequirementsInsert = $connection->prepare(
          'INSERT INTO `nonfunctional_requirements` (`unit`, `value`,`requirement_id`) 
          VALUES (:unit, :value, :requirementId)'
        );
        $secondInsertResult = $nonfunctionalRequirementsInsert->execute([
          'unit' => $requirement['unit'],
          'value' => $requirement['value'],
          'requirementId' => $id,
        ]);
  
        if (!$result || !$secondInsertResult) {
          throw new Exception('Error executing INSERT statement');
        }
        $connection->commit();
  
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        $connection->rollback();
  
        return false;
      }
    }
    return true;
  }


  private function insertFunctional($data, $projectId) : bool {
    $connection = $this->db->getConnection();
    foreach ($data as $requirement) {
      try {
        $connection->beginTransaction();
  
        $insert = $connection->prepare(
          'INSERT INTO `requirements` (`name`, `description`, `priority`, `project_id`) VALUES (:name, :description, :priority, :projectId)'
        );
        $result = $insert->execute([
          'name' => $requirement['name'],
          'description' => $requirement['description'],
          'priority' => $requirement['priority'],
          'projectId' => $projectId
        ]);
  
        $id = $connection->lastInsertId();
        $functionalRequirementsInsert = $connection->prepare(
          'INSERT INTO `functional_requirements` (`requirement_id`, `estimate`) VALUES (:id, :estimate)'
        );
        $secondInsertResult = $functionalRequirementsInsert->execute([
          'id' => $id,
          'estimate' => $requirement['estimate']
        ]);
  
        if (!$result || !$secondInsertResult) {
          throw new Exception('Error executing INSERT statement');
        }
        $connection->commit();
  
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        $connection->rollback();
  
        return false;
      }
    }
    return true;

  }

  private function insertProject($data): int {
    $userId = $_SESSION['userId'];
    $connection = $this->db->getConnection();

    try {
      $connection->beginTransaction();

      $insert = $connection->prepare(
        'INSERT INTO `projects` (`name`, `start_date`, `user_id`) VALUES (:name, :start_date, :user_id)'
      );

      $result = $insert->execute([
        'name' => $data['name'],
        'start_date' => $data['startDate'],
        'user_id' => $userId
      ]);
      $id = $connection->lastInsertId();

      if (!$result) {
        throw new Exception('Error executing INSERT statement');
      }
      $connection->commit();

      return $id;      ;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      $connection->rollback();

      return -1;
    }
  }
}
