<?php

require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_BuilderConstructorSetting.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_View.php';

class Rentsyst_BookingWidget
{
	private $view;
	private static $widget;
	public static $is_display;

	private $settings;

	public function __construct()
	{
		$view = new Rentsyst_View();
		$view->folderView = 'public/partials';
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

		$settings = ( new Rentsyst_BuilderConstructorSetting )->getPublicSettings();

		if(!$settings) {
			return false;
		}

		if($this->settings) {
			$settings = array_replace_recursive($settings, $this->settings);
		}
		return $this->view->render('booking', ['settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)]);
	}
}
