#!/usr/bin/env php
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

use RedBeanPHP\R;

require_once __DIR__ . '/../vendor/autoload.php'; // Adjust path if needed

// Database connection
//with namespace Model
define('REDBEAN_MODEL_PREFIX', '\\Thing\\Model\\');
// Setup RedBeanPHP with MySQL database (uncomment if needed)
R::setup('mysql:host=localhost;dbname=thing', 'root', 'elo58JiTs3_');
R::freeze(FALSE);
// Setup RedBeanPHP SQLite database in the same directory
//R::setup('sqlite:' . __DIR__ . '/nodes.db');

/**
 * Displays usage instructions for the application.
 *
 * This function outputs information on how to use the application,
 * including available commands, options, and examples.
 *
 * @return void
 */
function usage()
{
	echo "Usage:\n";
	echo "  php thing.php create <title> [<content>]\n";
	echo "  php thing.php read <id>\n";
	echo "  php thing.php update <id> <title> [<content>]\n";
	echo "  php thing.php delete <id>\n";
	echo "  php thing.php list\n";
	echo "  php thing.php find <searchtext>\n";
	exit(1);
}

/**
 * Creates a new node with the given title and content.
 *
 * @param string $title   The title of the node.
 * @param string $content The content of the node.
 * @return void
 */
function createNode($title, $content)
{
	$node = R::dispense('node');
	$node->title = $title;
	$node->content = $content;
	$id = R::store($node);
	echo "Node created with ID $id\n";
}

function readNode($id)
{
	$node = R::load('node', $id);
	if ($node->id) {
		print_r($node->export());
	} else {
		echo "Node with ID $id not found.\n";
	}
}

function updateNode($id, $title, $content)
{
	$node = R::load('node', $id);
	if ($node->id) {
		$node->title = $title;
		$node->content = $content;
		R::store($node);
		echo "Node with ID $id updated.\n";
	} else {
		echo "Node with ID $id not found.\n";
	}
}

function deleteNode($id)
{
	$node = R::load('node', $id);
	if ($node->id) {
		R::trash($node);
		echo "Node with ID $id deleted.\n";
	} else {
		echo "Node with ID $id not found.\n";
	}
}

function listNodes()
{
	$nodes = R::findAll('node');
	if (empty($nodes)) {
		echo "No nodes found.\n";
		return;
	}
	foreach ($nodes as $node) {
		echo "ID: {$node->id} | Title: {$node->title}\n";
	}
}

function findNodes($searchText)
{
	$nodes = R::find('node', 'title LIKE ? OR content LIKE ?', ["%$searchText%", "%$searchText%"]);
	if (empty($nodes)) {
		echo "No nodes found matching '$searchText'.\n";
		return;
	}
	foreach ($nodes as $node) {
		echo "ID: {$node->id} | Title: {$node->title}\n";
	}
}

// CLI argument handling
if ($argc < 2) {
	usage();
}

$command = $argv[1];

switch ($command) {
	case 'create':
		if ($argc < 3) usage();
		$title = $argv[2];
		$content = $argc >= 4 ? $argv[3] : '';
		createNode($title, $content);
		break;
	case 'read':
		if ($argc < 3) usage();
		readNode($argv[2]);
		break;
	case 'update':
		if ($argc < 3) usage();
		$id = $argv[2];
		$title = $argc >= 4 ? $argv[3] : '';
		$content = $argc >= 5 ? $argv[4] : '';
		updateNode($id, $title, $content);
		break;
	case 'delete':
		if ($argc < 3) usage();
		deleteNode($argv[2]);
		break;
	case 'list':
		listNodes();
		break;

	case 'find':
		if ($argc < 3) usage();
		findNodes($argv[2]);
		break;
	default:
		usage();
}
