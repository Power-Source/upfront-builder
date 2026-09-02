<?php

	/**
	 * Conflict with wp.org theme repo status
	 *
	 * @var bool
	 */
	$is_conflicted = false;

	/**
	 * Get themes update info
	 *
	 * @var array Array of WP_Theme objects
	 */
	$updates = get_theme_updates();
	if (!is_array($updates)) $updates = array();

	$slug = !empty($slug) ? $slug : false;
	$is_conflicted = !empty($updates[$slug]) && !empty($updates[$slug]->update)
		? $updates[$slug]->update
		: false
	;

?>
<?php if ( $new ) { /* inputs for new theme */ ?>
	<div class="uf-thx-theme_info clearfix">
		<div class="uf-thx-theme_meta">
			<label for="theme-name">
				<span class="description"><?php esc_html_e('Wie soll dein neues Theme heißen?', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-name" />
				<input type="hidden" id="export_with_images" value="true" />
				<input type="hidden" id="activate_theme" value="false" />
			</label>
		</div>
	</div>
<?php } else { /* inputs for existing theme */ ?>
	<?php
		/* translators: %s: Theme name. */
		$theme_info_title = sprintf(__('%s Theme Info', UpfrontThemeExporter::DOMAIN), $name);
	?>
	<h2 class="title"><?php echo esc_html($theme_info_title); ?></h2>
	<div class="uf-thx-theme_info clearfix">
		<?php if ($is_conflicted) { ?>
			<div class="notice notice-warning">
				<p>
					<?php esc_html_e("Dieser Theme-Name steht im Konflikt mit einem Theme aus dem WordPress-Theme-Repository:", UpfrontThemeExporter::DOMAIN); ?>
					<?php if(!empty($is_conflicted['url'])) { ?> <a href="<?php echo esc_url($is_conflicted['url']); ?>" target="_blank"> <?php } ?>
						<?php echo esc_html($is_conflicted['theme']); ?>
					<?php if(!empty($is_conflicted['url'])) { ?> </a> <?php } ?>
				</p>
				<p>
					<?php esc_html_e("Dies kann in Zukunft Probleme mit automatischen Updates verursachen.", UpfrontThemeExporter::DOMAIN); ?>
					<button type="button" class="button conflict fix">
						<?php esc_html_e("Theme klonen und Problem beheben", UpfrontThemeExporter::DOMAIN); ?>
					</button>
				</p>
			</div>
		<?php } ?>
		<div class="uf-thx-theme_screenshot">
			<label>
				<span class="description"><?php esc_html_e('Theme Bild oder Logo:', UpfrontThemeExporter::DOMAIN); ?></span>
			<?php if (!empty($screenshot)) { ?>
				<img class="nostyle" src="<?php echo esc_url($screenshot); ?>" alt="Add Image" />
			<?php } else { ?>
				<div class="no-image"></div>
			<?php }?>

				<input type="hidden" id="theme-screenshot" value="" />
			</label>
			<button type="button" class="change-image"><?php esc_html_e('Bild ändern', UpfrontThemeExporter::DOMAIN); ?></button>
		</div>
		<div class="uf-thx-theme_meta">
			<label for="theme-name">
				<span class="description"><?php esc_html_e('Theme Name:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-name" placeholder="<?php esc_attr_e('Name für dein Theme', UpfrontThemeExporter::DOMAIN); ?>" value="<?php if (!empty($name)) echo esc_attr($name); ?>" />
			</label>
			<label for="theme-slug">
				<span class="description"><?php esc_html_e('Theme Verzeichnis:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input readonly type="text" id="theme-slug" placeholder="<?php esc_attr_e('Basisverzeichnis für dein Theme', UpfrontThemeExporter::DOMAIN); ?>" value="<?php if (!empty($slug)) echo esc_attr($slug); ?>" />
			</label>
			<label for="author">
				<span class="description"><?php esc_html_e('Autor Name:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="author" placeholder="<?php esc_attr_e('Dein Name', UpfrontThemeExporter::DOMAIN); ?>" value="<?php if (!empty($author)) echo esc_attr($author); ?>" />
			</label>
			<label for="author-uri">
				<span class="description"><?php esc_html_e('Autor URL:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="author-uri" placeholder="<?php esc_attr_e('Deine URL', UpfrontThemeExporter::DOMAIN); ?>" value="<?php if (!empty($author_uri)) echo esc_attr($author_uri); ?>" />
			</label>
			<label for="theme-description">
				<span class="description"><?php esc_html_e('Theme Beschreibung:', UpfrontThemeExporter::DOMAIN); ?></span>
				<textarea id="theme-description"><?php if (!empty($description)) echo esc_textarea($description); ?></textarea>
			</label>
			<label for="theme-version">
				<span class="description"><?php esc_html_e('Theme Version:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-version" value="<?php if (!empty($version)) echo esc_attr($version); ?>" />
			</label>
			<label for="theme-uri">
				<span class="description"><?php esc_html_e('Theme URL:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-uri" value="<?php if (!empty($theme_uri)) echo esc_attr($theme_uri); ?>" />
			</label>
			<label for="theme-licence">
				<span class="description"><?php esc_html_e('Theme Lizenz:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-licence" value="<?php if (!empty($licence)) echo esc_attr($licence); ?>" />
			</label>
			<label for="theme-licence-uri">
				<span class="description"><?php esc_html_e('Lizenz URL:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-licence-uri" value="<?php if (!empty($licence_uri)) echo esc_attr($licence_uri); ?>" />
			</label>
			<label for="theme-tags">
				<span class="description"><?php esc_html_e('Theme Schlagworte:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-tags" value="<?php if (!empty($tags)) echo esc_attr(join(', ', $tags)); ?>" />
			</label>
			<label for="theme-text-domain">
				<span class="description"><?php esc_html_e('Theme Textdomain:', UpfrontThemeExporter::DOMAIN); ?></span>
				<input type="text" id="theme-text-domain" value="<?php if (!empty($text_domain)) echo esc_attr($text_domain); ?>" />
			</label>
			<div class="check">
				<label for="activate_theme" class="inline">
					<input type="checkbox" checked id="activate_theme" />
					<span class="description"><?php esc_html_e('Theme aktivieren', UpfrontThemeExporter::DOMAIN); ?></span>
				</label>
			</div>
			<div class="check">
				<label for="export_with_images" class="inline">
					<input type="checkbox" checked id="export_with_images" />
					<span class="description"><?php esc_html_e('Mit Bildern exportieren', UpfrontThemeExporter::DOMAIN); ?></span>
				</label>
			</div>
		</div>
	</div>
	<div class="buttons">
		<button type="button" class="edit info">
			<?php esc_html_e('Änderungen speichern', UpfrontThemeExporter::DOMAIN); ?>
		</button>
	</div>
<?php } ?>
