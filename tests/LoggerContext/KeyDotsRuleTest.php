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

use Alister\PHPStan\Rules\LoggerContext\KeyDotsRule;
use Alister\PHPStan\Rules\Tests\AbstractTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @covers \Alister\PHPStan\Rules\LoggerContext\KeyDotsRule
 */
final class KeyDotsRuleTest extends AbstractTestCase
{
    public function provideCasesWhereAnalysisShouldSucceed(): iterable
    {
        $loggerContext = __DIR__ . '/../fixtures/LoggerContext';

        yield from [
            'good-simple-class-with-good-keys' => [$loggerContext . '/Success/GoodKeysSimpleScalarValues.php'],
            'good-keys-passed-logger' => [$loggerContext . '/Success/GoodKeysPassedLogger.php'],
            // 'final-class-with-anonymous-class' => [$loggerContext . '/Success/FinalClassWithAnonymousClass.php'],
            // 'script-with-anonymous-class' => $loggerContext . '/Success/anonymous-class.php',
            // 'trait' => $loggerContext . '/Success/ExampleTrait.php',
            // 'trait-with-anonymous-class' => $loggerContext . '/Success/TraitWithAnonymousClass.php',
        ];
    }

    public function provideCasesWhereAnalysisShouldFail(): iterable
    {
        $loggerContext = __DIR__ . '/../fixtures/LoggerContext';

        yield from [
            'fail-with-dots' => [
                $loggerContext . '/FailDotInContextKey.php',
                [
                    "Logger context, key 'illegal.key' must not have an embedded full-stop ('.')",
                    22,
                ],
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new KeyDotsRule();
    }
}
