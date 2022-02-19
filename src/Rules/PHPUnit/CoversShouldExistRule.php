<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;
use function array_merge;
use function explode;
use function sprintf;
use function strpos;

/**
 * @implements Rule<Node>
 */
class CoversShouldExistRule implements Rule
{

	/**
	 * Document lexer.
	 *
	 * @var Lexer
	 */
	private $phpDocLexer;

	/**
	 * Document parser.
	 *
	 * @var PhpDocParser
	 */
	private $phpDocParser;

	/**
	 * Reflection provider.
	 *
	 * @var ReflectionProvider
	 */
	private $reflectionProvider;

	public function __construct(
		Lexer $phpDocLexer,
		PhpDocParser $phpDocParser,
		ReflectionProvider $reflectionProvider
	)
	{
		$this->phpDocParser = $phpDocParser;
		$this->phpDocLexer = $phpDocLexer;
		$this->reflectionProvider = $reflectionProvider;
	}

	public function getNodeType(): string
	{
		return Node::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (
			!$node instanceof Node\Stmt\ClassLike
			&& !$node instanceof Node\Stmt\ClassMethod
		) {
			// @todo should we make sure covers tags aren't in weird places?
			return [];
		}

		$docComment = $node->getDocComment();
		if ($docComment === null) {
			return [];
		}
		$phpDocNode = $this->getDocNode($docComment);

		$errors = [];
		foreach ($phpDocNode->getTags() as $phpDocTag) {
			switch ($phpDocTag->name) {
				case '@covers':
					$errors = array_merge(
						$errors,
						$this->processCovers($node, $phpDocTag)
					);
					break;
				case '@coversDefaultClass':
					$errors = array_merge(
						$errors,
						$this->processCoversDefault($node, $phpDocTag)
					);
					break;
			}
		}

		return $errors;
	}

	/**
	 * @return RuleError[] errors
	 * @throws ShouldNotHappenException
	 */
	private function processCoversDefault(
		Node $node,
		PhpDocTagNode $phpDocTag
	): array
	{
		$errors = [];
		if ($node instanceof Node\Stmt\ClassMethod) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@coversDefaultClass found on class method %s.',
				$node->name
			))->build();
		} else {
			$className = (string) $phpDocTag->value;
			if (!$this->reflectionProvider->hasClass($className)) {
				$errors[] = RuleErrorBuilder::message(sprintf(
					'@coversDefaultClass does not provide a known class %s.',
					$className
				))->build();
			}
		}
		return $errors;
	}

	/**
	 * @return RuleError[] errors
	 * @throws ShouldNotHappenException
	 */
	private function processCovers(Node $node, PhpDocTagNode $phpDocTag): array
	{
		$errors = [];
		$covers = (string) $phpDocTag->value;

		if (strpos($covers, '::') === false) {
			[$className, $method] = explode('::', $covers);
		} else {
			$className = $covers;
		}
		if ($className === '') {
			if ($node instanceof Node\Stmt\ClassMethod) {
				$parent = $node->getAttribute('parent');
				if ($parent instanceof Node\Stmt\Class_) {
					$docComment = $parent->getDocComment();
					if ($docComment !== null) {
						$phpDocNode = $this->getDocNode($docComment);
						foreach ($phpDocNode->getTags() as $phpDocTag2) {
							if ($phpDocTag2->name === '@coversDefaultClass') {
								$className = (string) $phpDocTag2->value;
								break;
							}
						}
					}
				}
			}
		}
		if ($this->reflectionProvider->hasClass($className)) {
			$class = $this->reflectionProvider->getClass($className);
			if (isset($method) && $method !== '' && !$class->hasMethod($method)) {
				$errors[] = RuleErrorBuilder::message(sprintf(
					'@covers value %s references an unknown method.',
					$covers
				))->build();
			}
		} else {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@covers value %s references an unknown class.',
				$covers
			))->build();
		}
		return $errors;
	}

	private function getDocNode(Doc $docComment): PhpDocNode
	{
		$phpDocString = $docComment->getText();
		$tokens = new TokenIterator($this->phpDocLexer->tokenize($phpDocString));
		return $this->phpDocParser->parse($tokens);
	}

}
