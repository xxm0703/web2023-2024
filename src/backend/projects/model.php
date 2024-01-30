<?php

class ProjectWithFunctionalRequirements
{
  public $projectName;
  public $startDate;
  public $requirementName;
  public $requirementDescription;
  public $priority;
  public $estimate;

  public function __construct(
    string $projectName,
    string $startDate,
    string $requirementName,
    ?string $requirementDescription,
    int $priority,
    int $estimate
  ) {
    $this->projectName = $projectName;
    $this->startDate = $startDate;
    $this->requirementName = $requirementName;
    $this->requirementDescription = $requirementDescription;
    $this->priority = $priority;
    $this->estimate = $estimate;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new ProjectWithFunctionalRequirements(
      $arrayData['project_name'],
      $arrayData['start_date'],
      $arrayData['requirement_name'],
      $arrayData['description'],
      $arrayData['priority'],
      $arrayData['estimate'],
    );
  }
}

class ProjectWithNonFunctionalRequirements
{
  public $projectName;
  public $requirementName;
  public $requirementDescription;
  public $unit;
  public $value;
  public $priority;

  public function __construct(
    string $projectName,
    string $requirementName,
    ?string $requirementDescription,
    int $priority,
    string $unit,
    string $value

  ) {
    $this->projectName = $projectName;
    $this->requirementName = $requirementName;
    $this->requirementDescription = $requirementDescription;
    $this->priority = $priority;
    $this->unit = $unit;
    $this->value = $value;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new ProjectWithNonFunctionalRequirements(
      $arrayData['project_name'],
      $arrayData['requirement_name'],
      $arrayData['description'],
      $arrayData['priority'],
      $arrayData['unit'],
      $arrayData['value']
    );
  }
}

class Project implements JsonSerializable
{
  private $id;
  private $name;
  private $date;
  private $userId;
  private $createdAt;

  public function __construct(
    int $id,
    string $name,
    string $date,
    string $userId,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->date = $date;
    $this->userId = $userId;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new Project(
      $arrayData["id"],
      $arrayData['name'],
      $arrayData['date'],
      $arrayData['user_id'],
      $arrayData['created_at']
    );
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'date'=> $this->date,
      'userId' => $this->userId,
      'createdAt' => $this->createdAt
    ];
  }
}
