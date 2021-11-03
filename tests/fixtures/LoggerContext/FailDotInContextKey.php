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

namespace Alister\PHPStan\Rules\Tests\Fixtures\LoggerContext;

use Psr\Log\LoggerInterface;

final class FailDotInContextKey
{
    public function doLog(LoggerInterface $log): void
    {
        $log->warning('hello', [
            'illegal.key' => 'hello1',
            'legal_key' => 'hello2',
        ]);
    }
}
