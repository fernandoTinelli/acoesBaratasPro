<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
  public function create(array $dataRequest, UserPasswordHasherInterface $passwordHasher = null): User
  {
    $user = (new User())
      ->setName($dataRequest['_name'])
      ->setEmail($dataRequest['_email'])
      ->setPassword($dataRequest['_password']);

    if (!is_null($passwordHasher)) {
      $hashedPassword = $passwordHasher->hashPassword(
        $user,
        $user->getPassword()
      );
      $user->setPassword($hashedPassword);
    }
    

    return $user;
  }
}