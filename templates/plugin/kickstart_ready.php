<div class="notice notice-info is-dismissible uf-thx-kickstart">
	<p>
		<?php esc_html_e('Es scheint, als hättest Du das Upfront-Theme auf deiner Webseite nicht aktiviert; dieses wird jedoch benötigt, um das Upfront-Builder-Plugin nutzen zu können.', UpfrontThemeExporter::DOMAIN); ?>
	</p>
	<p>
		<?php esc_html_e('Wir können das für dich beheben:', UpfrontThemeExporter::DOMAIN); ?>
		<button type="button" class="button button-primary" id="upfront-kickstart-start_building">
			<?php esc_html_e('Mit dem Bauen beginnen', UpfrontThemeExporter::DOMAIN); ?>
		</button>
		<button type="button" class="button" id="upfront-kickstart-go_away">
			<?php esc_html_e('Nicht mehr anzeigen', UpfrontThemeExporter::DOMAIN); ?>
		</button>
	</p>
	<p class="upfront-kickstart-out" style="display:none"></p>
</div>
<style>
.notice .upfront-kickstart-out.error { color: #c00; }
.notice .upfront-kickstart-out.success { color: #0c0; }
</style>
