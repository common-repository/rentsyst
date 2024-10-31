<?php

namespace rentsyst\admin\controllers;

use Exception;
use rentsyst\admin\components\Rentsyst_Api;
use rentsyst\admin\components\Rentsyst_CatalogVehicles;
use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\includes\RS_Company;
use rentsyst\includes\UrlConfig;
use function current_time;
use function update_option;

class Rentsyst_RentsystController extends Rentsyst_BaseController
{
	const TIME_VEHICLE_SYNC = 'time_vehicle_sync';
	const TIME_SETTINGS_SYNC = 'time_settings_sync';

	private $api;

	public function index()
	{
		$infoAboutSite    = [
			'title'   => get_bloginfo( 'name' ),
			'email'   => get_bloginfo( 'admin_email' ),
			'website' => [ get_bloginfo( 'url' ) ],
		];
		$authInfo         = get_option( 'rentsyst_auth' );
		$settings         = Rentsyst_PluginSettings::getSettings();

		$connectUrl      = add_query_arg( $infoAboutSite, UrlConfig::getCrmUrl() . '/auth/signup/create-agent/' );
		$disconnectorUrl = 'disconnect';

		$menus = wp_get_nav_menus();

		$menusPrepare = [];

		foreach ( $menus as $menu ) {
			$menusPrepare[ $menu->id ] = $menu->name;
		}

		$vehicleCatalogEnable = Rentsyst_PluginSettings::isCatalogVehiclesEnable();
		$lastTimeSync         = false;
		if ( $vehicleCatalogEnable ) {
			$lastTimeSync = get_option(self::TIME_VEHICLE_SYNC);
		}
		$syncVehicleUrl = 'sync-vehicle';

		$synchronizeSettingsUrl = 'sync-settings';

		$lastTimeSyncSettings = get_option(self::TIME_SETTINGS_SYNC);

		return $this->render( 'rentsyst', [
			'authInfo'             => $authInfo,
			'connectorUrl'         => $connectUrl,
			'disconnectorUrl'      => $disconnectorUrl,
			'settings'             => $settings,
			'vehicleCatalogEnable' => $vehicleCatalogEnable,
			'lastTimeSync'         => $lastTimeSync,
			'lastTimeSyncSettings' => $lastTimeSyncSettings,
			'syncVehicleUrl'       => $syncVehicleUrl,
			'synchronizeSettingsUrl' => $synchronizeSettingsUrl,

		] );
	}

	public function ajaxSyncVehicle()
	{
		try {
			ini_set('max_execution_time', '1000');
			ini_set('memory_limit', '256M');
			$vehicles = $this->getApi()->getAllVehicles();
			$imagesForUpload = Rentsyst_CatalogVehicles::updateVehicles($vehicles);
			$date = current_time('mysql');
			update_option(self::TIME_VEHICLE_SYNC, $date);
			echo json_encode(['status' => 'success', 'imagesForUpload' => $imagesForUpload, 'date' => $date]);
		} catch (\Exception $exception) {
			echo json_encode([
				'status' => 'error',
				'message' => $exception->getMessage(),
			]);
		}
		wp_die();
	}

	public function ajaxLoadImages()
	{
		$post_id = sanitize_text_field($_POST['message']);
		Rentsyst_CatalogVehicles::uploadImage($post_id);
		echo json_encode(['status' => 'success']);
		wp_die();
	}

	public function ajaxUpdateVehicleParams()
	{
		$params = $_POST['message'];

		update_option('rentsyst_booking_vehicle_params', $params);

		echo json_encode(['status' => 'success']);
		wp_die();
	}

	public function ajaxSyncSettings()
	{
		try {
			update_option(RS_Company::COMPANY_SETTINGS_ID, $this->getApi()->getCompanySettings());
			$date = current_time('mysql');
			update_option(self::TIME_SETTINGS_SYNC, $date);

			echo json_encode(['status' => 'success', 'date' => $date]);
		} catch (Exception $exception) {
			echo json_encode([
				'status' => 'error',
				'message' => $exception->getMessage(),
			]);
		}
		wp_die();
	}

	public function ajaxSaveInfo()
	{
		$response = sanitize_text_field($_POST['message']);
		$authInfo = json_decode( base64_decode( $response ) );
		update_option( 'rentsyst_auth', $authInfo );
		Rentsyst_Api::refreshInstance();
		update_option(RS_Company::COMPANY_SETTINGS_ID, $this->getApi()->getCompanySettings());
		$date = current_time('mysql');
		update_option(self::TIME_SETTINGS_SYNC, $date);
		echo json_encode(['status' => 'success', 'date' => $date]);
		wp_die();
	}

	public function ajaxDisconnect()
	{
		delete_option( 'rentsyst_auth' );
		delete_option( Rentsyst_Api::TOKEN_OPTIONS_NAME );

		echo json_encode(['status' => 'success']);
		wp_die();
	}

	public function add_page_to_menu( $page_slug, $menu_id )
	{
		$page_id = get_page_by_path( $page_slug )->ID;
		wp_update_nav_menu_item( $menu_id, 0,
			array(
				'menu-item-title'     => 'Reserve online',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish'
			) );
	}

	protected function getApi()
	{
		if(!$this->api) {
			$this->api = Rentsyst_Api::getInstance();
		}
		return $this->api;
	}

}
