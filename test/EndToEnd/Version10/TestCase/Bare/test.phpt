--TEST--
With a test case that does not sleep in methods that are not test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestCase/Bare/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/TestCase/Bare/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 2 tests that took longer than expected.

1. 0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#1
2. 0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#0

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
