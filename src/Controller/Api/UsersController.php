<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\User;
use App\Form\Model\ClientDto;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Service\UserFormProcessor;
use App\Service\UserManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as ViewView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        UserManager $userManager,
        UserFormProcessor $userFormProcessor,
        Request $request
    ){
        $user = $userManager->create();
        [$user, $error] = ($userFormProcessor)($user, $request);
        return $user ?? $error;  
    }

        /**
     * @Rest\Post(path="/users/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        UserFormProcessor $userFormProcessor,

        UserManager $userManager,
        Request $request
    ){
        $user = $userManager->find($id);
        if (!$user) {
            return ViewView::create('Usuario no encontrado', Response::HTTP_BAD_REQUEST);
        }

        [$user, $error] = ($userFormProcessor)($user, $request);
        return $user ?? $error;        
    }
}