<?php

namespace App\Controller\Api;

use App\Model\User\UserRepositoryCriteria;
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
    UserManager $userManager,
    Request $request
) {
    $clientId = $request->query->get('clientId');
    $page = $request->query->get('page');
    $itemsPerPage = $request->query->get('itemsPerPage');
    $sortOrder = $request->query->get('sortOrder', 'ASC'); // Get sortOrder parameter from URL, default value is 'ASC' if not specified
    $sortBy = $request->query->get('sortBy', 'id'); // Get sortBy parameter from URL, default value is 'id' if not specified
    $id = $request->query->get('id');
    $nombre = $request->query->get('nombre');
    $apellidos = $request->query->get('apellidos');
    $poblacion = $request->query->get('poblacion');
    $categoria = $request->query->get('categoria');
    $edad = $request->query->get('edad');
    $activo = $request->query->get('activo');
    $createdAt = $request->query->get('createdAt');
    $notEqual = $request->query->get('notEqual');
    $idGreaterThan = $request->query->get('id__gt');
    $idLessThan = $request->query->get('id__lt');

    $criteria = new UserRepositoryCriteria(
        $clientId,
        $itemsPerPage != null ? intval($itemsPerPage) : 10,
        $page != null ? intval($page) : 1,
        $sortOrder,
        $sortBy,
        $id,
        $nombre,
        $apellidos,
        $poblacion,
        $categoria,
        $edad,
        $activo,
        $createdAt,
        $notEqual,
        $idGreaterThan !== null ? intval($idGreaterThan) : null,
        $idLessThan,
    );
    
    return $userManager->getRepository()->findByCriteria($criteria);
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
