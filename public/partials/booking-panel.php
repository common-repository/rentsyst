<div class="RentsystWidgetCover" style="z-index: 100000; display: none;"></div>
<a class="RentsystCloseIcon <?= $settings['form_position'] ?? 'right'; ?>" href="#" style="z-index: 100500; display: none;"></a>
<div class="RentsystWidgetBlock <?= $settings['form_position'] ?? 'right'; ?> RentsystWidgetHide" style="z-index: 100001; overflow: auto">
	<?php
	require_once WP_RENTSYST_PLUGIN_DIR . '/public/Rentsyst_BookingWidget.php';
	echo Rentsyst_BookingWidget::widget();
	?>
</div>