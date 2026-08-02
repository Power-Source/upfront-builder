<?php
/*
 * Error checking here
 */

$error = false;
if (empty($_GET['error']) || !is_numeric($_GET['error'])) return false;

$error = (int)$_GET['error'];
if (!$error) return false;

$errors = array(
	Thx_Admin::ERROR_PARAM => __('Bei der Verarbeitung Deiner Anfrage ist ein Fehler aufgetreten, da ein Parameter fehlte oder ungültig war.', UpfrontThemeExporter::DOMAIN),
	Thx_Admin::ERROR_PERMISSION => __('Du hast keine Berechtigung, dies zu tun.', UpfrontThemeExporter::DOMAIN),
	Thx_Admin::ERROR_DEFAULT => __('Hoppla, es scheint ein Fehler aufgetreten zu sein.', UpfrontThemeExporter::DOMAIN),
);
if (!in_array($error, array_keys($errors))) return false;

$error = $errors[$error];
if (empty($error)) return false;

?>
<div class="error">
	<p><?php echo esc_html($error); ?></p>
</div>
