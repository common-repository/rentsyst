<?php

use rentsyst\site\Rentsyst_PaymentStatusWidget;

add_shortcode( 'rentsyst_payment', function ()
{
	if (!is_main_query() || !in_the_loop()) {
		return 'Confirmation page';
	}
	return Rentsyst_PaymentStatusWidget::widget();
} );
