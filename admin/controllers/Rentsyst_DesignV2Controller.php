<?php

namespace rentsyst\admin\controllers;

use rentsyst\admin\components\Rentsyst_BuilderConstructorSetting;
use rentsyst\admin\components\Rentsyst_BuilderConstructorSettingV2;
use rentsyst\admin\components\Rentsyst_Language;
use function add_action;

class Rentsyst_DesignV2Controller extends Rentsyst_BaseController {

	public function index()
	{
		$settings = (new Rentsyst_BuilderConstructorSettingV2())->getAdminSettings();

		return $this->render('design-v2',
			[
				'settings' => json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)
			]);
	}

	public function ajaxSave($data)
	{
		$previouslySettings = get_option('rentsyst_design_settings_v2');
		$previouslySettings = $previouslySettings ? $previouslySettings : [];

		$currentLanguage = Rentsyst_Language::getLanguageCode();

		if($currentLanguage) {
			$translationSettingKey = 'rentsyst_design_settings_v2_' . $currentLanguage;
			$translationSettings = $this->separeteTranslationPhrases( $data );
			$previouslyTranslationSettings = get_option($translationSettingKey);
			if($previouslyTranslationSettings) {
				$translationSettings = array_merge( $previouslyTranslationSettings, $translationSettings );
			}
			update_option($translationSettingKey, $translationSettings);
		}

		$newSettings = array_merge($previouslySettings, $data);
		update_option('rentsyst_design_settings_v2', $newSettings);
		return  ['status' => 'success'];
	}

	public function separeteTranslationPhrases(array &$settings)
	{
		$result = [];
		foreach ( $settings['components'] as $key => $component ) {
			$result['components'][$key]['text'] = $component['text'];
			unset($settings['components'][$key]['text']);
		}
		return $result;
	}

}
