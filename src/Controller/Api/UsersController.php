<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\User;
use App\Form\Model\ClientDto;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
        EntityManagerInterface $em,
        Request $request
    ){
        $userDto = new UserDto();
        $form = $this->createForm(UserFormType::class, $userDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()){
            $user = new User();
            $user->setNombre($userDto->nombre);
            $user->setApellidos($userDto->apellidos);
            $em->persist($user);
            $em->flush();
            return $user;
        }
        return $form;
    }

        /**
     * @Rest\Post(path="/users/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ClientRepository $clientRepository,
        Request $request
    ){
        $user = $userRepository->find($id);
        if(!$user) {
            throw $this->createNotFoundException("Usuario no encontrado");
        }
        $userDto = UserDto::createFromUser($user);

        $originalClientes = new ArrayCollection();
        foreach ($user->getClientes() as $client){
            $clientDto = ClientDto::createFromClient($client);
            $userDto->clientes[] = $clientDto;
            $originalClientes->add($clientDto);
        }

        $form = $this->createForm(UserFormType::class, $userDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()){
            //quitar clientes
            foreach ($originalClientes as $originalClientDto) {
                if (!in_array($originalClientDto, $userDto->clientes)) {
                    $client = $clientRepository->find($originalClientDto->id);
                    $user->removeCliente($client);
                }
            }

            //añadir clientes
            foreach ($userDto->clientes as $newClientDto) {
                if (!$originalClientes->contains($newClientDto)) {
                    $client = $clientRepository->find($newClientDto->id ?? 0);
                    if (!$client) {
                        $client = new Client();
                        $client->setNombre($newClientDto->nombre);
                        $em->persist($client);
                    }
                    $user->addCliente($client);
                }
            }
            $user->setNombre($userDto->nombre);
            $em->persist($user);
            $em->flush();
            return $user;
        }
    }
}