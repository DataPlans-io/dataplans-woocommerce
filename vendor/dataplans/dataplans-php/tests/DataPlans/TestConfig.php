<?php

use PHPUnit\Framework\TestCase;

// Define constants.
define('DATAPLANS_TOKEN', '');
define('DATAPLANS_API_MODE', 'sandbox');
define('DATAPLANS_API_VERSION', 1);

// Include necessary file.
require_once dirname(__FILE__) . '/../../lib/DataPlans.php';



abstract class TestConfig extends TestCase
{
}
