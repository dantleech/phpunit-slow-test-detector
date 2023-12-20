--TEST--
With a test case that has a setUpBeforeClass() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/TestCase/WithSetUpBeforeClass/phpunit.xml';
$_SERVER['argv'][] = '--random-order-seed=1234567890';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version11/TestCase/WithSetUpBeforeClass/phpunit.xml
Random %seed:   %s

...                                                                 3 / 3 (100%)

Detected 2 tests that took longer than expected.

1. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#1
2. 0.2%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#0

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
