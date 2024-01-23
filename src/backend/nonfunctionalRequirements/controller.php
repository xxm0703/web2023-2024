<?php

require_once 'Db.php';
require_once 'model.php';

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
        'SELECT  `r`.`id`, `r`.`name`, `r`.`description` , `r`.`project_id`, `r`.`created_at`, `nfr`.`unit`, `nfr`.`value`
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

  public function fetchNonfunctionalRequirementById($id): ?NonfunctionalRequirement
  {
    try {
      $connection = $this->db->getConnection();

      $select = $connection->prepare(
        'SELECT `r`.`id`, `r`.`name`, `r`.`description` , `r`.`project_id`, `r`.`created_at`, `nfr`.`unit`, `nfr`.`value`
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
    try {
      $connection = $this->db->getConnection();

      $insert = $connection->prepare(
        'INSERT INTO `requirements` (`name`, `description`, `project_id`) VALUES (:name, :description, :projectId)'
      );
      $result = $insert->execute([
        'name' => $data['name'],
        'description' => $data['description'],
        'projectId' => $data['projectId']
      ]);

      $id = $connection->lastInsertId();
      $functionalRequirementsInsert = $connection->prepare(
        'INSERT INTO `nonfunctional_requirements` (`unit`, `value`,`requirement_id`) 
        VALUES (:unit, :value, :requirementId)'
      );
      $functionalRequirementsInsert->execute([
        'unit' => $data['unit'],
        'value' => $data['value'],
        'requirementId' => $id,
      ]);

      if (!$result) {
        throw new Exception('Error executing INSERT statement');
      }

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function removeNonfunctionalRequirementById($id): bool
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
