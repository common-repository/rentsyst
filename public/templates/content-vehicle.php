<?php
global /** @var RS_Vehicle $vehicle */
$vehicle;
?>

<div class="rentsyst-catalog-item" data-id="<?= $vehicle->getId(); ?>">
	<?php
	echo do_shortcode(get_post(get_option(Rentsyst_PluginSettings::CATALOG_PAGE_ID))->post_content);
	?>
</div>