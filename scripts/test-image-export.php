<?php

require_once dirname(__DIR__) . '/lib/class_thx_fs.php';
require_once dirname(__DIR__) . '/lib/class_thx_exporter.php';

function get_theme_root () { return $GLOBALS['test_root'] . '/themes'; }
function get_theme_root_uri () { return 'http://example.test/wp-content/themes'; }
function get_site_url ($site = null, $path = '', $scheme = null) { return 'http://example.test/subsite'; }
function get_home_url () { return 'http://example.test'; }
function get_stylesheet_directory_uri () { return get_theme_root_uri() . '/sample'; }
function get_stylesheet_directory () { return $GLOBALS['test_stylesheet_directory']; }
function get_stylesheet () { return 'sample'; }
function wp_upload_dir () { return array('baseurl' => 'http://example.test/media', 'basedir' => $GLOBALS['test_root'] . '/uploads'); }
function wp_normalize_path ($path) { return preg_replace('|(?<=.)/+|', '/', str_replace('\\', '/', $path)); }
function trailingslashit ($path) { return rtrim($path, '/\\') . '/'; }
function apply_filters ($name, $value) {
	return $name === 'upfront-thx-theme_exports_images' && isset($GLOBALS['test_export_images'])
		? $GLOBALS['test_export_images']
		: $value;
}
function add_action () {}
function wp_get_attachment_image_src ($id, $size) { return array('http://target.test/uploads/imported.webp'); }
function get_attached_file ($id) {
	if ($id === 3) return $GLOBALS['test_root'] . '/uploads/first/background.png';
	return $GLOBALS['test_root'] . '/uploads/' . ($id === 1 ? 'first' : 'second') . '/photo.webp';
}
function stripslashes_deep ($data) { return $data; }
function get_intermediate_image_sizes () { return array('thumbnail'); }
function upfront_exporter_is_running () { return true; }
function esc_url ($url) { return $url; }

class Upfront_Layout { public static $version = 'test'; }
class TestJson { public function stringify_php ($value) { return var_export($value, true); } }
class Upfront_Object {}
class Upfront_Permissions {
	const BOOT = 'boot';
	const SAVE = 'save';
	public static function current ($permission) { return false; }
}
class Upfront_JsonResponse_Success {
	public $data;
	public function __construct ($data) { $this->data = $data; }
}

class Upfront_MacroCodec_LayoutData {
	public function encode_all ($content) {
		return str_replace(get_stylesheet_directory_uri(), '{{upfront:style_url}}', $content);
	}
}

class UpfrontThemeExporter {
	public static function get_root_slug () { return 'create_new'; }
}

class ImageExportTestExporter extends Thx_Exporter {
	protected function _render_region ($region, $use_var = false) { return $region->content; }
}

class Upfront_Server {
	protected function _out ($response) { return $response; }
}
require_once dirname(__DIR__, 2) . '/../themes/upfront/library/servers/class_upfront_image.php';
require_once dirname(__DIR__, 2) . '/../themes/upfront/elements/upfront-image/lib/uimage.php';
interface IUpfront_Server {}
require_once dirname(__DIR__, 2) . '/../themes/upfront/library/theme/class_upfront_child_theme.php';
require_once dirname(__DIR__, 2) . '/../themes/upfront/library/output/class_upfront_entity.php';

class ImageExportTestChildTheme extends Upfront_ChildTheme {
	public function keep_slider_image ($path) { return $this->_import_slider_image($path); }
	public function get_prefix () { return 'sample'; }
	public function initialize () {}
}

class ImageExportTestEntity extends Upfront_Entity {
	public function get_markup () { return ''; }
	public function slider_image ($image) { return $this->_get_background_slider_image_markup($image); }
}

class ImageExportTestImageServer extends Upfront_ImageServer {
	public function check ($url, $import = false) { return $this->_check_image_status($url, $import); }
	protected function _get_image_by_hash ($hash) {
		return isset($GLOBALS['test_import_hash']) && $hash === $GLOBALS['test_import_hash'] ? (object) array('ID' => 42) : false;
	}
	protected function _import_image ($path) {
		copy($path, $GLOBALS['test_root'] . '/imported.webp');
		$GLOBALS['test_import_hash'] = hash_file('md5', $path);
		return 42;
	}
}

function expect_image_export ($condition, $message) {
	if (!$condition) throw new RuntimeException($message);
}

