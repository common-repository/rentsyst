<?php

namespace rentsyst\site;

use Exception;
use rentsyst\admin\components\Rentsyst_BuilderConstructorSetting;
use rentsyst\admin\components\Rentsyst_BuilderConstructorSettingV2;
use rentsyst\admin\components\Rentsyst_View;
use rentsyst\includes\RS_Company;

class Rentsyst_BookingWidget
{
	private $view;
	private static $widget;
	public static $is_display;

	private $settings;

	public function __construct()
	{
		$view = new Rentsyst_View();
		$view->folderView = 'site/partials';
		$this->view = $view;

		Rentsyst_ResourceLoader::registerAll();

	}

	public static function widget($settings = [])
	{
		try {
			if(!get_option(RS_Company::COMPANY_SETTINGS_ID)) {
				return false;
			}
			if(!self::$is_display) {
				$widget = new self();
				self::$widget = $widget;
				self::$is_display = true;
				$widget->settings = $settings;
				return $widget->run();
			} else {
				return false;
			}
		} catch (Exception $exception) {
			if(current_user_can('administrator')) {
				$message = $exception->getMessage();
			} else {
				$message = 'Booking temporary not available, please, try later';
			}
			echo "<p style=\"text-align:center\">$message</p>";

		}
	}

	public function run()
	{
		nocache_headers();

		if($this->isV2()) {
			$settings = ( new Rentsyst_BuilderConstructorSettingV2 )->getPublicSettings();
		} else {
			$settings = ( new Rentsyst_BuilderConstructorSetting )->getPublicSettings();
		}

		if(!$settings) {
			return false;
		}

		if($this->settings) {
			$settings = array_replace_recursive($settings, $this->settings);
		}
		if($this->isV2()) {
			return $this->view->render('booking-v2', ['settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)]);

		} else {
			return $this->view->render('booking', ['settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)]);

		}
	}

	private function isV2()
	{
		return get_option('rentsyst_enable_design_v2');
	}
}
