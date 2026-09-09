<?php

class Thx_L10n {

	private static $_data = array();

	private function __construct () {
		$this->_populate_strings();
	}

	public static function serve () {
		$me = new self;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_filter('upfront_l10n', array($this, 'add_l10n_strings'));
	}

	public function add_l10n_strings ($strings) {
		if (!empty($strings['exporter'])) return $strings;
		$strings['exporter'] = self::get();
		return $strings;
	}

	/**
	 * Main data getter.
	 * Can return a prepared string, a key or an array of strings, depending on the parameter.
	 *
	 * @param bool $key Optional key parameter. If passed a true-ish value, a string is returned. Otherwise, an array
	 *
	 * @return mixed String if `$key` parameter is passed, array otherwise
	 */
	public static function get ($key=false) {
		return !empty($key)
			? (!empty(self::$_data[$key]) ? self::$_data[$key] : $key)
			: self::$_data
		;
	}

	/**
	 * Populates internal string storage.
	 */
	private function _populate_strings () {
		self::$_data = array(
			'plugin_name' => __('UpFront Builder', UpfrontThemeExporter::DOMAIN),
			// Inherited from Upfront core l10n server
			'long_loading_notice' => __('Der Upfront Builder kann eine Weile zum Laden brauchen (besonders beim ersten Mal), bitte hab Geduld :)', UpfrontThemeExporter::DOMAIN),
			'page_layout_name' => __('Seitename (leer lassen für single-page.php)', UpfrontThemeExporter::DOMAIN),
			'start_fresh' => __('Starte Standardlayout', UpfrontThemeExporter::DOMAIN),
			'start_from_existing' => __('Beginne mit bestehendem Layout', UpfrontThemeExporter::DOMAIN),
			'create_new_layout' => __('Neues Layout erstellen', UpfrontThemeExporter::DOMAIN),
			'edit_saved_layout' => __('Gespeichertes Layout bearbeiten', UpfrontThemeExporter::DOMAIN),
			'export_str' => __('Theme speichern', UpfrontThemeExporter::DOMAIN),
			'create_responsive_layouts' => __('Responsive', UpfrontThemeExporter::DOMAIN),
			'edit_grid' => __('Grid bearbeiten', UpfrontThemeExporter::DOMAIN),

			// modal.js
			'manage_layouts' => __('Layouts verwalten', UpfrontThemeExporter::DOMAIN),
			'create_layout' => __('Layout erstellen', UpfrontThemeExporter::DOMAIN),
			'edit_layout' => __('Layout bearbeiten', UpfrontThemeExporter::DOMAIN),
			'edit_existing_layout' => __('Bestehendes Layout bearbeiten', UpfrontThemeExporter::DOMAIN),
			'manage_exported_layouts' => __('Exportierte Templates verwalten', UpfrontThemeExporter::DOMAIN),
			'delete_layout' => __('Template löschen', UpfrontThemeExporter::DOMAIN),
			'delete_layout_confirm' => __('Dieses exportierte Template wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.', UpfrontThemeExporter::DOMAIN),
			'delete_layout_failed' => __('Das Template konnte nicht gelöscht werden.', UpfrontThemeExporter::DOMAIN),
			'loading' => __('UpFront Builder lädt...', UpfrontThemeExporter::DOMAIN),
			'activate_theme' => __('Theme aktivieren', UpfrontThemeExporter::DOMAIN),
			'activate_message' => __('Aktiviere das Theme:', UpfrontThemeExporter::DOMAIN),
			'theme' => __('Theme', UpfrontThemeExporter::DOMAIN),
			'yes' => __('Ja', UpfrontThemeExporter::DOMAIN),
			'no' => __('Nein', UpfrontThemeExporter::DOMAIN),

			// sidebar.js
			/* translators: %s: Name of the layout currently being edited. */
			'current_layout' => __('Aktuelles Layout: <b>%s</b>', UpfrontThemeExporter::DOMAIN),
			'layouts' => __('Layouts', UpfrontThemeExporter::DOMAIN),
			'media' => __('Medien', UpfrontThemeExporter::DOMAIN),
			'theme_images' => __('Medien', UpfrontThemeExporter::DOMAIN),
			'theme_sprites' => __('UI / Sprites', UpfrontThemeExporter::DOMAIN),
			'my_themes' => __('Meine Themes', UpfrontThemeExporter::DOMAIN),
			'themes' => __('Themes', UpfrontThemeExporter::DOMAIN),

			// post_image.js
			'image_variant' => __('Bildvariante', UpfrontThemeExporter::DOMAIN),
			'edit_content_style' => __('Inhalt Layout bearbeiten', UpfrontThemeExporter::DOMAIN),
			'edit_image_insert' => __('Bild Einfügen bearbeiten', UpfrontThemeExporter::DOMAIN),
			'variant_name' => __('Dieses Einfügen benennen:', UpfrontThemeExporter::DOMAIN),
			'variant_css' => __('CSS bearbeiten', UpfrontThemeExporter::DOMAIN),
			'variant_wrap_label' => __('Wrapper', UpfrontThemeExporter::DOMAIN),
			'variant_wrap_info' => __('Wrapper', UpfrontThemeExporter::DOMAIN),
			'variant_image_label' => __('Bild', UpfrontThemeExporter::DOMAIN),
			'variant_image_info' => __('Bild', UpfrontThemeExporter::DOMAIN),
			'variant_caption_label' => __('Beschriftung', UpfrontThemeExporter::DOMAIN),
			'variant_caption_info' => __('Beschriftung', UpfrontThemeExporter::DOMAIN),

			// mode context dialog (application.js)
			'builder_mode_context' => __('<p>Hier ist das UpFront Builder Interface, hier erstellst/bearbeitest du ein distributables Theme.</p><p>Das bedeutet, dass alle Änderungen, die du mit diesem Interface vornimmst, in deinem Theme-Ordner gespeichert werden.</p>', UpfrontThemeExporter::DOMAIN),
			'editor_mode_context' => __('<p>Du befindest dich im Upfront Editor Interface, hier nimmst du seiten-spezifische Anpassungen vor.</p><p>Das bedeutet, dass alle Änderungen, die du mit diesem Interface vornimmst, spezifisch für deine Seite sind und Änderungen, die mit dem Builder gemacht wurden, überschreiben.</p>', UpfrontThemeExporter::DOMAIN),
			'user_agrees' => __('Okay, Ich habe Verstanden!', UpfrontThemeExporter::DOMAIN),
			'dont_show_again' => __('Du brauchst dies nicht mehr zu zeigen', UpfrontThemeExporter::DOMAIN),

		);
	}
}
Thx_L10n::serve();
