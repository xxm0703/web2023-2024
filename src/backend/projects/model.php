<?php

class ProjectWithFunctionalRequirements
{
  public $projectName;
  public $requirementName;
  public $requirementDescription;

  public function __construct(
    string $projectName,
    string $requirementName,
    ?string $requirementDescription
  ) {
    $this->projectName = $projectName;
    $this->requirementName = $requirementName;
    $this->requirementDescription = $requirementDescription;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new ProjectWithFunctionalRequirements(
      $arrayData['project_name'],
      $arrayData['requirement_name'],
      $arrayData['description']
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


  public function __construct(
    string $projectName,
    string $requirementName,
    ?string $requirementDescription,
    string $unit,
    string $value

  ) {
    $this->projectName = $projectName;
    $this->requirementName = $requirementName;
    $this->requirementDescription = $requirementDescription;
    $this->unit = $unit;
    $this->value = $value;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new ProjectWithNonFunctionalRequirements(
      $arrayData['project_name'],
      $arrayData['requirement_name'],
      $arrayData['description'],
      $arrayData['unit'],
      $arrayData['value']
    );
  }
}

class Project implements JsonSerializable
{
  private $id;
  private $name;
  private $userId;
  private $createdAt;

  public function __construct(
    int $id,
    string $name,
    string $userId,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->userId = $userId;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new Project(
      $arrayData["id"],
      $arrayData['name'],
      $arrayData['user_id'],
      $arrayData['created_at']
    );
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'userId' => $this->userId,
      'createdAt' => $this->createdAt
    ];
  }
}
