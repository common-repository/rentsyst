<?php

require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_BuilderConstructorSetting.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_View.php';

class Rentsyst_PaymentStatusWidget
{
	private $view;
	private static $widget;
	public static $is_display;


	public function __construct()
	{
		$view = new Rentsyst_View();
		$view->folderView = 'public/partials';
		$this->view = $view;
		Rentsyst_ResourceLoader::registerForPayment();
	}

	public static function widget()
	{
		try {
			if(!get_option(RS_Company::COMPANY_SETTINGS_ID)) {
				return false;
			}
			if(!self::$widget) {
				$widget = new self();
				self::$widget = $widget;
				self::$is_display = true;
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
		$settings = ( new Rentsyst_BuilderConstructorSetting )->getPublicSettings();
		$api = Rentsyst_Api::getInstance();
		if(!$settings || !isset($_POST['message'])) {
			return false;
		}
		$htmlContent = $_POST['message'];
		$htmlContent = str_replace(array("\t", "\r", "\n", "\\\""), ' ', $htmlContent);
		$status = sanitize_text_field($_POST['status']);
		$order_id = sanitize_text_field($_POST['order_id']);

			$settings = array_replace_recursive($settings, [
				'payment_page' => [
					'type' => $status,
					'order_id' =>  $order_id,
					'content' => addcslashes(addcslashes($htmlContent, '"'), '"'),
				],
				'urls' => [
					'getOrder' => [
						'link' => $api->baseUrl . $api->getConfig()['getOrder']
						]
				]
			]);
		return $this->view->render('payment', ['settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)]);
	}
}
