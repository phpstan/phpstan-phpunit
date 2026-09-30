<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Framework\Struct;

use PHPUnit\Framework\TestCase;
use function PHPStan\Testing\assertType;

/**
 * @internal
 */
class CollectionTest extends TestCase
{
	public function testFromAssociative(): void
	{
		$data = [
			null,
			0,
			'some-string',
			new \stdClass(),
			['some' => 'value'],
		];

		$collection = (new TestCollection())->assignRecursive($data);

		static::assertCount(5, $collection);

		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
		static::assertSame($data[0], $collection->get(0));
		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
		static::assertSame($data[1], $collection->get(1));
		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
		static::assertSame($data[2], $collection->get(2));
		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
		static::assertSame($data[3], $collection->get(3));
		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
		static::assertSame($data[4], $collection->get(4));
		assertType("array{null, 0, 'some-string', stdClass, array{some: 'value'}}", $data);
	}
}

/**
 * @template TElement
 *
 * @extends Collection<TElement>
 */
class TestCollection extends Collection
{
}

/**
 * @template TElement
 *
 * @implements \IteratorAggregate<array-key, TElement>
 */
abstract class Collection implements \IteratorAggregate, \Countable
{
	/**
	 * @var array<array-key, TElement>
	 */
	protected array $elements = [];

	/**
	 * @param iterable<TElement> $elements
	 */
	public function __construct(iterable $elements = [])
	{
	}

	/**
	 * @param array-key $key
	 *
	 * @return TElement|null
	 */
	public function get($key)
	{
		return $this->elements[$key] ?? null;
	}

	/**
	 * @phpstan-impure
	 */
	public function count(): int
	{
		return \count($this->elements);
	}

	/**
	 * @return \Traversable<TElement>
	 */
	public function getIterator(): \Traversable
	{
		yield from $this->elements;
	}

	public function assignRecursive(array $options): static
	{
		return $this;
	}
}
