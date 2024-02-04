<?php

class FunctionalRequirement implements JsonSerializable
{
  public $id;
  public $name;
  public $description;
  public $priority;
  public $estimate;
  public $projectId;
  public $createdAt;

  public function __construct(
    int $id,
    string $name,
    ?string $description,
    int $priority,
    int $estimate,
    int $projectId,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->priority = $priority;
    $this->estimate = $estimate;
    $this->projectId = $projectId;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new FunctionalRequirement(
      $arrayData["id"],
      $arrayData['name'],
      $arrayData['description'],
      $arrayData['priority'],
      $arrayData['estimate'],
      $arrayData['project_id'],
      $arrayData['created_at']
    );
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'priority'=> $this->priority,
      'estimate'=> $this->estimate,
      'projectId' => $this->projectId,      
    ];
  }
}
