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
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use Psr\Log\LoggerInterface;

/**
 * Search a logger context for disallowed full-stops in keys.
 *
 * @implements \PHPStan\Rules\Rule<Node\Expr\MethodCall>
 */
final class KeyDotsRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Node\Expr\MethodCall) {
            return [];
        }

        if (!$this->isLoggerInterface($scope, $node)) {
            return [];
        }

        $dottedKeys = $this->hasContext($scope, $node);

        $messages = [];

        foreach ($dottedKeys as $key => $keyType) {
            $messages[] = \sprintf(
                "Logger context, key '%s' must not have an embedded full-stop ('.')",
                (string) $keyType->getValue()
            );
        }

        return $messages;
    }

    private function isLoggerInterface(Scope $scope, Node\Expr\MethodCall $node): bool
    {
        // $verbosity = VerbosityLevel::precise();
        //dump($calledOnType->describe(VerbosityLevel::precise()), __LINE__);

        $calledOnType = $scope->getType($node->var);

        if (!$calledOnType instanceof ObjectType) {
            return false;
        }

        $loggerInstance = new ObjectType(LoggerInterface::class);
        $hasLoggerInterface = $calledOnType->isSuperTypeOf($loggerInstance);

        if ($hasLoggerInterface->yes()) {
            return true;
        }

        return false;
    }

    private function hasContext(Scope $scope, Node\Expr\MethodCall $node): array
    {
        $args = $node->args;

        if (!isset($args[1])) {
            return [];
        }

        $argType = $scope->getType($args[1]->value);

        if (!$argType instanceof ArrayType) {
            return [];
        }

        if ($argType->count() === 0) {
            return [];
        }

        return $this->collectDottedKeys($argType);
    }

    /**
     * @return array<string,ConstantStringType>
     */
    private function collectDottedKeys(ArrayType $context): array
    {
        $dottedKeys = [];

        foreach ($context->getKeyTypes() as $keyType) {
            if (!$keyType instanceof ConstantStringType) {
                continue;
            }

            $keyName = $keyType->getValue();

            if (str_contains($keyName, '.')) {
                $dottedKeys[$keyName] = $keyType;
            }
        }

        return $dottedKeys;
    }
}
