<?php

class User implements JsonSerializable
{
  private $id;
  private $email;
  private $password;
  private $createdAt;

  public function __construct(
    int $id,
    string $email,
    string $password,
    string $createdAt
  ) {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->createdAt = $createdAt;
  }

  public static function fromAssoc(array $arrayData)
  {
    return new User(
      $arrayData["id"],
      $arrayData['email'],
      $arrayData['password'],
      $arrayData['created_at']
    );
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'email' => $this->email,
      'password' => $this->password,
      'createdAt' => $this->createdAt
    ];
  }
}
