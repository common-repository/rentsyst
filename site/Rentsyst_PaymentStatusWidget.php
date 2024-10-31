<?php

namespace rentsyst\site;

use Exception;
use rentsyst\admin\components\Rentsyst_Api;
use rentsyst\admin\components\Rentsyst_BuilderConstructorSetting;
use rentsyst\admin\components\Rentsyst_BuilderConstructorSettingV2;
use rentsyst\admin\components\Rentsyst_View;
use rentsyst\includes\RS_Company;
use function array_replace_recursive;
use function sanitize_text_field;

class Rentsyst_PaymentStatusWidget
{
	private $view;
	private static $widget;
	public static $is_display;


	public function __construct()
	{
		$view = new Rentsyst_View();
		$view->folderView = 'site/partials';
		$this->view = $view;
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
				$output = $widget->run();
				self::$is_display = true;
				Rentsyst_BookingWidget::$is_display = true;
				return $output;
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
		if(get_option('rentsyst_enable_design_v2')) {
			return $this->runV2();
		}

		Rentsyst_ResourceLoader::registerForPayment();
		$settings = ( new Rentsyst_BuilderConstructorSetting )->getPublicSettings();
		$api = Rentsyst_Api::getInstance();
		if(!$settings || !isset($_POST['message'])) {
			return false;
		}
		$htmlContent = $_POST['message'];
		$htmlContent = str_replace(array("\t", "\r", "\n", "\\\""), ' ', $htmlContent);
		$status = sanitize_text_field($_POST['status']) ?? 'success';
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

	private function runV2()
	{
		Rentsyst_ResourceLoader::registerAll();
		$settings = ( new Rentsyst_BuilderConstructorSettingV2 )->getPublicSettings();

		$settings = array_replace_recursive($settings, [
			'showPage' => 'confirmation',
			'payment_page' => [
				'order_id' => sanitize_text_field($_POST['order_id'] ?? ''),
				'type' => sanitize_text_field($_POST['type'] ?? ''),
				'order_unique_number' => sanitize_text_field($_POST['order_unique_number'] ?? ''),
				'order_manage_link' => sanitize_text_field($_POST['order_manage_link'] ?? ''),
				'reason_of_error' => sanitize_text_field($_POST['reason_of_error'] ?? ''),
				'pay_again_link' => sanitize_text_field($_POST['pay_again_link'] ?? ''),
		]
		]);

		return $this->view->render('booking-v2', ['settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)]);

	}

}
