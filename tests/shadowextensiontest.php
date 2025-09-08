<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Extension/ShadowBean.php'; // Adjust path if needed

use PHPUnit\Framework\TestCase;
use RedBeanPHP\R as R;

if (!defined('REDBEAN_MODEL_PREFIX')) {
	// Define the model prefix if not already defined
	define('REDBEAN_MODEL_PREFIX', '\\Thing\\Model\\');
}

class ShadowExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        // Only setup the database if not already setup
        if (!R::testConnection()) {
            $config = require __DIR__ . '/../tests_config.php';
            R::setup($config['dsn'], $config['user'], $config['pass']);
        }
        R::nuke(); // Clear the database
    }

    protected function tearDown(): void
    {
        R::close();
    }

    public function testShadowBeanExtension()
    {
		Thing\Extension\enableShadowBeanExtensionForType('thing'); // Enable the shadow extension for 'thing' beans
        // Create and store the original bean
        $bean = R::dispense('thing');
        $bean->name = 'TestThing';
        R::store($bean);

        // Simulate an update and revision increment
        $bean->name = 'TestThingUpdated';
        R::store($bean);

        // Use the shadow extension to get the previous revision
        $shadow = R::findOne('shadow', ' source_type = ? AND source_id = ? ORDER BY revision DESC', ['thing', $bean->id]);

        $this->assertNotNull($shadow, 'Shadow bean exists after update');
        $this->assertEquals(2, $shadow->revision, 'Shadow bean should have the latest revision number');
    }
}