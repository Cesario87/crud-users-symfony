<?php

namespace App\Service;

use App\Entity\User;
use App\Form\Model\UserDto;
use App\Form\Model\ClientDto;
use App\Form\Type\UserFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormProcessor
{

    private $userManager;
    private $clientManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        UserManager $userManager,
        ClientManager $clientManager,
        FormFactoryInterface $formFactory
    ) {
        $this->userManager = $userManager;
        $this->clientManager = $clientManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(User $user, Request $request): array
    {
        $userDto = UserDto::createFromUser($user);
        $originalClientes = new ArrayCollection();
        foreach ($user->getClientes() as $client) {
            $clientDto = ClientDto::createFromClient($client);
            $userDto->clientes[] = $clientDto;
            $originalClientes->add($clientDto);
        }
        $form = $this->formFactory->create(UserFormType::class, $userDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if ($form->isValid()) {
            // Remove clientes
            foreach ($originalClientes as $originalClientDto) {
                if (!\in_array($originalClientDto, $userDto->clientes)) {
                    $client = $this->clientManager->find(Uuid::fromString($originalClientDto->id));
                    $user->removeCliente($client);
                }
            }

            // Add clientes
            foreach ($userDto->clientes as $newClientDto) {
                if (!$originalClientes->contains($newClientDto)) {
                    $client = null;
                    if ($newClientDto->id !== null) {
                        $client = $this->clientManager->find(Uuid::fromString($newClientDto->id));
                    }
                    if (!$client) {
                        $client = $this->clientManager->create();
                        $client->setNombre($newClientDto->nombre);
                        $this->clientManager->persist($client);
                    }
                    $user->addCliente($client);
                }
            }
            $user->setNombre($userDto->nombre);
            $this->userManager->save($user);
            $this->userManager->reload($user);
            return [$user, null];
        }
        return [null, $form];
    }
}
