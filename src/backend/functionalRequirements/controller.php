<?php

require_once 'Db.php';
require_once 'model.php';

class FunctionalRequirementsController
{
  private $db;

  public function __construct()
  {
    $this->db = new Db();
  }

  public function fetchAllFunctionalRequirements(): array
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `r`.`id`, `r`.`name`, `r`.`description`, `r`.`priority`, `r`.`project_id`, `r`.`created_at`
        from `requirements` `r`
        join `functional_requirements` `fr` on `fr`.`requirement_id` = `r`.`id`; '
      );
      $select->execute([]);
      $rows = $select->fetchAll();

      $requirements = [];
      foreach ($rows as $row) {
        $requirements[] = FunctionalRequirement::fromAssoc($row);
      }

      return $requirements;
    } catch (Exception $e) {
      echo "" . $e->getMessage();
      return [];
    }
  }

  public function fetchFunctionalRequirementById($id): ?FunctionalRequirement
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `r`.`id`, `r`.`name`, `r`.`description`, `r`.`priority`, `r`.`project_id`, `r`.`created_at` 
        from `requirements` `r`
        join `functional_requirements` `fr` on `fr`.`requirement_id` = `r`.`id`
        where `r`.`id` = ?'
      );
      $select->execute([$id]);
      $requirement = $select->fetch();

      if ($requirement === false) {
        return null;
      }

      return FunctionalRequirement::fromAssoc($requirement);
    } catch (Exception $e) {
      echo "" . $e->getMessage();
    }
  }

  public function addFunctionalRequirement($data): bool
  {
    try {
      $connection = $this->db->getConnection();

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
      $functionalRequirementsInsert = $connection->prepare(
        'INSERT INTO `functional_requirements` (`requirement_id`) VALUES (?)'
      );
      $functionalRequirementsInsert->execute([$id]);

      if (!$result) {
        throw new Exception('Error executing INSERT statement');
      }

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function removeFunctionalRequirementById($id): bool
  {
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

}