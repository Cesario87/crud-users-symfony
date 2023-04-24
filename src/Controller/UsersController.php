<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    public function __construct()
    {
        
    }


    /**
     * @Route("/users", name="users_get")
     */
    public function list(Request $request, UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        $usersAsArray = [];
        foreach ($users as $user) {
            $usersAsArray[] = [
                'id' => $user->getId(),
                'nombre' => $user->getNombre()
            ];
        };
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $usersAsArray
                ]);
        return $response;
    }

    /**
     * @Route("/user/create", name="create_user")
     */
    public function createUser(Request $request, EntityManagerInterface $em) 
    {
        $user = new User();
        $user->setNombre('Enrique');
        $user->setApellidos('Pastor');
        $user->setPoblacion('Madrid');
        $user->setCategoria('User');
        $user->setEdad('55');
        $user->setActivo('No');
        $createdAt = new \DateTimeImmutable();
        $user->setCreatedAt($createdAt);
        $em->persist($user);
        $em->flush();
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $user->getId(),
                    'nombre' => $user->getNombre(),
                ]
            ]
                ]);
        return $response;
    }
}