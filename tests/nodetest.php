<?php

declare(strict_types=1);

/**
 * @file
 * nodetest.php
 *
 * This file is part of the test suite for the "thing" project.
 * It may contain test cases or example code for node-related functionality.
 *
 * @package thing
 */


use PHPUnit\Framework\TestCase;
use RedBeanPHP\R as R;
use Thing\Model\Node;

if (!defined('REDBEAN_MODEL_PREFIX')) {
	// Define the model prefix if not already defined
	define('REDBEAN_MODEL_PREFIX', '\\Thing\\Model\\');
}

class NodeTest extends TestCase
{

    protected function setUp(): void
    {
        // Only setup the database if not already setup
        if (!R::testConnection()) {
            $config = require __DIR__ . '/testconfig.php';
            R::setup($config['dsn'], $config['user'], $config['pass']);
        }
        R::nuke(); // Clear the database
    }

	protected function tearDown(): void
	{
		R::close();
	}

	public function testCreatedOnIsSetWhenInserted()
	{
		$createdOn = date('Y-m-d H:i:s');
		$updatedOn = date('Y-m-d H:i:s');
		// Simulate a new Node bean
		$bean = R::dispense('node'); // Use RedBeanPHP to create a new bean
		$bean->id = 0; // Simulate new record
		$bean->title = 'Test Node';
		$bean->createdOn = $createdOn;
		$bean->updatedOn = $updatedOn;

		$id = R::store($bean); // Simulate saving the bean

		$this->assertSame($bean->createdOn, $createdOn);
		$this->assertSame($bean->updatedOn, $updatedOn);
	}

	public function _testCreatedOnIsNotOverwrittenOnUpdate()
	{
		$originalCreatedOn = '2024-01-01 12:00:00';
		$bean = new stdClass();
		$bean->id = 1; // Simulate existing record
		$bean->title = 'Test Node';
		$bean->createdOn = $originalCreatedOn;
		$bean->updatedOn = null;

		$node = $this->getMockBuilder(Node::class)
			->disableOriginalConstructor()
			->onlyMethods([])
			->getMock();

		$node->bean = $bean;

		$node->update();

		$this->assertEquals(
			$originalCreatedOn,
			$bean->createdOn,
			'createdOn should not be overwritten on update'
		);
		$this->assertNotNull($bean->updatedOn, 'updatedOn should be set on update');
	}
}
