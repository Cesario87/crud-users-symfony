<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClientManager
{
    private $em;
    private $clientRepository;

    public function __construct(EntityManagerInterface $em, ClientRepository $clientRepository)
    {
        $this->em = $em;
        $this->clientRepository = $clientRepository;
    }

    public function find(int $id): ?Client
    {
        return $this->clientRepository->find($id);
    }

    public function create(): Client
    {
        $client = new Client();
        return $client;
    }

    public function persist(Client $client): Client
    {
        $this->em->persist($client);
        return $client;
    }

    public function save(Client $client): Client
    {
        $this->em->persist($client);
        $this->em->flush();
        return $client;
    }

    public function reload(Client $client): Client
    {
        $this->em->refresh($client);
        return $client;
    }
}