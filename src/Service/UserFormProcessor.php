<?php

namespace App\Service;

use App\Entity\User;
use App\Form\Model\ClientDto;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormProcessor
{

    private $userManager;
    private $clientManager;
    private $formFactory;

    public function __construct(
        UserManager $userManager,
        ClientManager $clientManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->userManager = $userManager;
        $this->clientManager = $clientManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(User $user, Request $request): array
    {
        $userDto = UserDto::createFromUser($user);

        $originalClient = new ArrayCollection();
        foreach ($user->getClient() as $client) {
            $clientDto = ClientDto::createFromClient($client);
            $userDto->client[] = $clientDto;
            $originalClient->add($clientDto);
        }

        $form = $this->formFactory->create(UserFormType::class, $userDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Formulario no enviado'];
        }
        if (!$form->isValid()) {
            // Remove clients
            foreach ($originalClient as $originalClientDto) {
                if (!in_array($originalClientDto, $userDto->client)){
                    $client = $this->clientManager->find($originalClientDto->id);
                    $user->removeClient($client);
                }
            }

            // Add clients
            foreach ($userDto->client as $newClientDto) {
               if(!$originalClient->contains($newClientDto)){
                    $client = $this->clientManager->find($newClientDto->id ?? 0);
                    if(!$client){
                        $client = $this->clientManager->create();
                        $client->setNombre($newClientDto->nombre);
                        $this->clientManager->persist($client);
                    }
                    $user->addClient($client);
               }
            }
            $this->userManager->save($user);
            $this->userManager->reload($user);
            return [$user, null];
        } 
        return [null, $form];
    }
}