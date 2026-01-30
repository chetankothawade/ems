<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Timezone utility for handling time conversions and localization.
 * 
 * The API stores all times in UTC. Clients should:
 * 1. Receive times in ISO 8601 format (DATE_ATOM)
 * 2. Convert to local timezone using JavaScript's browser API
 * 
 * Example for frontend developers:
 * ```javascript
 * const utcTime = new Date('2024-01-01T10:30:00+00:00');
 * const localTime = new Date(utcTime.toLocaleString('en-US', {timeZone: 'America/New_York'}));
 * // Or simpler:
 * const localString = utcTime.toLocaleString();  // Uses browser's timezone
 * ```
 */
class TimezoneHelper
{
    /**
     * Format a DateTimeImmutable to ISO 8601 string for API responses.
     * All times are in UTC to ensure consistency across different clients.
     */
    public static function toApiFormat(\DateTimeImmutable $dateTime): string
    {
        return $dateTime->format(\DateTimeInterface::ATOM);
    }

    /**
     * Get timezone info for client-side conversion hints.
     * Returns information about the server's timezone configuration.
     */
    public static function getServerTimezoneInfo(): array
    {
        return [
            'server_timezone' => 'UTC',
            'server_time' => date('c'),
            'note' => 'All API times are in UTC. Convert to browser local time using JavaScript.'
        ];
    }

    /**
     * Validate that a DateTimeImmutable is in UTC timezone.
     */
    public static function isUtc(\DateTimeImmutable $dateTime): bool
    {
        return $dateTime->getTimezone()->getName() === 'UTC' || 
               $dateTime->getTimezone()->getName() === '+00:00';
    }
}
