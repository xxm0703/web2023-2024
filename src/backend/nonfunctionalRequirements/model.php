<?php

class NonfunctionalRequirement implements JsonSerializable
{
  public $id;
  public $name;
  public $description;
  public $priority;
  public $projectId;
  public $unit;
  public $value;
  public $createdAt;

  public function __construct(
    int $id,
    string $name,
    ?string $description,
    int $priority,
    int $projectId,
    string $unit,
    string $value,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->priority = $priority;
    $this->projectId = $projectId;
    $this->unit = $unit;
    $this->value = $value;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new NonfunctionalRequirement(
      $arrayData["id"],
      $arrayData['name'],
      $arrayData['description'],
      $arrayData['priority'],
      $arrayData['project_id'],
      $arrayData['unit'],
      $arrayData['value'],
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
      'projectId'=> $this->projectId,
      'unit' => $this->unit,
      'value' => $this->value,
    ];
  }
}
