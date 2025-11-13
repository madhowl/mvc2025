<?php

namespace App\Factories;

use App\Interfaces\PostFactoryInterface;
use App\Models\Post;
use Michelf\MarkdownExtra;

class PostFactory implements PostFactoryInterface
{


    public function create(array $data): Post
    {
        return new Post(
            (int)($data['id']),
            (string)($data['title']),
            (string)($data['description']),
            (string)($data['cover_image']),
            (string)(MarkdownExtra::defaultTransform($data['content'])),
            (string)($data['category'] ?? ''),
            (string)($data['filename'] ?? '')
        );
    }
}
