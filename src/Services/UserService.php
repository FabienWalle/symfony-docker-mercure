<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {

    }

 /**
  * @return User[]
  */
    public function findAll(): array
    {
        return $this->userRepository->findBy([], ['username' => 'ASC']);
    }
}
