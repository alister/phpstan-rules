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

namespace Alister\PHPStan\Rules\Tests\Fixtures\Support\Entity;

final class User
{
    public string $string = 'hello';

    public function returnsString(): string
    {
        return 'string';
    }

    public function returnsEntity(): AnotherEntity
    {
        return new AnotherEntity();
    }
}
