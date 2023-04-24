<?php

namespace App\Model\User;

class UserRepositoryCriteria {
    public function __construct(
        public readonly ?string $clientId = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1,
        public readonly string $sortOrder = 'ASC', // Add sortOrder parameter with default value 'ASC'
        public readonly string $sortBy = 'id' // Add sortBy parameter with default value 'id'
    )
    {
        
    }
}