$root = sys_get_temp_dir() . '/upfront-builder-image-test-' . bin2hex(random_bytes(8));
$GLOBALS['test_root'] = $root;
$GLOBALS['test_stylesheet_directory'] = $root . '/themes/sample';
mkdir($root . '/themes/sample/images/archive-home', 0777, true);
mkdir($root . '/themes/sample/images/global-regions/header', 0777, true);
mkdir($root . '/themes/sample/ui', 0777, true);
mkdir($root . '/uploads/first', 0777, true);
mkdir($root . '/uploads/second', 0777, true);

try {
	$first_image = base64_decode('UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA');
	$second_image = $first_image . 'second';
	file_put_contents($root . '/uploads/first/photo.webp', $first_image);
	file_put_contents($root . '/uploads/second/photo.webp', $second_image);
	file_put_contents($root . '/uploads/first/background.png', $first_image);
	file_put_contents($root . '/uploads/first/disabled.gif', $first_image);
	file_put_contents($root . '/themes/sample/images/archive-home/keep.jpg', 'existing image');
	file_put_contents($root . '/themes/sample/images/global-regions/header/header.png', 'header image');

	$reflection = new ReflectionClass('ImageExportTestExporter');
	$exporter = $reflection->newInstanceWithoutConstructor();
	$filesystem = Thx_Fs::get('sample');
	foreach (array('_fs' => $filesystem, '_theme' => 'sample', '_json' => new TestJson()) as $name => $value) {
		$property = $reflection->getParentClass()->getProperty($name);
		$property->setAccessible(true);
		$property->setValue($exporter, $value);
	}
	$method = $reflection->getMethod('_export_images');
	$method->setAccessible(true);
	$directory = $root . '/themes/sample/images/archive-home';
	$content = implode("\n", array(
		"'src' => 'http://example.test/media/first/photo.webp',",
		"'other' => '{{upfront:home_url}}/media/second/photo.webp',",
		"'background' => 'http://example.test/media/first/background.png',",
		"'shared' => '{{upfront:style_url}}/images/global-regions/header/header.png',",
		"'external' => 'https://other.example/image.png',",
	));
	$result = $method->invoke($exporter, $content, 'archive-home', $directory);
	$prefix = get_stylesheet_directory_uri() . '/images/archive-home/';
	$second_name = 'photo-' . substr(hash('sha256', $second_image), 0, 12) . '.webp';
	expect_image_export(file_get_contents($directory . '/photo.webp') === $first_image, 'First media image was not copied.');
	expect_image_export(file_get_contents($directory . '/' . $second_name) === $second_image, 'Same-named images must not overwrite each other.');
	expect_image_export(strpos($result, $prefix . 'photo.webp') !== false, 'First image URL was not rewritten.');
	expect_image_export(strpos($result, $prefix . $second_name) !== false, 'Second image URL was not rewritten.');
	expect_image_export(strpos($result, $prefix . 'background.png') !== false, 'Background URL was not rewritten.');
	expect_image_export(strpos($result, '{{upfront:style_url}}/images/global-regions/header/header.png') !== false, 'Existing theme image reference changed.');
	expect_image_export(strpos($result, 'https://other.example/image.png') !== false, 'External URL changed.');
	$GLOBALS['test_export_images'] = false;
	$without_images = $method->invoke($exporter, "'src' => 'http://example.test/media/first/disabled.gif'", 'archive-home', $directory);
	expect_image_export(strpos($without_images, 'disabled.gif') === false && !file_exists($directory . '/disabled.gif'), 'The existing disabled image export option changed.');
	unset($GLOBALS['test_export_images']);

	$method->invoke($exporter, '', 'archive-home', $directory);
	expect_image_export(is_file($directory . '/keep.jpg'), 'Saving a layout deleted an existing theme image.');
	expect_image_export(is_file($directory . '/photo.webp'), 'Saving a layout deleted a previously exported image.');
	expect_image_export(is_file($root . '/themes/sample/images/global-regions/header/header.png'), 'Saving a layout deleted a shared image.');
	$files = $filesystem->ls();
	expect_image_export(in_array($directory . '/photo.webp', $files, true), 'Exported image is absent from the recursive download list.');
	expect_image_export(in_array($root . '/themes/sample/images/global-regions/header/header.png', $files, true), 'Global region image is absent from the recursive download list.');

	$lightbox = $reflection->getMethod('_export_lightbox');
	$lightbox->setAccessible(true);
	$lightbox->invoke($exporter, (object) array('name' => 'example', 'content' => "'src' => 'http://example.test/media/first/background.png';"));
	$lightbox_image = $root . '/themes/sample/images/global-regions/lightboxes/example/background.png';
	expect_image_export(is_file($lightbox_image), 'Lightbox image was not exported.');
	expect_image_export(strpos(file_get_contents($root . '/themes/sample/global-regions/lightboxes/example.php'), '{{upfront:style_url}}/images/global-regions/lightboxes/example/background.png') !== false, 'Lightbox image URL is not portable.');

	$render_region = $reflection->getParentClass()->getMethod('_render_region');
	$render_region->setAccessible(true);
	$region = (object) array(
		'name' => 'slider', 'title' => 'Slider', 'properties' => array(
			(object) array('name' => 'background_slider_images', 'value' => array(1, 2, 'images/existing.webp')),
			(object) array('name' => 'breakpoint', 'value' => (object) array('mobile' => (object) array('background_slider_images' => array(3)))),
		), 'modules' => array(), 'wrappers' => array(),
	);
	$rendered = $render_region->invoke($exporter, $region);
	expect_image_export(strpos($rendered, 'images/photo.webp') !== false && strpos($rendered, 'images/' . $second_name) !== false, 'Background slider images were not exported safely.');
	expect_image_export(strpos($rendered, 'images/existing.webp') !== false, 'Existing background slider path changed.');
	expect_image_export(strpos($rendered, 'images/background.png') !== false, 'Breakpoint slider image was not exported.');
	expect_image_export(file_get_contents($root . '/themes/sample/images/photo.webp') === $first_image, 'Background slider original was overwritten.');

	$child_theme = (new ReflectionClass('ImageExportTestChildTheme'))->newInstanceWithoutConstructor();
	expect_image_export($child_theme->keep_slider_image('images/photo.webp') === 'images/photo.webp', 'Loading slider images must not import them.');
	$entity = (new ReflectionClass('ImageExportTestEntity'))->newInstanceWithoutConstructor();
	expect_image_export(strpos($entity->slider_image($child_theme->keep_slider_image('images/photo.webp')), get_stylesheet_directory_uri() . '/images/photo.webp') !== false, 'Frontend slider must use the theme image.');
	$_POST = array('item_id' => json_encode(array('images/photo.webp')));
	$sizes = (new Upfront_Uimage_Server())->get_image_sizes();
	expect_image_export($sizes->data['images']['images/photo.webp']['full'][0] === get_stylesheet_directory_uri() . '/images/photo.webp', 'Builder slider preview must use the theme image.');
	expect_image_export(!isset($GLOBALS['test_import_hash']), 'Preview imported a theme image into the media library.');

	if (class_exists('ZipArchive')) {
		$archive = new ZipArchive();
		expect_image_export($archive->open($root . '/theme.zip', ZipArchive::CREATE) === true, 'Could not create ZIP fixture.');
		foreach ($filesystem->ls() as $file) {
			$relative = 'sample/' . substr($file, strlen($filesystem->get_root_path()) + 1);
			if (is_dir($file)) $archive->addEmptyDir($relative);
			else $archive->addFile($file, $relative);
		}
		expect_image_export($archive->close(), 'Could not close ZIP fixture.');
		expect_image_export($archive->open($root . '/theme.zip') === true, 'Could not reopen ZIP fixture.');
		mkdir($root . '/installed');
		expect_image_export($archive->extractTo($root . '/installed'), 'Could not install ZIP fixture.');
		$archive->close();
		$GLOBALS['test_stylesheet_directory'] = $root . '/installed/sample';
		$installed = $GLOBALS['test_stylesheet_directory'] . '/images/archive-home/photo.webp';
		expect_image_export(file_get_contents($installed) === $first_image && getimagesize($installed) !== false, 'Installed theme image is missing or invalid.');
		$server = new ImageExportTestImageServer();
		$url = get_stylesheet_directory_uri() . '/images/archive-home/photo.webp';
		expect_image_export($server->check($url)['status'] === 'not_exists', 'Theme image was not offered for optional import.');
		expect_image_export(file_get_contents($installed) === $first_image, 'Ignoring import changed the theme image.');
		expect_image_export($server->check($url, true)['status'] === 'import_success', 'Optional import failed.');
		expect_image_export(file_get_contents($installed) === $first_image, 'Import changed the theme image.');
		expect_image_export(file_get_contents($root . '/imported.webp') === $first_image, 'Import did not copy the theme image.');
		echo "ZIP installation and optional import passed.\n";
	} else {
		echo "ZIP installation and optional import skipped: PHP ZipArchive unavailable.\n";
	}
	echo "Image export, lightboxes, repeated saves, filename collisions and recursive download listing passed.\n";
} finally {
	$entries = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach ($entries as $entry) {
		if ($entry->isDir()) rmdir($entry->getPathname());
		else unlink($entry->getPathname());
	}
	rmdir($root);
}
