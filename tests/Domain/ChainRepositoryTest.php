<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain;

use GeekCell\Ddd\Contracts\Domain\Repository;
use GeekCell\Ddd\Domain\ChainRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test subject.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class TestChainRepository extends ChainRepository
{
    public function select(Repository $repository): void
    {
        $this->selectPrimary($repository);
    }
}

class ChainRepositoryTest extends TestCase
{
    public function testChainDefault(): void
    {
        // Given
        $mockFirst = Mockery::mock(Repository::class);
        $mockSecond = Mockery::mock(Repository::class);
        $mockThird = Mockery::mock(Repository::class);

        $chainRepository = new TestChainRepository(
            $mockFirst,
            $mockSecond,
            $mockThird,
        );

        /** @var Mockery\MockInterface $mockFirst */
        $mockFirst->expects('collect')->once();
        $mockFirst->expects('paginate')->once();
        $mockFirst->expects('getIterator')->once();
        $mockFirst->expects('count')->once();

        /** @var Mockery\MockInterface $mockSecond */
        $mockSecond->expects('collect')->never();
        $mockSecond->expects('paginate')->never();
        $mockSecond->expects('getIterator')->never();
        $mockSecond->expects('count')->never();

        /** @var Mockery\MockInterface $mockThird */
        $mockThird->expects('collect')->never();
        $mockThird->expects('paginate')->never();
        $mockThird->expects('getIterator')->never();
        $mockThird->expects('count')->never();

        // When
        $chainRepository->collect();
        $chainRepository->paginate(10);
        $chainRepository->getIterator();
        $chainRepository->count();

        $this->addToAssertionCount(12);
    }

    public function testChainSelect(): void
    {
        // Given
        $mockFirst = Mockery::mock(Repository::class);
        $mockSecond = Mockery::mock(Repository::class);
        $mockThird = Mockery::mock(Repository::class);

        $chainRepository = new TestChainRepository(
            $mockFirst,
            $mockSecond,
            $mockThird,
        );

        /** @var Mockery\MockInterface $mockFirst */
        $mockFirst->expects('collect')->never();
        $mockFirst->expects('paginate')->never();
        $mockFirst->expects('getIterator')->never();
        $mockFirst->expects('count')->never();

        /** @var Mockery\MockInterface $mockSecond */
        $mockSecond->expects('collect')->once();
        $mockSecond->expects('paginate')->once();
        $mockSecond->expects('getIterator')->once();
        $mockSecond->expects('count')->once();

        /** @var Mockery\MockInterface $mockThird */
        $mockThird->expects('collect')->never();
        $mockThird->expects('paginate')->never();
        $mockThird->expects('getIterator')->never();
        $mockThird->expects('count')->never();

        // When

        /** @var Repository $mockSecond */
        $chainRepository->select($mockSecond);
        $chainRepository->collect();
        $chainRepository->paginate(10);
        $chainRepository->getIterator();
        $chainRepository->count();

        $this->addToAssertionCount(12);
    }
}
