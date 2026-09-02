<div class="notice notice-info is-dismissible uf-thx-kickstart">
	<p>
		<?php esc_html_e('Leider scheint Upfront Core auf dieser Website nicht installiert zu sein.', UpfrontThemeExporter::DOMAIN); ?>
		<?php esc_html_e('Der Builder kann die aktuelle stabile Version direkt aus dem offiziellen GitHub-Repository installieren.', UpfrontThemeExporter::DOMAIN); ?>
	</p>
	<p>
		<button type="button" class="button button-primary" id="upfront-kickstart-start_building">
			<?php esc_html_e('Framework installieren und Builder starten', UpfrontThemeExporter::DOMAIN); ?>
		</button>
		<a class="button" href="<?php echo esc_url(Thx_Kickstart::UPFRONT_RELEASES_URL); ?>" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e('Releases ansehen', UpfrontThemeExporter::DOMAIN); ?>
		</a>
	</p>
	<p class="upfront-kickstart-out" style="display:none"></p>
</div>
