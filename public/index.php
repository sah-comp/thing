<?php declare (strict_types = 1);
/**
 * Thing
 */

/**
 * Autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Construct a model.
 */
$model = new \Thing\Model\Model;

/**
 * Let the model do something.
 */
echo $model->do("Bark, bark") . "\n";
