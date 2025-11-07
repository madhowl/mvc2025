<?php

namespace App\Models;

class Article
{
    public function all()
    {
        return [
            ['title' => 'Title1', 'content' => 'Content1'],
            ['title' => 'Title2', 'content' => 'Content2'],
            ['title' => 'Title3', 'content' => 'Content3']
            ];

}

}