<?php

namespace rentsyst\admin\controllers;

use rentsyst\admin\components\Rentsyst_BuilderConstructorSetting;
use rentsyst\admin\components\Rentsyst_Language;

class Rentsyst_DesignController extends Rentsyst_BaseController {

	public function index()
	{
		$saveUrl = add_query_arg( ['action' => '/rentsyst/design/save'], '/wp-admin/admin-ajax.php' );

		$settings = (new Rentsyst_BuilderConstructorSetting)->getAdminSettings();


		return $this->render('design',
			[
				'settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)
			]);
	}

	public function ajaxSave($data)
	{
		$previouslySettings = get_option('rentsyst_design_settings');
		$previouslySettings = $previouslySettings ? $previouslySettings : [];

		$currentLanguage = Rentsyst_Language::getLanguageCode();

		if($currentLanguage) {
			$translationSettingKey = 'rentsyst_design_settings_' . $currentLanguage;
			$translationSettings = $this->separeteTranslationPhrases( $data );
			$previouslyTranslationSettings = get_option($translationSettingKey);
			if($previouslyTranslationSettings) {
				$translationSettings = array_merge( $previouslyTranslationSettings, $translationSettings );
			}
			update_option($translationSettingKey, $translationSettings);
		}

		$newSettings = array_merge($previouslySettings, $data);
		update_option('rentsyst_design_settings', $newSettings);
		return  ['status' => 'success'];
	}

	public function separeteTranslationPhrases(array &$settings)
	{
		$result = [];
		foreach ( $settings as $key => $setting ) {
			if(is_array($setting)) {
				if($key === 'text') {
					$result[$key] = $setting;
					unset($settings[$key]);
				}
				$subResult = $this->separeteTranslationPhrases($setting);
				if($subResult) {
					$result[ $key ] = $subResult;
					$settings[$key] = $setting;
				}
			}

			if($key === 'title' || $key === 'placeholder') {
				$result[$key] = $setting;
				unset($settings[$key]);
			}
		}
		return $result;
	}

}
