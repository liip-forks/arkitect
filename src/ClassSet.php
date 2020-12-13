<?php
declare(strict_types=1);

namespace Arkitect;

use Symfony\Component\Finder\Finder;

class ClassSet implements \IteratorAggregate
{
    private string $directory;

    private array $exclude;

    private function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->exclude = [];
    }

    public function exclude(string ...$pattern): self
    {
        $this->exclude = array_merge($this->exclude, $pattern);

        return $this;
    }

    public static function fromDir(string $directory): self
    {
        return new self($directory);
    }

    public function getIterator()
    {
        $finder = (new Finder())
            ->files()
            ->in($this->directory)
            ->name('*.php')
            ->sortByName()
            ->followLinks()
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS(true);

        if ($this->exclude) {
            $finder->notPath($this->exclude);
        }

        return $finder;
    }
}
