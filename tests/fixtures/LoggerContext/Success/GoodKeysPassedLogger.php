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

namespace Alister\PHPStan\Rules\Tests\Fixtures\LoggerContext\Success;

use Psr\Log\LoggerInterface;

final class GoodKeysPassedLogger
{
    public function doLog(LoggerInterface $log): void
    {
        $log->debug('hello', [
            'legal_key' => 'hello2',
        ]);
    }
}
