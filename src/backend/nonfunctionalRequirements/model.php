<?php

class NonfunctionalRequirement implements JsonSerializable
{
  private $id;
  private $name;
  private $description;
  private $projectId;
  private $unit;
  private $value;
  private $createdAt;

  public function __construct(
    int $id,
    string $name,
    ?string $description,
    int $projectId,
    string $unit,
    string $value,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
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
      'projectId'=> $this->projectId,
      'unit' => $this->unit,
      'value' => $this->value,
      'createdAt' => $this->createdAt
    ];
  }
}
