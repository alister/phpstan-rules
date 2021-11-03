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

use Ergebnis\License;
use Ergebnis\PhpCsFixer;

$license = License\Type\MIT::markdown(
    __DIR__ . '/LICENSE.md',
    License\Range::since(
        License\Year::fromString('2021'),
        new \DateTimeZone('UTC')
    ),
    License\Holder::fromString('Alister Bulman'),
    License\Url::fromString('https://github.com/alister/phpstan-rules')
);

$license->save();

$config = PhpCsFixer\Config\Factory::fromRuleSet(new PhpCsFixer\Config\RuleSet\Php71($license->header()));

$config->getFinder()
    ->exclude([
        '.build/',
        '.github/',
        '.notes/',
        'test/Fixture/',
    ])
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->name('.php_cs');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php_cs.cache');

return $config;
