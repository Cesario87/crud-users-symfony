<?php

namespace App\Model\User;

class UserRepositoryCriteria {
    public function __construct(
        public readonly ?string $clientId = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1
    )
    {
        
    }
}