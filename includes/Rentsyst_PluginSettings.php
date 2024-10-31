<?php

namespace rentsyst\includes;
use rentsyst\admin\components\Rentsyst_View;

class Rentsyst_PluginSettings
{
	const BOOKING_PAGE_ID = 'booking_page_id';
	public static $settings;

	const ENABLE_CATALOG_VEHICLES = 'add_catalog_vehicles';
	const ENABLE_DESIGN_V2 = 'enable_design_v2';
	const CATALOG_PAGE_ID = 'rentsyst_catalog_page_id';
	const CATALOG_SINGLE_PAGE_ID = 'rentsyst_catalog_single_page_id';
	const CATALOG_FILTER_ENABLE = 'enable_catalog_filter';
	const CATALOG_FILTER_POSITION = 'filter_button_position';
	const CATALOG_FILTER_ACCENT_COLOR = 'filter_accent_color';
	const CATALOG_FILTER_ENABLE_FIX = 'filter_enable_fix';
	const PRIVACY_POLICY_PAGE_ID = 'privacy_policy_page_id';
	const CONFIRM_PAGE_ID = 'confirmation_page_id';

	public static function getDefaultSettings()
	{
		$settings = [
			'enable_booking_page' => 0,
			'add_page_to_menu' => 1,
			'enable_booking_button' => 0,
			'button_position' => 'bottom right',
			'button_color' => '#041928',
			'button_animation' => 1,
			'button_text' => 'Online booking',
			'form_position' => 'right',
			self::ENABLE_DESIGN_V2 => 1,
			self::PRIVACY_POLICY_PAGE_ID => 0,
			self::CONFIRM_PAGE_ID => 0,
			self::CATALOG_FILTER_ACCENT_COLOR => '#0894ff',
			self::CATALOG_FILTER_ENABLE => 1,
			self::CATALOG_FILTER_POSITION => 'right',
			self::ENABLE_CATALOG_VEHICLES => 0,
			self::CATALOG_FILTER_ENABLE_FIX => 1,
			self::CATALOG_PAGE_ID => 0,
			self::CATALOG_SINGLE_PAGE_ID => 0,
		];
		return $settings;
	}

	public static function getSettings()
	{
		if(!self::$settings) {
			self::$settings = self::prepareSettings();
		}
		return self::$settings;
	}

	public static function prepareSettings()
	{
		$settings = self::getDefaultSettings();
		foreach ($settings as $setting => $value){
			if(strpos($setting, 'rentsyst_') !== 0) {
				$optionName = 'rentsyst_' . $setting;
			} else {
				$optionName = $setting;
			}
			if(($oldSettings = get_option($optionName)) !== false) {
				$settings[$setting] = $oldSettings;
			}
			if(isset($_REQUEST[$setting]) && $_REQUEST[$setting] !== $settings[$setting]) {
				$settings[$setting] = sanitize_text_field($_REQUEST[$setting]);
				self::beforeChangeOption($setting, sanitize_text_field($_REQUEST[$setting]));
				update_option($optionName, sanitize_text_field($_REQUEST[$setting]));
			}
		}
		return $settings;
	}

	public static function beforeChangeOption($name, $value)
	{

		if($name === 'enable_booking_page') {
			if($value) {
				$postarr = [
					'post_content' => '[rentsyst_booking]',
					'post_title'   => 'Booking',
					'post_name'    => 'rentsyst_booking_page',
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'guid'         => 'rentsyst_booking_page'
				];
				update_option( self::BOOKING_PAGE_ID, wp_insert_post( $postarr ) );
			} else {
				wp_delete_post(get_option(self::BOOKING_PAGE_ID));
				delete_option(self::BOOKING_PAGE_ID);
			}
		} elseif ($name === 'add_catalog_vehicles') {
			if($value) {
				$view = new Rentsyst_View();
				$view->folderView = 'includes/templates';
				$postarr = [
					'post_content' => $view->render('catalog'),
					'post_title' => 'Catalog',
					'post_name' => 'catalog',
					'post_status' => 'publish',
					'post_type' => 'page',
					'guid' => 'catalog',
					'comments_status' => 'closed',
				];
				$id = wp_insert_post( $postarr );

				update_option(self::CATALOG_PAGE_ID, $id);

				$postarr = [
					'post_content' => $view->render('single'),
					'post_title' => 'Single vehicle',
					'post_name' => 'single-vehicle',
					'post_status' => 'publish',
					'post_type' => 'page',
					'guid' => 'single-vehicle'
				];
				$id = wp_insert_post( $postarr );

				update_option(self::CATALOG_SINGLE_PAGE_ID, $id);
			} else {
				$id = get_option(self::CATALOG_PAGE_ID);
				wp_delete_post($id);
				delete_option($id);

				$id = get_option(self::CATALOG_SINGLE_PAGE_ID);
				wp_delete_post($id);
				delete_option($id);
			}
		}
	}

	public static function isCatalogVehiclesEnable()
	{
		if(self::getSettings()[self::ENABLE_CATALOG_VEHICLES]) {
			return true;
		} else {
			return false;
		}
	}

	public static function isDesignV2Enable()
	{
		if(self::getSettings()[self::ENABLE_DESIGN_V2]) {
			return true;
		} else {
			return false;
		}
	}
}
