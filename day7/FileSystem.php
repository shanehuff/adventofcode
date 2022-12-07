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

    public function makeFromCommandHistories(array $histories): void
    {
        $this->histories = collect($histories)->map(function ($history) {
            return new History($history);
        });

        $this->createFromHistories();
        $this->calculateSizes();
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

    private function getDirectorySize($directory)
    {
        return $this->nodes->filter(function ($node) use ($directory) {
            return $this->isNodeInDirectory($node, $directory);
        })->sum('size');
    }

    public function directoriesSmallerThan($size): Collection
    {
        return $this->nodes->filter(function ($node) use ($size) {
            return $node['size'] <= $size && $node['type'] === self::TYPE_DIRECTORY;
        });
    }

    public function directoriesCanBeFreeUp($size): Collection
    {
        return $this->nodes->filter(function ($node) use ($size) {
            return $node['size'] >= ($size - $this->freeSpace()) && $node['type'] === self::TYPE_DIRECTORY;
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
        $isRoot = '/' === $node['path'];
        $isDir = $node['type'] === self::TYPE_DIRECTORY;
        $indent = str_repeat('--', substr_count($node['path'], '/'));
        echo $isRoot ? '' : $indent . ' ';
        echo $isRoot ? '-/' : $node['name'];
        echo ' ';
        $nodeSize = $node['size'] ?? 0;
        echo $isDir ? '(dir, ' . $this->mb($nodeSize) . ')' : '(file, ' . $this->mb($nodeSize) . ')';
        echo PHP_EOL;
    }

    public function ls(string $path): Collection
    {
        // Return nodes of given path
        return $this->nodes->filter(function ($node) use ($path) {
            return $node['parent'] === $path;
        });
    }

    public function calculateSizes(): static
    {
        $this->nodes->transform(function ($node) {
            if ($node['type'] === self::TYPE_DIRECTORY) {
                $node['size'] = $this->getDirectorySize($node);
            }
            return $node;
        });

        return $this;
    }

    private function mb(mixed $nodeSize): string
    {
        return round($nodeSize / 1000000,2) . ' MB';
    }

    private function isNodeInDirectory($node, $directory): bool
    {
        $count = strlen($directory['name']) + 1;
        $isSameRoot = substr($node['parent'], 0, $count) === substr($directory['path'], 0, $count);

        return str_contains($node['parent'], $directory['path'])
            && $node['path'] !== $directory['path']
            && $isSameRoot;
    }

    public function getUsedSpace()
    {
        return $this->findNodeByPath('/')['size'];
    }

    public function freeSpace()
    {
        return 70000000 - $this->getUsedSpace();
    }
}