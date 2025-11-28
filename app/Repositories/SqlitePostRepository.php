<?php

namespace App\Repositories;


use App\Interfaces\PostFactoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use PDO;

class SqlitePostRepository implements PostRepositoryInterface
{
    private PDO $pdo;
    private PostFactoryInterface $factory;

    public function __construct(PostFactoryInterface $factory, string $dbPath = __DIR__ . '/../../storage/posts.db')
    {
        $this->factory = $factory;
        $this->pdo = new PDO("sqlite:$dbPath");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT NOT NULL,
            cover_image TEXT,
            content TEXT NOT NULL,
            category TEXT
        )
    ');
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, title, description, cover_image, content, category FROM posts ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            $row['filename'] = 'post_' . $row['id'];
            return $this->factory->create($row);
        }, $rows);
    }

    public function find(int $id): ?Post
    {
        $stmt = $this->pdo->prepare('SELECT id, title, description, cover_image, content, category FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $row['filename'] = 'post_' . $row['id'];
            return $this->factory->create($row);
        }
        return null;
    }

    public function create(array $data): Post
    {
        $stmt = $this->pdo->prepare('INSERT INTO posts (title, description, cover_image, content, category) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['cover_image'],
            $data['content'],
            $data['category']
        ]);
        $id = $this->pdo->lastInsertId();
        $post = [
            'id' => (int)$id,
            'title' => $data['title'],
            'description' => $data['description'],
            'cover_image' => $data['cover_image'],
            'content' => $data['content'],
            'category' => $data['category'],
            'filename' => 'post_' . $id
        ];
        return $this->factory->create($post);
    }

    public function update(int $id, array $data): ?Post
    {
        $stmt = $this->pdo->prepare('UPDATE posts SET title = ?, description = ?, cover_image = ?, content = ?, category = ? WHERE id = ?');
        $result = $stmt->execute([
            $data['title'],
            $data['description'],
            $data['cover_image'],
            $data['content'],
            $data['category'],
            $id
        ]);
        if ($result && $stmt->rowCount() > 0) {
            return $this->find($id);
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}