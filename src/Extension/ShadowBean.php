<?php

/**
 * @package Thing
 * @author Stephan Hombergs <info@sah-company.com>
 * @copyright Copyright (c) 2025 sah-comp
 * 
 * A simple CLI CRUD application using RedBeanPHP
 */

/**
 * TODO: Refactor code for better readability and maintainability.
 * TODO: Add detailed documentation for parameters and return values.
 */

declare(strict_types=1);

namespace Thing\Extension;

use RedBeanPHP\R;
use RedBeanPHP\OODBBean;
use RedBeanPHP\Observer; // Add this import at the top

/**
 * ShadowBeanExtension is an observer that tracks changes to beans and creates shadow beans
 * for revisions. It allows for soft deletion and revision history tracking.
 *
 * This extension should be enabled for specific bean types using the `enableShadowBeanExtensionForType` function.
 */
class ShadowBeanExtension implements Observer // Change Observer to Observable
{
	/**
	 * Handles events for beans and creates shadow beans for revisions.
	 *
	 * @param string $eventName The name of the event (e.g., 'after_update', 'before_delete').
	 * @param OODBBean $bean The bean that triggered the event.
	 */
	public function onEvent($eventName, $bean)
	{
		if (!($bean instanceof OODBBean)) {
			return;
		}

		$type = $bean->getMeta('type');

		// Skip shadow beans themselves
		if ($type === 'shadow') {
			return;
		}

		switch ($eventName) {
			case 'after_update':
				$this->createRevision($bean, false);
				break;

			case 'before_delete':
				$this->createRevision($bean, true);

				// Instead of really deleting, mark as deleted if bean has shadow
				if ($this->hasShadow($bean)) {
					$bean->setMeta('deleted', true);
					if ($bean->hasProperty('deleted')) {
						$bean->deleted = true;
						R::store($bean);
					}
					// Suppress actual deletion
					throw new \RedBeanPHP\RedException('Shadow bean: deletion suppressed, marked as deleted.');
				}
				break;
		}
	}

	/**
	 * Creates a revision of the bean and stores it as a shadow bean.
	 *
	 * @param OODBBean $bean The bean to create a revision for.
	 * @param bool $deleted Whether the bean is being deleted (default: false).
	 */
	protected function createRevision(OODBBean $bean, $deleted = false)
	{
		if (!$bean->id) {
			return;
		}

		// Get latest revision number
		$lastShadow = R::findOne(
			'shadow',
			' source_type = ? AND source_id = ? ORDER BY revision DESC ',
			[$bean->getMeta('type'), $bean->id]
		);

		$revision = $lastShadow ? $lastShadow->revision + 1 : 1;

		// Always create a NEW shadow bean (do not overwrite old ones)
		$shadow = R::dispense('shadow');
		$shadow->source_type = $bean->getMeta('type');
		$shadow->source_id   = $bean->id;
		$shadow->revision    = $revision;
		$shadow->deleted     = $deleted;

		if ($deleted) {
			$shadow->json = json_encode(['deleted' => true], JSON_PRETTY_PRINT);
		} else {
			$data = $bean->export(false, true, true); // include lists
			$shadow->json = json_encode($data, JSON_PRETTY_PRINT);
		}

		R::store($shadow);
	}

	/**
	 * Checks if the bean has a shadow bean (revision history).
	 * @param OODBBean $bean The bean to check.
	 * @return bool True if the bean has a shadow, false otherwise.
	 */
	protected function hasShadow(OODBBean $bean): bool
	{
		$count = R::count('shadow', ' source_type = ? AND source_id = ? ', [$bean->getMeta('type'), $bean->id]);
		return $count > 0;
	}
}

/**
 * Enables the ShadowBeanExtension for a specific bean type.
 * This function should be called to activate the extension for beans of the specified type.
 *
 * @param string $beanType The type of bean to enable the shadow extension for.
 */
function enableShadowBeanExtensionForType(string $beanType)
{
	$ext = new ShadowBeanExtension();
	$redBean = R::getRedBean();

	$listener = new class($ext, $beanType) implements \RedBeanPHP\Observer {
		private $ext;
		private $beanType;
		public function __construct($ext, $beanType)
		{
			$this->ext = $ext;
			$this->beanType = $beanType;
		}
		public function onEvent($eventName, $bean)
		{
			if ($bean instanceof OODBBean && $bean->getMeta('type') === $this->beanType) {
				$this->ext->onEvent($eventName, $bean);
			}
		}
	};

	$redBean->addEventListener('after_update', $listener);
	$redBean->addEventListener('before_delete', $listener); // <-- use before_delete
}
