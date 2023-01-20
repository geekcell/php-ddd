<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use Assert;
use GeekCell\Ddd\Contracts\Domain\Paginator;
use GeekCell\Ddd\Contracts\Domain\Repository as RepositoryInterface;
use Traversable;

abstract class ChainRepository implements RepositoryInterface
{
    /**
     * @var array<class-string<RepositoryInterface>, RepositoryInterface>
     */
    private array $repositories;

    /**
     * @var class-string<RepositoryInterface>
     */
    private string $primaryRepositoryKey;

    /**
     * ChainRepository constructor.
     */
    public function __construct(RepositoryInterface ...$repositories)
    {
        foreach ($repositories as $repository) {
            $this->repositories[get_class($repository)] = $repository;
        }

        // Select the first repository as primary by default
        $firstRepository = reset($repositories);
        $this->selectPrimary($firstRepository);
    }

    /**
     * Selects the primary repository, which will be used for collection,
     * pagination and count.
     *
     * @param RepositoryInterface $repository
     *
     * @throws Assert\AssertionFailedException
     */
    protected function selectPrimary(RepositoryInterface $repository): void
    {
        $className = get_class($repository);
        Assert\Assertion::keyExists($this->repositories, $className);

        $this->primaryRepositoryKey = $className;
    }

    /**
     * Returns the currently selected primary repository.
     *
     * @return RepositoryInterface
     */
    protected function getPrimary(): RepositoryInterface
    {
        return $this->repositories[$this->primaryRepositoryKey];
    }

    /**
     * Delegate operation to the currently selected primary repository.
     * For more advanced scenarios, this method can be overridden.
     *
     * @inheritDoc
     */
    public function collect(): Collection
    {
        return $this->getPrimary()->collect();
    }

    /**
     * Delegate operation to the currently selected primary repository.
     * For more advanced scenarios, this method can be overridden.
     *
     * @inheritDoc
     */
    public function paginate(int $itemsPerPage, int $currentPage = 1): Paginator
    {
        return $this->getPrimary()->paginate($itemsPerPage, $currentPage);
    }

    /**
     * Delegate operation to the currently selected primary repository.
     * For more advanced scenarios, this method can be overridden.
     *
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->getPrimary()->count();
    }

    /**
     * Delegate operation to the currently selected primary repository.
     * For more advanced scenarios, this method can be overridden.
     *
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->getPrimary()->getIterator();
    }
}
