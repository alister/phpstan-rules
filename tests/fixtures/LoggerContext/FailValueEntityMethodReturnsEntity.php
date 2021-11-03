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

use Alister\PHPStan\Rules\Tests\Fixtures\Support\Entity\User;
use Psr\Log\LoggerInterface;

final class FailValueEntityMethodReturnsEntity
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        $user = new User();
        $this->logger->warning('hello', [
            'legal_scalar_value' => $user->returnsEntity(),
        ]);
    }
}
