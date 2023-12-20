--TEST--
With @maximumDuration and @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version09/TestMethod/WithMaximumDurationAndSlowThresholdAnnotations/phpunit.xml';
$_SERVER['argv'][] = '--random-order-seed=1234567890';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version09/TestMethod/WithMaximumDurationAndSlowThresholdAnnotations/phpunit.xml
Random %seed:   %s

..                                                                  2 / 2 (100%)

Detected 1 test that took longer than expected.

1. 0.3%s (0.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestMethod\WithMaximumDurationAndSlowThresholdAnnotations\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations

Time: %s, Memory: %s

OK (2 tests, 2 assertions)
