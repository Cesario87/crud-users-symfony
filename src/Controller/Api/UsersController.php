<?php

namespace App\Controller\Api;

use App\Service\UserFormProcessor;
use App\Service\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        UserManager $userManager
    ) {
        return $userManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        UserManager $userManager,
        UserFormProcessor $userFormProcessor,
        Request $request
    ) {
        $user = $userManager->create();
        [$user, $error] = ($userFormProcessor)($user, $request);
        $statusCode = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $user ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Get(path="/users/{id}")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(
        int $id,
        UserManager $userManager
    ) {
        $user = $userManager->find($id);
        if (!$user) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }
        return $user;
    }

    /**
     * @Rest\Post(path="/users/{id}")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        UserFormProcessor $userFormProcessor,
        UserManager $userManager,
        Request $request
    ) {
        $user = $userManager->find($id);
        if (!$user) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }
        [$user, $error] = ($userFormProcessor)($user, $request);
        $statusCode = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $user ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/users/{id}")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int $id,
        UserManager $userManager
    ) {
        $user = $userManager->find($id);
        if (!$user) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }
        $userManager->delete($user);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
