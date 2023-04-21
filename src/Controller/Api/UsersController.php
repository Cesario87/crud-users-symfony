<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        UserRepository $userRepository
    ){
        return $userRepository->findAll();
    }

        /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        EntityManagerInterface $em
    ){
        $user = new User();
        $user->setNombre('Amador');
        $user->setApellidos('Rivas');
        // $user->setPoblación('Madrid');
        // $user->setCategoría('User');
        // $user->setEdad('55');
        // $user->setActivo('No');
        // $createdAt = new \DateTimeImmutable();
        // $user->setCreatedAt($createdAt);
        $em->persist($user);
        $em->flush();
        return $user;
    }
}