<?php

use rentsyst\admin\components\Rentsyst_Api;
use rentsyst\includes\RS_Company;

require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_Api.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_CatalogVehicles.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/includes/RS_Company.php';

class Rentsyst_Updater {

	public static function update() {
		try {
			update_option(RS_Company::COMPANY_SETTINGS_ID, (Rentsyst_Api::getInstance())->getCompanySettings());

		} catch (Exception $exception) {

		}
	}

}
