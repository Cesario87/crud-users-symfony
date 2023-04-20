<?php

namespace App\Controller;

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
     * @Route("/users/list", name="users_list")
     */
    public function list(Request $request)
    {
        $name = $request->get('name', 'Anonymous');
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Rosario Parrales'
                ],
                [
                    'id' => 2,
                    'name' => 'Antonio Recio'
                ],
                [
                    'id' => 3,
                    'name' => $name
                ]
            ]
                ]);
        return $response;
    }
}