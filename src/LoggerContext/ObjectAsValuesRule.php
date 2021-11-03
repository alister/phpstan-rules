<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Alister Bulman
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/alister/phpstan-rules
 */

namespace Alister\PHPStan\Rules\LoggerContext;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Psr\Log\LoggerInterface;

use function sprintf;

/**
 * Search a logger context for disallowed class instances from listed namespaces (eg: entities).
 *
 * @implements \PHPStan\Rules\Rule<Array_>
 */
final class ObjectAsValuesRule implements Rule
{
    /**
     * @var array<array<string, string[]>>
     */
    private $bannedNamespaces;

    /**
     * @-param array<array<string, string|string[]>> $bannedNamespaces
     *
     * @param array<int, string> $bannedNamespaces
     */
    public function __construct(array $bannedNamespaces = [])
    {
        $this->bannedNamespaces = $bannedNamespaces;
    }

    public function getNodeType(): string
    {
        return Array_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node instanceof Array_) {
            throw new \LogicException('Expected an Array_ type');
        }

        $loggerContext = $this->getLoggerContext($node, $scope);
        if (null === $loggerContext) {
            return [];
        }

        $disallowedValues = $this->findDisallowedValues($loggerContext, $scope);
        $messages = [];

        foreach ($disallowedValues as $key => $class) {
            $messages[] = sprintf(
                "Logging the object(%s) value from key '%s' is not allowed",
                $class,
                $key
            );
        }

        return $messages;
    }

    private function getLoggerContext(Array_ $node, Scope $scope): ?Arg
    {
        $possibleLoggerContext = $node->getAttribute('parent');
        if (!$possibleLoggerContext instanceof Arg) {
            return null;
        }

        if (!$possibleLoggerContext->hasAttribute('parent')) {
            return null;
        }

        $possibleLogger = $possibleLoggerContext->getAttribute('parent');
        if (!$possibleLogger instanceof Node\Expr\MethodCall) {
            return null;
        }

        $calledOnType = $scope->getType($possibleLogger->var);

        $loggerInstance = new ObjectType(LoggerInterface::class);
        $hasLoggerInterface = $calledOnType->isSuperTypeOf($loggerInstance);

        if ($hasLoggerInterface->yes()) {
            return $possibleLoggerContext;
        }

        return null;
    }

    private function findDisallowedValues(Arg $node, Scope $scope): array
    {
        $inDisallowedNamespace = [];

        // $verbosity = VerbosityLevel::precise();
        foreach ($node->value->items as $itemNode) {
            $key = $itemNode->key->value;
            $value = $itemNode->value;
            $valueType = $scope->getType($value);

            if ($valueType instanceof StringType) {
                continue;
            }
            if (!$valueType instanceof \PHPStan\Type\TypeWithClassName) {
                throw new \UnexpectedValueException('Expected a type that is_a(TypeWithClassName), got '.get_class($valueType));
            }

            $className = $valueType->getClassName();

            foreach ($this->bannedNamespaces as $bannedNamespace) {
                if (str_starts_with($className, $bannedNamespace)) {
                    $inDisallowedNamespace[$key] = $className;
                }
            }
        }

        return $inDisallowedNamespace;
    }
}
