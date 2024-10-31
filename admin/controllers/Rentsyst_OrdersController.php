<?php

require_once WP_RENTSYST_PLUGIN_DIR . '/admin/controllers/Rentsyst_BaseController.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/models/Rentsyst_Order.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_Api.php';

class Rentsyst_OrdersController extends BaseController {

	public function index()
	{
		$this->handleActions($_REQUEST);

		return $this->render('orders');
	}


	private function handleActions( $params )
	{
		$action = sanitize_text_field($params['action']);
		$id = sanitize_text_field($params['id']);
		if($action === 'delete') {
			Order::delete($id);
		}
	}


}