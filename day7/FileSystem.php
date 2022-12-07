<?php

use Illuminate\Support\Collection;

class FileSystem
{
    private Collection $histories;

    private Collection $nodes;

    public const TYPE_DIRECTORY = 'directory';
    public const TYPE_FILE = 'file';

    private array $pwd = [];

    public function __construct()
    {
        $this->histories = collect();
        $this->nodes = collect();
    }

    public function toArray(): array
    {
        return [
            'histories' => $this->histories->toArray(),
            'nodes' => $this->nodes->toArray()
        ];
    }

    public function loadHistories(array $histories): void
    {
        $this->histories = collect($histories)->map(function ($history) {
            return new History($history);
        });

        $this->createFromHistories();
    }

    private function createFromHistories(): void
    {
        $this->histories->each(function (History $history) {
            switch ($history->command()) {
                case 'cd':
                    $this->cd($history->parameters()[0]);
                    break;
                case 'ls':
                    foreach ($history->output() as $line) {
                        $nodeParts = explode(' ', $line);
                        if (str_contains($line, 'dir')) {
                            $directoryName = $nodeParts[1];
                            $this->maybeCreateDirectoryNode($directoryName);
                        } else {
                            $nodeSize = $nodeParts[0];
                            $nodeName = $nodeParts[1];
                            $this->maybeCreateFileNode($nodeName, $nodeSize);
                        }
                    }
                    break;
            }
        });
    }

    private function createDirectoryNode(array $directory): void
    {
        $directory['type'] = self::TYPE_DIRECTORY;
        $this->createNode($directory);
    }

    private function createNode(array $node): void
    {
        $isRoot = '/' === $node['path'];
        $node['parent'] = $isRoot ? null : $this->currentPath();

        $this->nodes->put($node['path'], $node);
    }

    private function findNodeByPath(?string $parentPath)
    {
        return $this->nodes->first(function ($node) use ($parentPath) {
            return $node['path'] === $parentPath;
        });
    }

    private function createFileNode(array $file): void
    {
        $file['type'] = self::TYPE_FILE;
        $this->createNode($file);
    }

    private function cd(string $directory): void
    {
        switch ($directory) {
            case '/':
                $this->pwd = ['/'];
                break;
            case '..':
                count($this->pwd) > 1 ? array_pop($this->pwd) : null;
                break;
            default:
                $this->pwd[] = $directory;
        }

        $this->maybeCreateDirectoryNode();
    }

    private function currentPath(): string
    {
        return str_replace('//', '/', implode('/', $this->pwd));
    }

    private function getAbsolutePath($name): string
    {
        return str_replace('//', '/', $this->currentPath() . '/' . $name);
    }

    private function maybeCreateDirectoryNode(?string $name = null): void
    {
        $directoryToCreate = $name ? $this->getAbsolutePath($name) : $this->currentPath();

        if (is_null($this->findNodeByPath($directoryToCreate))) {
            $this->createDirectoryNode([
                'path' => $directoryToCreate,
                'name' => basename($directoryToCreate),
            ]);
        }
    }

    private function maybeCreateFileNode(string $nodeName, int $nodeSize = 0): void
    {
        $fileToCreate = $this->getAbsolutePath($nodeName);

        if (is_null($this->findNodeByPath($fileToCreate))) {
            $this->createFileNode([
                'path' => $fileToCreate,
                'name' => basename($fileToCreate),
                'size' => $nodeSize
            ]);
        }
    }

    public function getDirectoriesWithSize()
    {
        return $this->nodes->filter(function ($node) {
            return $node['type'] === self::TYPE_DIRECTORY;
        })->map(function ($node) {
            $node['size'] = $this->getDirectorySize($node['path']);
            return $node;
        });
    }

    private function getDirectorySize(string $path)
    {
        $directory = $this->findNodeByPath($path);
        $size = 0;
        if ($directory['type'] === self::TYPE_DIRECTORY) {
            $size += $this->nodes->filter(function ($node) use ($directory) {
                // Check if directory path include node path
                return str_contains($node['path'], $directory['path']);

                //return $node['parent'] === $directory['path'];
            })->sum('size');
        }

        return $size;
    }

    public function getDeletableDirectories()
    {
        return $this->getDirectoriesWithSize()->filter(function ($directory) {
            return $directory['size'] <= 100000;
        });
    }

    public function renderTrees(): void
    {
        // Render tree of root node
        $this->renderTree($this->findNodeByPath('/'));
    }

    private function renderTree($node): void
    {
        $this->renderNode($node);
        $this->nodes->filter(function ($child) use ($node) {
            return $child['parent'] === $node['path'];
        })->each(function ($child) {
            $this->renderTree($child);
        });
    }

    private function renderNode($node): void
    {
        $name = $node['name'] === '' ? '/' : $node['name'];
        $isDir = $node['type'] === self::TYPE_DIRECTORY;
        $indent = str_repeat('--', substr_count($node['path'], '/'));
        echo $indent . ' ';
        echo $name;
        echo ' ';
        $nodeSize = $node['size'] ?? 0;
        echo $isDir ? '(dir, ' . $nodeSize . ')' : '(file, ' . $nodeSize . ' )';
        echo PHP_EOL;
    }
}