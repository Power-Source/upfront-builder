<?php

require_once dirname(__DIR__) . '/lib/class_thx_sanitize.php';
require_once dirname(__DIR__) . '/lib/class_thx_exporter.php';

class UpfrontThemeExporter {
	const DOMAIN = 'test';
}

class ThemeCheckResponse extends RuntimeException {
	public $data;

	public function __construct ($data) {
		parent::__construct('JSON response');
		$this->data = $data;
	}
}

class ThemeCheckFilesystem {
	public $slug;

	public function set_theme ($slug) {
		$this->slug = $slug;
	}

	public function get_root_path () {
		return dirname(__DIR__) . '/' . $this->slug;
	}
}

function __ ($message, $domain) {
	return $message;
}

function wp_unslash ($data) {
	return $data;
}

function wp_send_json ($data) {
	throw new ThemeCheckResponse($data);
}

function themes_api ($action, $args) {
	throw new RuntimeException('Theme creation must not query an external repository.');
}

$reflection = new ReflectionClass('Thx_Exporter');
$exporter = $reflection->newInstanceWithoutConstructor();
$filesystem = new ThemeCheckFilesystem();
$property = $reflection->getProperty('_fs');
$property->setAccessible(true);
$property->setValue($exporter, $filesystem);

$cases = array(
	array('Webmaster Theme', 0, 'webmaster-theme'),
	array('Twenty Twenty-Four', 0, 'twenty-twenty-four'),
	array('lib', 2, 'lib'),
	array('12345', 1, null),
	array('', 1, null),
);

foreach ($cases as $case) {
	$_POST = array('name' => $case[0]);
	$filesystem->slug = null;
	try {
		$exporter->json_check_theme();
		throw new RuntimeException('Missing JSON response.');
	} catch (ThemeCheckResponse $response) {
		if ($response->data['error'] !== $case[1] || $filesystem->slug !== $case[2]) {
			throw new RuntimeException('Unexpected result for ' . var_export($case[0], true));
		}
	}
}

echo "Passed 5 local theme-name checks without repository requests.\n";
