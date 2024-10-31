<?php
global /** @var RS_Vehicle $rentsyst_vehicle */
$rentsyst_vehicle;

$content = $args['content'] ?? get_post(get_option(Rentsyst_PluginSettings::CATALOG_PAGE_ID))->post_content;
?>

<div class="rentsyst-catalog-item" data-id="<?= $rentsyst_vehicle->getId(); ?>">
	<?php
	echo do_shortcode($content);
	?>
</div>
