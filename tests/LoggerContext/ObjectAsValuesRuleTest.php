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

namespace Alister\PHPStan\Rules\Tests\LoggerContext;

use Alister\PHPStan\Rules\LoggerContext\ObjectAsValuesRule;
use Alister\PHPStan\Rules\Tests\AbstractTestCase;
use Alister\PHPStan\Rules\Tests\Fixtures;
use PHPStan\Rules\Rule;
use Alister\PHPStan\Rules\Tests\Fixtures\Support\Entity\User;

/**
 * @internal
 *
 * @covers \Alister\PHPStan\Rules\LoggerContext\ObjectAsValuesRule
 */
final class ObjectAsValuesRuleTest extends AbstractTestCase
{
    public function provideCasesWhereAnalysisShouldSucceed(): iterable
    {
        $loggerContext = __DIR__ . '/../fixtures/LoggerContext';

        $paths = [
            'good-simple-class-with-good-keys' => $loggerContext . '/Success/GoodKeysSimpleScalarValues.php',
            'good-keys-passed-logger' => $loggerContext . '/Success/GoodKeysPassedLogger.php',
            'good-entity-returns-string' => $loggerContext .'/Success/GoodEntityMethodReturnsString.php',
            'good-sub-entity-returns-string' => $loggerContext .'/Success/GoodOtherEntityReturnsString.php',
            // #'script-with-anonymous-class' => $loggerContext .'Classes/FinalRule/Success/anonymous-class.php',
            // #'trait' => $loggerContext .'Classes/FinalRule/Success/ExampleTrait.php',
            // #'trait-with-anonymous-class' => $loggerContext .'Classes/FinalRule/Success/TraitWithAnonymousClass.php',
        ];

        foreach ($paths as $description => $path) {
            yield $description => [
                $path,
            ];
        }
    }

    public function provideCasesWhereAnalysisShouldFail(): iterable
    {
        $loggerContext = __DIR__ . '/../fixtures/LoggerContext/';

        yield from [
            'fail-simple-class-has-entity' => [
                $loggerContext . 'FailEntityInLoggerContextValue.php',
                [
                    \sprintf(
                        "Logging the object(%s) value from key 'illegal_value' is not allowed",
                        Fixtures\Support\Entity\User::class
                    ),
                    31,
                ],
            ],
            'fail-logs-entire-sub-entity' => [
                $loggerContext .'FailReturnsOtherEntity.php',
                [
                    \sprintf(
                        "Logging the object(%s) value from key 'illegal_entity_return' is not allowed",
                        Fixtures\Support\Entity\AnotherEntity::class
                    ),
                    32,
                ],
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new ObjectAsValuesRule([User::class]);
    }
}
