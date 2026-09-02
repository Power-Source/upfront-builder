<?php
	$themes = array();
	$fallback_screenshot = plugins_url(THX_BASENAME . '/imgs/testImage.jpg');
	$current_theme = get_option('stylesheet');

	/**
	 * Get themes update info
	 *
	 * @var array Array of WP_Theme objects
	 */
	$updates = get_theme_updates();
	if (!is_array($updates)) $updates = array();

	foreach(wp_get_themes() as $stylesheet=>$theme) {
		if ($theme->get('Template') !== 'upfront') continue;

		if (!empty($updates[$stylesheet])) {
			$theme->uf_update = !empty($updates[$stylesheet]->update)
				? $updates[$stylesheet]->update
				: false
			;
		}
		$themes[$stylesheet] = $theme;
	}

	/**
	 * Base URL for redirection and download URL building
	 *
	 * @var string
	 */
	$redirection = remove_query_arg(array(
		'theme',
		'nonce',
		'action',
		'error'
	));
?>

<div class="wrap upfront_admin upfront-builder">
	<header class="upfront-builder-header">
		<div class="upfront-builder-header__content">
			<h1>
				<?php esc_html_e('Upfront Builder', UpfrontThemeExporter::DOMAIN); ?>
				<span class="upfront_logo"></span>
			</h1>
			<p class="upfront-builder-header__intro">
				<?php esc_html_e('Erstelle und verwalte responsive Upfront-Themes, passe ihre Designgrundlagen an und exportiere sie für andere Websites.', UpfrontThemeExporter::DOMAIN); ?>
			</p>
			<div class="upfront-builder-header__status">
				<span><?php esc_html_e('Aktives Theme', UpfrontThemeExporter::DOMAIN); ?>: <strong><?php echo esc_html(wp_get_theme($current_theme)->get('Name')); ?></strong></span>
				<span><?php printf(esc_html(_n('%s Upfront-Theme verfügbar', '%s Upfront-Themes verfügbar', count($themes), UpfrontThemeExporter::DOMAIN)), number_format_i18n(count($themes))); ?></span>
			</div>
		</div>
		<nav class="upfront-builder-header__actions" aria-label="<?php esc_attr_e('Schnellzugriff', UpfrontThemeExporter::DOMAIN); ?>">
			<a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=upfront_to_codepen')); ?>">
				<span class="dashicons dashicons-art" aria-hidden="true"></span>
				<?php esc_html_e('CodePen-Styleguide', UpfrontThemeExporter::DOMAIN); ?>
			</a>
			<a class="button" href="https://psource.eimen.net/wiki/upfront-themes/upfront-theme-entwickler/" target="_blank" rel="noopener noreferrer">
				<span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
				<?php esc_html_e('Theme-Dokumentation', UpfrontThemeExporter::DOMAIN); ?>
			</a>
		</nav>
	</header>

	<div class="postbox-container">
		<!-- Build New Theme -->
		<div class="postbox newtheme" id="new-theme">
			<h2 class="title"><?php esc_html_e('Neues Theme erstellen', UpfrontThemeExporter::DOMAIN); ?></h2>
			<div class="character"></div>
			<div class="newtheme-form" >
				<?php
					Thx_Template::plugin()->load('theme_form', array(
						'new' => true,
						'name' => '',
					));
				?>
				<div class="buttons">
					<button type="button" class="create theme">
						<?php esc_html_e('Mit dem Bauen beginnen', UpfrontThemeExporter::DOMAIN); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Existing Themes -->
		<div class="postbox themes" id="existing-theme">
			<?php if (!empty($themes)) { ?>
				<h2 class="title"><?php esc_html_e('Bestehendes Theme bearbeiten', UpfrontThemeExporter::DOMAIN); ?></h2>

				<div class="uf-thx-themes_container clearfix">
				<?php foreach ($themes as $key => $theme) { ?>
					<div class="uf-thx-theme <?php
					// if (!empty($_GET['theme']) && $theme->get_stylesheet() === $_GET['theme']) echo 'selected';
				?> <?php
					$extra_classes = array();

					if ($theme->get_stylesheet() === $current_theme) $extra_classes[] = 'current';
					if (!empty($theme->uf_update)) $extra_classes[] = 'wporg-conflict';

					echo join(' ', $extra_classes);
				?>" data-theme="<?php echo esc_attr($theme->get_stylesheet()); ?>">
						<a href="<?php
							echo esc_attr(add_query_arg('theme', $theme->get_stylesheet()));
						?>" data-download_url="<?php
							echo esc_url(add_query_arg(array(
								'action' => 'download',
								'theme' => $theme->get_stylesheet(),
								'nonce' => wp_create_nonce('download-' . $theme->get_stylesheet()),
							), $redirection));
						?>" >
							<?php
								$screenshot = $theme->get_screenshot() ? $theme->get_screenshot() : '';
								$screenshot = apply_filters('upfront_theme_catalog_screenshot', $screenshot, $theme->get_stylesheet());
							?>
							<?php if ( !empty($screenshot) ) { ?>
								<img src="<?php echo esc_url($screenshot); ?>" />
							<?php }?>
							<div class="uf-thx-caption">
								<span><?php echo esc_html($theme->get('Name')); ?></span>
								<button type="button" class="edit theme">
									<?php esc_html_e('Im Builder bearbeiten', UpfrontThemeExporter::DOMAIN); ?>
								</button>
								<button type="button" class="download" alt="" >
									<span class="btn-label-hidden">
									<?php esc_html_e('Download Theme', UpfrontThemeExporter::DOMAIN); ?>
									</span>
								</button>
							</div>
							<button type="button" class="edit info">
								<?php esc_html_e('Theme-Informationen bearbeiten', UpfrontThemeExporter::DOMAIN); ?>
							</button>
						</a>
					</div>
				<?php } ?>
				</div>
			<?php } else { ?>
				<label class="inline"><span class="description">
					<?php esc_html_e('Keine bestehenden Themes, bitte erstellen Sie ein neues.', UpfrontThemeExporter::DOMAIN); ?>
				</span></label>

			<?php } ?>
		</div><!-- /.postbox -->
	</div><!-- /.postbox-container -->
	<div class="postbox-modal-container">
		<div class="postbox edit-theme" id="edit-theme">
			<div id="postbox-modal-close">&times;</div>
			<div class="form_content"><!-- will be replaced with edit form --></div>
		</div><!-- /.postbox -->
	</div>
</div>
