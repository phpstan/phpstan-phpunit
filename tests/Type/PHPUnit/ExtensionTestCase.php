<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Cache\Cache;
use PHPStan\File\FileHelper;
use PHPStan\PhpDoc\PhpDocStringResolver;
use PHPStan\Testing\TestCase;
use PHPStan\Type\FileTypeMapper;

abstract class ExtensionTestCase extends TestCase
{

	protected function assertTypes(
		string $file,
		string $description,
		string $expression,
		array $dynamicMethodReturnTypeExtensions = [],
		array $dynamicStaticMethodReturnTypeExtensions = [],
		string $evaluatedPointExpression = 'die;'
	): void
	{
		$this->processFile($file, function (\PhpParser\Node $node, Scope $scope) use ($description, $expression, $evaluatedPointExpression): void {
			$printer = new \PhpParser\PrettyPrinter\Standard();
			$printedNode = $printer->prettyPrint([$node]);
			if ($printedNode === $evaluatedPointExpression) {
				/** @var \PhpParser\Node\Expr $expressionNode */
				$expressionNode = $this->getParser()->parseString(sprintf('<?php %s;', $expression))[0];
				$type = $scope->getType($expressionNode);
				$this->assertTypeDescribe(
					$description,
					$type->describe(),
					sprintf('%s at %s', $expression, $evaluatedPointExpression)
				);
			}
		}, $dynamicMethodReturnTypeExtensions, $dynamicStaticMethodReturnTypeExtensions);
	}

	protected function processFile(string $file, \Closure $callback, array $dynamicMethodReturnTypeExtensions = [], array $dynamicStaticMethodReturnTypeExtensions = []): void
	{
		/** @var \PHPStan\PhpDoc\PhpDocStringResolver $phpDocStringResolver */
		$phpDocStringResolver = $this->getContainer()->getByType(PhpDocStringResolver::class);

		$printer = new \PhpParser\PrettyPrinter\Standard();
		$resolver = new NodeScopeResolver(
			$this->createBroker(),
			$this->getParser(),
			$printer,
			new FileTypeMapper($this->getParser(), $phpDocStringResolver, $this->createMock(Cache::class)),
			new FileHelper('/'),
			true,
			true,
			[]
		);
		$resolver->processNodes(
			$this->getParser()->parseFile($file),
			new Scope(
				$this->createBroker($dynamicMethodReturnTypeExtensions, $dynamicStaticMethodReturnTypeExtensions),
				$printer,
				new TypeSpecifier($printer),
				$file
			),
			$callback
		);
	}

	protected function assertTypeDescribe(string $expectedDescription, string $actualDescription, string $label = ''): void
	{
		self::assertSame(
			$expectedDescription,
			$actualDescription,
			$label
		);
	}

}
