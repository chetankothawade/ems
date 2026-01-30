<?php

declare(strict_types=1);

namespace App\Support\Clock;
interface ClockInterface
{
    /**
     * Always return current time in UTC.
     */
    public function now(): \DateTimeImmutable;
}
