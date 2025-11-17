<?php

namespace App\Models;

class Post
{
    public int $id;
    public string $title;
    public string $description;
    public string $cover_image;
    public string $content;
    public string $category;
    public string $filename;

    public function __construct(
        int $id,
        string $title,
        string $description,
        string $cover_image,
        string $content,
        string $category = '',
        string $filename = ''
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->cover_image = $cover_image;
        $this->content = $content;
        $this->category = $category;
        $this->filename = $filename;
    }
}
