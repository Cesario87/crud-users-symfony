<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ClientManager
{

    private $em;
    private $clientRepository;

    public function __construct(EntityManagerInterface $em, ClientRepository $clientRepository)
    {
        $this->em = $em;
        $this->clientRepository = $clientRepository;
    }

    public function find(UuidInterface $id): ?Client
    {
        return $this->clientRepository->find($id);
    }

    public function getRepository(): ClientRepository
    {
        return $this->clientRepository;
    }

    public function create(): Client
    {
        $client = new Client(Uuid::uuid4());
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
