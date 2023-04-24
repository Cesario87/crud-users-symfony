<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserManager
{

    private $em;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function find(UuidInterface $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getRepository(): UserRepository
    {
        return $this->userRepository;
    }

    public function create(): User
    {
        $user = new User(Uuid::uuid4());
        return $user;
    }

    public function save(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function reload(User $user): User
    {
        $this->em->refresh($user);
        return $user;
    }

    public function delete(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
