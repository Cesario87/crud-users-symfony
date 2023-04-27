<?php

namespace App\Model\User;

use DateTime;

class UserRepositoryCriteria
{
    public function __construct(
        public readonly ?string $clientId = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1,
        public readonly string $sortOrder = 'ASC', // Add sortOrder parameter with default value 'ASC'
        public readonly string $sortBy = 'id', // Add sortBy parameter with default value 'id'
        public $id = null,
        public readonly ?string $nombre = null,
        public readonly ?string $apellidos = null,
        public readonly ?string $poblacion  = null,
        public readonly ?string $categoria  = null,
        public readonly ?int $edad = null,
        public readonly ?bool $activo  = null,
        public readonly ?DateTime $createdAt = null,
        public ?bool $notEqual = false,
        public ?int $idGreaterThan = null,
        public ?int $idLessThan = null,
    ) {
    }
}
