<?php

namespace App\Interfaces;

use App\Models\Post;

interface PostFactoryInterface
{
    public function create(array $data): Post;
}
