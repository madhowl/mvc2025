<?php
// app/Services/FileHandler.php

namespace App\Services;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

class FileHandler
{
    private string $postsDirectory;

    public function __construct(string $postsDirectory = ROOT_PATH . '/content/posts')
    {
        $this->postsDirectory = rtrim($postsDirectory, '/');
        // Убедимся, что директория существует
        if (!is_dir($this->postsDirectory)) {
            if (!mkdir($this->postsDirectory, 0755, true)) {
                throw new Exception("Could not create posts directory: {$this->postsDirectory}");
            }
        }
    }

    /**
     * Читает содержимое файла и возвращает массив с метаданными и телом.
     *
     * @param string $filePath
     * @return array|null ['metadata' => array, 'body' => string, 'filename' => string, 'category' => string]
     */
    public function readPostFile(string $filePath): ?array
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        // Разделение метаданных (JSON) и содержимого (Markdown)
        $parts = preg_split('/^---\s*$/m', $content, 2);
        if (count($parts) < 2) {
            // Если разделитель --- не найден, считаем всё содержимое как Markdown без метаданных
            $metadata = [];
            $markdownBody = $content;
        } else {
            $metadata = json_decode(trim($parts[0]), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Если JSON некорректен, пропускаем файл
                return null;
            }
            $markdownBody = $parts[1] ?? '';
        }

        $filename = basename($filePath, '.md');
        $category = $this->getCategoryFromPath($filePath);

        return [
            'metadata' => $metadata,
            'body' => trim($markdownBody),
            'filename' => $filename,
            'category' => $category
        ];
    }

    /**
     * Генерирует содержимое файла из метаданных и тела.
     *
     * @param array $metadata
     * @param string $markdownBody
     * @return string
     */
    public function buildFileContent(array $metadata, string $markdownBody): string
    {
        $jsonMetadata = json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return "---\n{$jsonMetadata}\n---\n{$markdownBody}";
    }

    /**
     * Получает путь к файлу на основе имени и категории.
     *
     * @param string $filename
     * @param string|null $category
     * @return string
     */
    public function getFilePath(string $filename, ?string $category = null): string
    {
        $categoryPath = $category ? $category . '/' : '';
        return $this->postsDirectory . '/' . $categoryPath . $filename . '.md';
    }

    /**
     * Получает категорию из пути к файлу.
     *
     * @param string $filePath
     * @return string
     */
    private function getCategoryFromPath(string $filePath): string
    {
        $relativePath = substr($filePath, strlen($this->postsDirectory) + 1);
        $dir = dirname($relativePath);
        return ($dir !== '.') ? $dir : '';
    }

    /**
     * Получает все пути к файлам постов.
     *
     * @return array
     */
    public function getAllPostFilePaths(): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->postsDirectory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Проверяет, существует ли файл.
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists(string $filePath): bool
    {
        return file_exists($filePath);
    }

    /**
     * Записывает содержимое в файл.
     *
     * @param string $filePath
     * @param string $content
     * @return bool
     */
    public function writeFile(string $filePath, string $content): bool
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
        return file_put_contents($filePath, $content) !== false;
    }

    /**
     * Удаляет файл.
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        return unlink($filePath);
    }

    /**
     * Переименовывает/перемещает файл.
     *
     * @param string $oldPath
     * @param string $newPath
     * @return bool
     */
    public function renameFile(string $oldPath, string $newPath): bool
    {
        $newDir = dirname($newPath);
        if (!is_dir($newDir)) {
            if (!mkdir($newDir, 0755, true)) {
                return false;
            }
        }
        return rename($oldPath, $newPath);
    }

    /**
     * Удаляет директорию, если она пуста.
     *
     * @param string $dirPath
     * @return bool
     */
    public function removeEmptyDir(string $dirPath): bool
    {
        if (is_dir($dirPath) && !(new \FilesystemIterator($dirPath))->valid()) {
            return rmdir($dirPath);
        }
        return false; // Не удалена, если не директория или не пуста
    }

    /**
     * Получает базовую директорию постов.
     *
     * @return string
     */
    public function getPostsDirectory(): string
    {
        return $this->postsDirectory;
    }
}