<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;

/**
 * @internal
 */
final class DefaultDurationFormatter implements DurationFormatter
{
    /**
     * @see https://github.com/sebastianbergmann/php-timer/blob/6.0.0/src/Duration.php
     */
    public function format(Duration $duration): string
    {
        $durationInMilliseconds = $duration->seconds()->toInt() * 1_000 + $duration->nanoseconds() / 1_000_000;

        $hours = (int) \floor($durationInMilliseconds / 60 / 60 / 1_000);
        $hoursInMilliseconds = $hours * 60 * 60 * 1_000;

        $minutes = ((int) \floor($durationInMilliseconds / 60 / 1_000)) % 60;
        $minutesInMilliseconds = $minutes * 60 * 1_000;

        $seconds = (int) \floor(($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds) / 1_000);
        $secondsInMilliseconds = $seconds * 1_000;

        $milliseconds = (int) ($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds - $secondsInMilliseconds);

        if (0 < $hours) {
            return \sprintf(
                '%d:%02d:%02d.%03d',
                $hours,
                $minutes,
                $seconds,
                $milliseconds,
            );
        }

        if (0 < $minutes) {
            return \sprintf(
                '%d:%02d.%03d',
                $minutes,
                $seconds,
                $milliseconds,
            );
        }

        return \sprintf(
            '%d.%03d',
            $seconds,
            $milliseconds,
        );
    }
}
