<?php

namespace App\Controller\Api;

use App\Form\Model\ClientDto;
use App\Form\Type\ClientFormType;
use App\Service\ClientManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/clients")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        ClientManager $clientManager
    ) {
        return $clientManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/clients")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        ClientManager $clientManager
    )
    {
        $clientDto = new ClientDto();
        $form = $this->createForm(ClientFormType::class, $clientDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $clientManager->create();
            $client->setNombre($clientDto->nombre);
            $clientManager->save($client);
            return $client;
        }
        return $form;
    }


}