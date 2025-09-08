<?php

declare(strict_types=1);

namespace Thing\Model;

/**
 * Node model
 */
class Node extends \RedBeanPHP\SimpleModel
{
	// Add your model logic here, e.g. validation, hooks, etc.

	public function update()
	{
		// Called before saving (insert or update)
		// Example: $this->bean->updated_at = date('Y-m-d H:i:s');
		if (empty($this->bean->title)) {
			throw new \Exception('Title cannot be empty');
		}
		if ($this->bean->id === 0 || !$this->bean->createdOn) {
			$this->bean->revision = 0; // Set revision to 0 for new records
			$this->bean->createdOn = date('Y-m-d H:i:s');
		}
		$this->bean->revision++;
		$this->bean->updatedOn = date('Y-m-d H:i:s');
	}

	public function open()
	{
		// Called after loading the bean
	}
}
