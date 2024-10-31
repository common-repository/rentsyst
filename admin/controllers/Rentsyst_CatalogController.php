<?php

namespace rentsyst\admin\controllers;

use rentsyst\includes\Rentsyst_PluginSettings;

class Rentsyst_CatalogController extends Rentsyst_BaseController {

	public function index()
	{
		global $wp;
		$selfUrl =  $current_url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$settings         = Rentsyst_PluginSettings::getSettings();

		return $this->render('catalog',
			[
				'selfUrl' => $selfUrl,
				'settings' => $settings,
		]);
	}

}
