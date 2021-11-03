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

final class AnotherEntity
{
    public string $string = 'hello from AnotherEntity';

    public function returnsString(): string
    {
        return $this->string;
    }
}
