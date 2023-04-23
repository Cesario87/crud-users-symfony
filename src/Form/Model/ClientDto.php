<?php
namespace App\Form\Model;

use App\Entity\Client;

class ClientDto {
    public $id;
    public $nombre;

    public static function createFromClient(Client $client): self
    {
        $dto = new self();
        $dto->id = $client->getId();
        $dto->nombre = $client->getNombre();
        return $dto;
    }
}