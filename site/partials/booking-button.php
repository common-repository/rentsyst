<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public/partials
 */
?>

<a id="rentsyst_booking_button" class="RentsystButton <?= $settings['button_position']; ?>" href="#"
   style="z-index: 800; display:<?= $settings['enable_booking_button'] ? 'block' : 'none'; ?>">
    <div class="RentsystButtonBackground" style="background-color: <?= $settings['button_color']; ?>;"></div>
    <div class="RentsystButtonWave"
         style="border-color: <?= $settings['button_color']; ?>; color: <?= $settings['button_color']; ?>; display:  <?= $settings['button_animation'] ? 'block' : 'none'; ?>;"></div>
    <div class="RentsystButtonText"><?= $settings['button_text']; ?></div>
    <div class="RentsystButtonIcon"></div>
</a>

<?php
require_once WP_RENTSYST_PLUGIN_DIR . '/site/partials/booking-panel.php';
?>
