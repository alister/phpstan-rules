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

final class FailDotsInLoggerContextKey
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        $this->logger->warning('hello', [
            'illegal.key' => $this->hi(),
            'legal_key' => 'hello2',
        ]);
    }

    private function hi(): string
    {
        return '123';
    }
}
