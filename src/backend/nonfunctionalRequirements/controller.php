<?php

require_once 'Db.php';
require_once 'model.php';

session_start();

class NonfunctionalRequirementsController
{
  private $db;

  public function __construct()
  {
    $this->db = new Db();
  }

  public function fetchAllNonfunctionalRequirements(): array
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT  `r`.`id`, `r`.`name`, `r`.`description`, `r`.`priority`, `r`.`project_id`, `r`.`created_at`, `nfr`.`unit`, `nfr`.`value`
        from `requirements` `r` 
        join `nonfunctional_requirements` `nfr` on `nfr`.`requirement_id` = `r`.`id`'
      );
      $select->execute([]);
      $rows = $select->fetchAll();

      $requirements = [];
      foreach ($rows as $row) {
        $requirements[] = NonfunctionalRequirement::fromAssoc($row);
      }

      return $requirements;

    } catch (Exception $e) {
      echo "" . $e->getMessage();
      return [];
    }
  }

  public function fetchAllNonfunctionalRequirementsForProject($projectId): array
  {
    try {
      $connection = $this->db->getConnection();

      $isAuthorized = $this->isProjectOwnedByUser($projectId);
      if (!$isAuthorized) {
        echo "Not authorized";
        return [];
      }

      $select = $connection->prepare(
        'SELECT  `r`.`id`, `r`.`name`, `r`.`description`, `r`.`priority`, `r`.`project_id`, `r`.`created_at`, `nfr`.`unit`, `nfr`.`value`
        from `requirements` `r` 
        join `nonfunctional_requirements` `nfr` on `nfr`.`requirement_id` = `r`.`id`
        where `r`.`project_id` = ?
        order by `r`.`created_at` DESC'
      );
      $select->execute([$projectId]);
      $rows = $select->fetchAll();

      $requirements = [];
      foreach ($rows as $row) {
        $requirements[] = NonfunctionalRequirement::fromAssoc($row);
      }

      return $requirements;

    } catch (Exception $e) {
      echo "" . $e->getMessage();
      return [];
    }
  }

  public function fetchNonfunctionalRequirementById($id): ?NonfunctionalRequirement
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `r`.`id`, `r`.`name`, `r`.`description`, `r`.`priority`, `r`.`project_id`, `r`.`created_at`, `nfr`.`unit`, `nfr`.`value`
        from `requirements` `r` 
        join `nonfunctional_requirements` `nfr` on `nfr`.`requirement_id` = `r`.`id`
        where `r`.`id` = ?'
      );
      $select->execute([$id]);
      $requirement = $select->fetch();

      if ($requirement === false) {
        return null;
      }

      return NonfunctionalRequirement::fromAssoc($requirement);
    } catch (Exception $e) {
      echo "" . $e->getMessage();
    }
  }

  public function addNonfunctionalRequirement($data): bool
  {
    $isAuthorized = $this->isProjectOwnedByUser($data['projectId']);
    if (!$isAuthorized) {
      return false;
    }
    $connection = $this->db->getConnection();

    try {
      $connection->beginTransaction();

      $insert = $connection->prepare(
        'INSERT INTO `requirements` (`name`, `description`, `priority`, `project_id`) VALUES (:name, :description, :priority, :projectId)'
      );
      $result = $insert->execute([
        'name' => $data['name'],
        'description' => $data['description'],
        'priority' => $data['priority'],
        'projectId' => $data['projectId']
      ]);

      $id = $connection->lastInsertId();
      $nonfunctionalRequirementsInsert = $connection->prepare(
        'INSERT INTO `nonfunctional_requirements` (`unit`, `value`,`requirement_id`) 
        VALUES (:unit, :value, :requirementId)'
      );
      $secondInsertResult = $nonfunctionalRequirementsInsert->execute([
        'unit' => $data['unit'],
        'value' => $data['value'],
        'requirementId' => $id,
      ]);

      if (!$result || !$secondInsertResult) {
        throw new Exception('Error executing INSERT statement');
      }
      $connection->commit();

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      $connection->rollback();

      return false;
    }
  }

  public function removeNonfunctionalRequirementById($id): bool
  {
    $requirement = $this->fetchNonfunctionalRequirementById($id);
    $isAuthorized = $this->isProjectOwnedByUser($requirement->projectId);
    if (!$isAuthorized) {
      return false;
    }

    try {

      $connection = $this->db->getConnection();

      $delete = $connection->prepare('DELETE from `requirements` `r` where `r`.`id` = ?');
      $delete->execute([$id]);

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  private function isProjectOwnedByUser($projectId): bool
  {
    try {
      $connection = $this->db->getConnection();
      $userId = $_SESSION['userId'];

      $select = $connection->prepare(
        'SELECT *
        from `projects` `p` 
        where `p`.`user_id` = :userId and `p`.`id` = :projectId'
      );
      $select->execute([
        'userId' => $userId,
        'projectId' => $projectId
      ]);
      $project = $select->fetch();

      if ($project === false) {
        return false;
      }

      return true;
    } catch (Exception $e) {
      echo "" . $e->getMessage();
      return false;
    }
  }
}
