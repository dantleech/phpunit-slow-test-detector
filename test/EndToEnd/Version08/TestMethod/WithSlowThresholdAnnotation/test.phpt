--TEST--
With test methods with @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version08/TestMethod/WithSlowThresholdAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version08/TestMethod/WithSlowThresholdAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 2 tests that took longer than expected.

1. 0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWhenTestMethodHasValidSlowThresholdAnnotation
2. 0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidSlowThresholdAnnotation

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
