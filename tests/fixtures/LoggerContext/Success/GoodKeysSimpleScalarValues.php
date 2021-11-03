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

final class GoodKeysSimpleScalarValues
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        $this->logger->debug('hello', [
            'legal_key' => 'hello2',
        ]);
    }
}
