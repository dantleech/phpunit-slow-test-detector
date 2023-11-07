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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\Seconds;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Reporter\DefaultReporter::class)]
#[Framework\Attributes\UsesClass(Comparator\DurationComparator::class)]
#[Framework\Attributes\UsesClass(Count::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
#[Framework\Attributes\UsesClass(Formatter\DefaultDurationFormatter::class)]
#[Framework\Attributes\UsesClass(Seconds::class)]
#[Framework\Attributes\UsesClass(SlowTest::class)]
#[Framework\Attributes\UsesClass(TestIdentifier::class)]
final class DefaultReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenThereAreNoSlowTests(): void
    {
        $faker = self::faker();

        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            Count::fromInt($faker->numberBetween(1)),
        );

        $report = $reporter->report();

        self::assertSame('', $report);
    }

    #[Framework\Attributes\DataProvider('provideExpectedReportMaximumDurationMaximumCountAndSlowTests')]
    public function testReportReturnsReportWhenThereAreFewerSlowTestsThanMaximumCount(
        string $expectedReport,
        Duration $maximumDuration,
        Count $maximumCount,
        SlowTest ...$slowTests,
    ): void {
        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        self::assertSame($expectedReport, $report);
    }

    public static function provideExpectedReportMaximumDurationMaximumCountAndSlowTests(): \Generator
    {
        $values = [
            'header-singular' => [
                <<<'TXT'
Detected 1 test that took longer than expected.

1. 0.300 (0.100) FooTest::test
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'header-plural' => [
                <<<'TXT'
Detected 2 tests that took longer than expected.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(2),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'list-sorted' => [
                <<<'TXT'
Detected 3 tests that took longer than expected.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
3. 0.250 (0.100) BazTest::test
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(3),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'list-unsorted' => [
                <<<'TXT'
Detected 3 tests that took longer than expected.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
3. 0.250 (0.100) BazTest::test
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(3),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'list-different-maximum-duration' => [
                <<<'TXT'
Detected 10 tests that took longer than expected.

 1. 20:50.000 (16:40.000) FooTest::test
 2.  9:35.000 ( 8:20.000) BarTest::test
 3.     0.250 (    0.100) BazTest::test
 4.     0.200 (    0.100) QuxTest::test
 5.     0.160 (    0.100) QuuxTest::test
 6.     0.150 (    0.100) CorgeTest::test
 7.     0.140 (    0.100) GraultTest::test
 8.     0.130 (    0.100) GarplyTest::test
 9.     0.120 (    0.100) WaldoTest::test
10.     0.110 (    0.100) FredTest::test
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(10),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(1_250_000),
                        Duration::fromMilliseconds(1_000_000),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(575_000),
                        Duration::fromMilliseconds(500_000),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuxTest::test'),
                        Duration::fromMilliseconds(200),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuuxTest::test'),
                        Duration::fromMilliseconds(160),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('CorgeTest::test'),
                        Duration::fromMilliseconds(150),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GraultTest::test'),
                        Duration::fromMilliseconds(140),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GarplyTest::test'),
                        Duration::fromMilliseconds(130),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('WaldoTest::test'),
                        Duration::fromMilliseconds(120),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FredTest::test'),
                        Duration::fromMilliseconds(110),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'footer-singular' => [
                <<<'TXT'
Detected 2 tests that took longer than expected.

1. 0.300 (0.100) FooTest::test

There is 1 additional slow test that is not listed here.
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
            'footer-plural' => [
                <<<'TXT'
Detected 3 tests that took longer than expected.

1. 0.300 (0.100) FooTest::test

There are 2 additional slow tests that are not listed here.
TXT,
                Duration::fromMilliseconds(500),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        Duration::fromMilliseconds(100),
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        Duration::fromMilliseconds(100),
                    ),
                ],
            ],
        ];

        foreach ($values as $key => [$expected, $maximumDuration, $maximumCount, $slowTests]) {
            yield $key => [
                $expected,
                $maximumDuration,
                $maximumCount,
                ...$slowTests,
            ];
        }
    }
}
