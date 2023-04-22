<?php

namespace App\Form\Model;

use App\Entity\User;

class UserDto {
    public $nombre;
    public $apellidos;
    public $poblacion;
    public $client;

    public function __construct()
    {
        $this->client = [];
    }

    public static function createFromUser(User $user): self
    {
        $dto = new self();
        $dto->nombre = $user->getNombre();
        return $dto;
    }
}