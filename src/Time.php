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

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @internal
 */
final class Time
{
    private function __construct(
        private readonly Seconds $seconds,
        private readonly int $nanoseconds,
    ) {
    }

    /**
     * @throws Exception\InvalidNanoseconds
     */
    public static function fromSecondsAndNanoseconds(
        Seconds $seconds,
        int $nanoseconds,
    ): self {
        if (0 > $nanoseconds) {
            throw Exception\InvalidNanoseconds::notGreaterThanOrEqualToZero($nanoseconds);
        }

        $maxNanoseconds = 999_999_999;

        if ($maxNanoseconds < $nanoseconds) {
            throw Exception\InvalidNanoseconds::notLessThanOrEqualTo(
                $nanoseconds,
                $maxNanoseconds,
            );
        }

        return new self(
            $seconds,
            $nanoseconds,
        );
    }

    public function seconds(): Seconds
    {
        return $this->seconds;
    }

    public function nanoseconds(): int
    {
        return $this->nanoseconds;
    }

    /**
     * @throws Exception\InvalidStart
     */
    public function duration(self $start): Duration
    {
        $seconds = $this->seconds->toInt() - $start->seconds->toInt();
        $nanoseconds = $this->nanoseconds - $start->nanoseconds;

        if (0 > $nanoseconds) {
            --$seconds;

            $nanoseconds += 1_000_000_000;
        }

        if (0 > $seconds) {
            throw Exception\InvalidStart::notLessThanOrEqualToEnd();
        }

        return Duration::fromSecondsAndNanoseconds(
            Seconds::fromInt($seconds),
            $nanoseconds,
        );
    }
}
