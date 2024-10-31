<?php

use rentsyst\includes\RS_Company;

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/RS_Company.php';

add_shortcode('rentsyst_company_currency' ,function () {
	return RS_Company::getInstance()->getCurrency();
});
