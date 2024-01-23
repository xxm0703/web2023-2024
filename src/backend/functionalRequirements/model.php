<?php

class FunctionalRequirement implements JsonSerializable
{
  private $id;
  private $name;
  private $description;
  private $projectId;
  private $createdAt;

  public function __construct(
    int $id,
    string $name,
    ?string $description,
    int $projectId,
    string $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->projectId = $projectId;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new FunctionalRequirement(
      $arrayData["id"],
      $arrayData['name'],
      $arrayData['description'],
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
      'projectId' => $this->projectId,      
      'createdAt' => $this->createdAt
    ];
  }
}
