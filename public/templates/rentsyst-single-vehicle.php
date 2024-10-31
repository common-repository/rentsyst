<?php

global /** @var RS_Vehicle $rentsyst_vehicle */
$rentsyst_vehicle;

$original_page_id = get_option(Rentsyst_PluginSettings::CATALOG_SINGLE_PAGE_ID);
$single_vehicle_page_id = Rentsyst_Language::getConnectedPagesId($original_page_id)[Rentsyst_Language::getLanguageCode()] ?? $original_page_id;

?>

    <div class="rentsyst-catalog-item" data-id="<?= $rentsyst_vehicle->getId(); ?>">
		<?php
		    echo do_shortcode(get_post($single_vehicle_page_id)->post_content);
		?>
    </div>

<?php
