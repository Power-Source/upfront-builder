<div class="notice notice-error is-dismissible">
	<p>
		<?php esc_html_e('Leider scheint Upfront Core auf dieser Website nicht installiert zu sein.', UpfrontThemeExporter::DOMAIN); ?>
		<?php esc_html_e('Wir benötigen das, damit das Upfront Builder-Plugin funktioniert.', UpfrontThemeExporter::DOMAIN); ?>
		<?php echo wp_kses(
			sprintf(
				__('<a href="%s" target="_blank">Hier erhältlich.</a>', UpfrontThemeExporter::DOMAIN),
				'https://psource.eimen.net/wiki/upfront-dokumentation/upfront-builder-dokumentation/'
			), array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
			)
		); ?>
	</p>
</div>
